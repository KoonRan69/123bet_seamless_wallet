<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\PersonalInfo;
use App\Http\Requests\Register;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Session;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;

use App\Model\GoogleAuth;
use App\Model\User;
use App\Model\Profile;
use App\Model\Log;
use App\Model\Wallet;
use Illuminate\Support\Facades\Hash;
use App\Model\Investment;
use App\Jobs\SendMailJobs;
use App\Jobs\SendTelegramJobs;
use App\Mails\UserSignUp;
use App\Model\Eggs;
use App\Model\LogUser;
use App\Model\MUser;
use App\Model\Pools;
use App\Model\Fishs;
use App\Model\Foods;
use Exception;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api', ['only' => ['getProfile', 'memberTree', 'postAddMember', 'postKYC', 'getMemberDetail', 'getChildTotalEgg1']]);
    }
    
	public function getProfile(){
		$user = User::where('User_Email', $req->email)->first();
		if($user){
			return $this->response(200, [], $user->User_Email, [], true);
		}
		
	}
	
  	public function postChangePassword(Request $req)
    {
        $user = $req->user();

        $validator = Validator::make($req->all(), [
            'passwordCurrent' => 'required',
            'password' => 'required|min:6',
            'passwordConfirm' => 'required|same:password'
        ],[
            'passwordCurrent.required' => trans('notification.password_required') , 
            'password.required' => trans('notification.password_required') , 
            'passwordConfirm.required' => trans('notification.password_required') , 
            'passwordCurrent.min' => trans('notification.password_minimum_6_characters'),
            'password.min' => trans('notification.password_minimum_6_characters'),
            'passwordConfirm.min' => trans('notification.password_minimum_6_characters'),
          	'passwordConfirm.same' => trans('notification.Re_password_and_Password_not_same'),
        ]);
      	//dd(123);
        $check_user = DB::table('users')->where('User_ID', $user->User_ID)->first();
        if (!$check_user) {
            return response(array('status' => false, 'message' => trans('notification.Account_does_not_exist')), 200);
        }
      	if (strlen($req->password) < 6) {
            return response(array('status' => false, 'message' => trans('notification.password_minimum_6_characters')), 200);
        }
      	if($req->password == $req->passwordCurrent){
      		return $this->response(200, [], trans('notification.The_current_password_cannot_be_the_same_as_the_new_password') , [], false);
    	}
      	if ($req->password != $req->passwordConfirm) {
            return response(array('status' => false, 'message' => trans('notification.Confirm_password_is_not_the_same_as_password')), 200);
        }
      
      
        if (Hash::check($req->passwordCurrent, $check_user->User_Password)) {
          	$update = DB::table('users')->where('User_ID', $user->User_ID)->update([
                'User_Password'=> bcrypt($req->password),'User_Evo_Password'=>$req->password,'User_Sbobet_Password'=>$req->password
            ]);
            return $this->response(200, [], trans('notification.Change_password_success'), [], true);
        }
        return response(array('status' => false, 'message' => trans('notification.Old_password_is_incorrect')), 200);
        
    }
  
    public function postKYC(Request $data)
    {
        $user = $data->user();

        $validator = Validator::make($data->all(), [
            'passport' => 'required',
            'passport_image' => 'required|image|mimes:png,jpg',
            'passport_image_selfie' => 'required|image|mimes:png,jpg'
        ],[
            'passport.required' => trans('notification.Miss_passport') , 
            'passport_image.required' => trans('notification.Miss_passport_image') , 
            'passport_image_selfie.required' => trans('notification.Miss_passport_image_selfie') , 
        ]);

		if ($validator->fails()) {
			foreach ($validator->errors()->all() as $value) {
				// return $error;
				return $this->response(200, [], $value, $validator->errors(), false);
			}
        }

        if (!isset($data->passport)) {
            return response(array('status' => false, 'message' => trans('notification.Miss_passport')), 200);
        }
        if (!$data->hasFile('passport_image')) {
            return response(array('status' => false, 'message' => trans('notification.Miss_passport_image')), 200);
        }
        if (!$data->hasFile('passport_image_selfie')) {
            return response(array('status' => false, 'message' => trans('notification.Miss_passport_image_selfie')), 200);
        }
        $check_passport = DB::table('profile')->where('Profile_Passport_ID', $data->passport)->first();
        if ($check_passport) {
            return response(array('status' => false, 'message' => trans('notification.The_passport_has_already_been_taken')), 200);
        }
        $checkExist = Profile::where('Profile_User', $user->User_ID)->whereIn('Profile_Status', [0, 1])->first();
        if ($checkExist) {
            return response(array('status' => false, 'message' => trans('notification.Requested_KYC_Please_waiting_confirm')), 200);
        }
        $passportID = $data->passport;
        //get file extension
        $passportImageExtension = $data->file('passport_image')->getClientOriginalExtension();
        $passportImageSelfieExtension = $data->file('passport_image_selfie')->getClientOriginalExtension();

        // set folder and file name
        $randomNumber = uniqid();
        $passportImageStore = "users/" . $user->User_ID . "/profile/passport_image_" . $user->User_ID . "_" . $randomNumber . "." . $passportImageExtension;
        $passportImageSelfieStore = "users/" . $user->User_ID . "/profile/passport_image_selfie_" . $user->User_ID . "_" . $randomNumber . "." . $passportImageSelfieExtension;
        //send to Image server
        // return $passportImageSelfieStore;
        $passportImageStatus = Storage::disk('ftp')->put($passportImageStore, fopen($data->file('passport_image'), 'r+'));
        $passportImageSelfieStatus = Storage::disk('ftp')->put($passportImageSelfieStore, fopen($data->file('passport_image_selfie'), 'r+'));

        if ($passportImageStatus and $passportImageSelfieStatus) {
            $insertProfileData = [
                'Profile_User' => $user->User_ID,
                'Profile_Passport_ID' => $passportID,
                'Profile_Passport_Image' => $passportImageStore,
                'Profile_Passport_Image_Selfie' => $passportImageSelfieStore,
                'Profile_Time' => date('Y-m-d H:i:s')
            ];
            $inserStatus = Profile::create($insertProfileData);
            if ($inserStatus) {
                $kyc_type = config('utils.action.post_kyc');
                LogUser::addLogUser($user->User_ID, $kyc_type['action_type'], $kyc_type['message'], $data->ip());
                //Gửi telegram thông báo lệh hoa hồng
                // $message = $user->User_ID. " Post KYC\n"
                // 				. "<b>User ID: </b>\n"
                // 				. "$user->User_ID\n"
                // 				. "<b>Email: </b>\n"
                // 				. "$user->User_Email\n"
                // 				. "<b>POST KYC Time: </b>\n"
                // 				. date('d-m-Y H:i:s',time());

                // dispatch(new SendTelegramJobs($message, -364563312));
                //kiem tra KYC
                $checkKYC = Profile::where('Profile_User', $user->User_ID)->whereIn('Profile_Status', [0, 1])->first();
                if ($checkKYC) {
                    $reason = '';
                    $KYC = $checkKYC->Profile_Status;
                    $passport_image = config('url.media') . $checkKYC->Profile_Passport_Image;
                    $passport_image_selfie = config('url.media') . $checkKYC->Profile_Passport_Image_Selfie;
                } else {
                    $KYC = -1;
                    $reason = 'Your Profile KYC Is Unverify!';
                    $passport_image = '';
                    $passport_image_selfie = '';
                }
                $KYC_infor['status'] = $KYC;
                $KYC_infor['reason'] = $reason;
                $KYC_infor['passport'] = $passportID;
                $KYC_infor['passport_image'] = $passport_image;
                $KYC_infor['passport_image_selfie'] = $passport_image_selfie;
                return $this->response(200, ['check_kyc' => $KYC_infor], trans('notification.Update_profile_noted'));
            }
            return $this->response(200, [], trans('notification.Please_contact_admin'), [], false);
        }
        return $this->response(200, [], trans('notification.Update_profile_error'), [], false);
    }

    public function memberList(Request $request)
    {
        $user = $request->user();
        $user_list = $this->getList($user, $request);

        $list = [];
      	$levelAgency = [0 => 'Member', 1 => 'Silve', 2 => 'Gold', 3 => 'Platinum', 4 => 'Diamond', 5 => 'Royale', 6 => 'Crowd'];
        for ($i = 0; $i < count($user_list); $i++) {
            $list[$i] = [
                'User_ID' => (int)$user_list[$i]->User_ID,
                'VolumeTrade' => 0,
                'LevelAgency' => $levelAgency[$user_list[$i]->User_Level_Agency],
                'UserEmail' => $user_list[$i]->User_Email,
                'Parent' => (int)$user_list[$i]->User_Parent,
                'Created_Date' => $user_list[$i]->User_RegisteredDatetime,
                'F' => (int)$user_list[$i]->f,
                'Level' => 'Member',
                'last_login' => $this->getLastLogin($user_list[$i]->User_ID),
            ];
            
        }
        
        $current_page = $user_list->currentPage();
        $total_page = $user_list->lastPage();
        $total_member = $user_list->total();
        
        return $this->response(200, [
            'list' => $list,
            'current_page' => $current_page,
            'total_page' => $total_page,
            'total_member' => $total_member,
        ]);
    }
    public function getLastLogin($user){
        $lastlogin = DB::table('log_user')->where('user', $user)->orderBy('datetime', 'DESC')->first();
        return $lastlogin->datetime??null;
    }

     


    public function memberTree(Request $request)
    {
        $user = $request->user();
        $user_list = $this->getTree($user);
        return $this->response(200, ['trees' => $user_list]);
    }

    public function getList($user, $request = null, $limit = 25)
    {
        $user_list = User::select('Profile_Status', 'User_ID', 'User_Email', 'User_Level_Agency', 'User_Name', 'User_Phone', 'User_FullName', 'User_RegisteredDatetime', 'User_Parent', DB::raw("(CHAR_LENGTH(User_Tree)-CHAR_LENGTH(REPLACE(User_Tree, ',', '')))-" . substr_count($user->User_Tree, ',') . " AS f, User_Tree"))
            ->leftJoin('profile', 'Profile_User', 'User_ID')
            ->whereRaw('User_Tree LIKE "' . $user->User_Tree . '%"')
            ->where('User_ID', '<>', $user->User_ID)
            ->orderBy('User_RegisteredDatetime', 'DESC');
        if( isset($request) ){
            if($request->user_id){
                $user_list = $user_list->where('User_ID', $request->user_id);
            }
            if($request->created_at){
                $user_list = $user_list->whereDate('User_RegisteredDatetime','<=', $request->created_at);
            }
            
            if($request->user_email){
                $user_list = $user_list->where('User_Email', 'LIKE', "%$request->user_email%");
            }
            if($request->user_f){
                $user_list = $user_list->whereRaw("(CHAR_LENGTH(User_Tree)-CHAR_LENGTH(REPLACE(User_Tree, ',', '')))-" . substr_count($user->User_Tree, ',') . " = ". $request->user_f);
            }
        }
        if($limit){
            $user_list = $user_list->paginate($limit);
        }else{
            $user_list = $user_list->get();
        }
        return $user_list;
    }

    public function getStaticMember($user)
    {
        $user_list = User::select('User_ID', 'User_Email', DB::raw("(CHAR_LENGTH(User_Tree)-CHAR_LENGTH(REPLACE(User_Tree, ',', '')))-" . substr_count($user->User_Tree, ',') . " AS f"))
            ->whereRaw("(CHAR_LENGTH(User_Tree)-CHAR_LENGTH(REPLACE(User_Tree, ',', '')))-" . substr_count($user->User_Tree, ',') . " <= 3")
            ->whereRaw('User_Tree LIKE "' . $user->User_Tree . ',%"')
            ->get();
        return $user_list;
    }

    public function getTree($user)
    {
        if (!isset($user->user_id)) {
            $userID = $user->User_ID;
        } else {
            $userID = $user->user_id;
            $user = User::find($userID);
        }
        $amount_investment = DB::table('investment')
            ->where('investment_User', $userID)
            ->where('investment_Status', 1)
            ->sum(DB::raw('investment_Amount * investment_Rate'));
        $total_invest_branch = User::join('investment', 'investment_User', 'User_ID')->where('User_Tree', 'LIKE', $user->User_Tree . ',%')->sum(DB::raw('investment_Amount * investment_Rate'));
        $list = array(
            'id' => $userID,
            'name' =>  $user->User_Email,
            'title' => $userID,
            'amount_investment' => number_format($amount_investment, 2, ',', ''),
            'Sales' => number_format($total_invest_branch, 2, ',', ''),
            'children' => $this->buildTree($userID),
            'className' => 'node-tree ' . strtoupper($user->user_Name),
        );
        return $list;
    }

    function buildTree($idparent, $idRootTemp = null, $barnch = null)
    {

        $build = User::select('User_Email', 'User_Name', 'User_ID', 'User_Tree')
            ->where('User_Parent', $idparent)->GET();
        $child = array();
        if (count($build) > 0) {
            for ($i = 0; $i < count($build); $i++) {
                if (isset($build[$i])) {
                    $amount_investment = DB::table('investment')
                        ->where('investment_User', $build[$i]->User_ID)
                        ->where('investment_Status', 1)
                        ->sum(DB::raw('investment_Amount * investment_Rate'));
                    $total_invest_branch = User::join('investment', 'investment_User', 'User_ID')->where('User_Tree', 'LIKE', $build[$i]->User_Tree . ',%')->sum(DB::raw('investment_Amount * investment_Rate'));
                    $child[] = array(
                        'id' => $build[$i]->User_ID,
                        'name' => $build[$i]->User_Email,
                        'title' => $build[$i]->User_ID,
                        'amount_investment' => number_format($amount_investment, 2, ',', ''),
                        'Sales' => number_format($total_invest_branch, 2, ',', ''),
                        'className' => 'node-tree ' . strtoupper($build[$i]->User_Name),
                        'children' => $this->buildTree($build[$i]->User_ID),
                    );
                }
            }
        }
        return $child;
    }

    public function postAddMember(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'User_Name' => 'required|min:6|max:255',
            'User_Phone' => 'required',
            'password' => 'required|min:6|max:255',
            'password_confirm' => 'required|min:6|max:255'
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $value) {
                return $this->response(200, [], $value, $validator->errors(), false);
            }
        }

        $user = $request->user();


        $sponsor = $user->User_ID;
        $sponserInfo = User::where('User_ID', $sponsor)->first();
        $userID = $this->RandomIDUser();

        $addMember = config('utils.action.add_member');
        LogUser::addLogUser($user->User_ID, $addMember['action_type'], 'Invite ' . $userID . ' join', $request->ip());

        $userTree = $sponserInfo->User_Tree . "," . $userID;
        if($request->password != $request->password_confirm){
            return $this->response(200, [], 'Password confirm not match !', [], false);
        }
        $password = $request->password;
        // $token = Crypt::encryptString($request->User_Email . ':' . time());
        $token = Crypt::encryptString($request->User_Email . ':' . time() . ':' . $password);

        $currentUser = new User();
        $currentUser->User_ID = $userID;
        $currentUser->User_Name = $request->User_Name;
        $currentUser->User_Phone = $request->User_Phone;
        $currentUser->User_EmailActive = 1;
        $currentUser->User_Password = bcrypt($password);
        $currentUser->User_RegisteredDatetime = date('Y-m-d H:i:s');
        $currentUser->User_Parent = $sponsor;
        $currentUser->User_Tree = $userTree;
        $currentUser->User_Level = 0;
        $currentUser->User_Token = $token;
        $currentUser->User_Agency_Level = 0;
        $currentUser->User_Status = 1;

        // $currentUser->save();

        if (!$currentUser) {
            return $this->response(200, [], 'There is an error, please contact admin', [], false);
        }
        // try {
            
        //     // gửi mail thông báo
        //     // $data = array('User_ID' => $userID, 'User_Email' => $request->User_Email, 'token' => $token);
        //     $data = [
        //         'User_Name' => $currentUser->User_Name,
        //         'User_Phone' => $currentUser->User_Phone,
        //         'User_ID' => $currentUser->User_ID,
        //         'User_Token' => $currentUser->User_Token,
        //         'User_Parent' => $currentUser->User_Parent,
        //         'User_Tree' => $currentUser->User_Tree,
        //         'User_PasswordNotHash' => $currentUser->User_PasswordNotHash,
        //     ];
        //     dispatch(new SendMailJobs('add-user', $data, 'Active Account Member!', $user->User_ID));
        //     // dispatch(new SendMailJobs('Active', $data, 'Active Account!', $userID));
        //     // Mail::to($currentUser->User_Email)->send(new UserSignUp($currentUser));
        // } catch (Exception $e) {
        //     return $this->response(200, [], 'Wrong email format', [], false);
        // }
        return $this->response(200, ['user' => $currentUser], 'Registration successful, please check your email to confirm!');
    }

    public function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public function RandomIDUser()
    {
        $id = rand(100000, 999999);
        //TẠO RA ID RANĐOM
        $user = User::where('User_ID', $id)->first();
        //KIỂM TRA ID RANDOM ĐÃ CÓ TRONG USER CHƯA
        if (!$user) {
            return $id;
        } else {
            return $this->RandomIDUser();
        }
    }

    public function getMemberDetail($id)
    {
        $user = User::where('User_ID', $id)->first();
        $total_egg = count(Eggs::where(['Owner' => $id,])->get());
		$total_food = Foods::where(['Owner' => $id,])->sum('Amount');
		$total_pool = count(Pools::where(['Owner' => $id,])->get());

        return $this->response(200, [
            'email' => $user? $user->User_Email: null,
            'total_egg' => $total_egg,
            'total_food' => $total_food,
            'total_pool' => $total_pool,
        ]);
    }
}
