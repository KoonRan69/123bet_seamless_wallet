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
use App\Model\Investment;
use App\Jobs\SendMailJobs;
use App\Jobs\SendTelegramJobs;
use App\Mails\UserSignUp;
use App\Model\Eggs;
use App\Model\LogUser;
use App\Model\MUser;
use App\Model\GameBet;
use App\Model\Pools;
use App\Model\Fishs;
use App\Model\Foods;
use Exception;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
  public function __construct(){
    $this->middleware('auth:api', ['only' => ['getBlockUserMember','memberList', 'memberTree', 'postAddMember', 'postKYC', 'getMemberDetail', 'getChildTotalEgg1']]);
  }

  public function checkEmail(Request $req){
    $user = User::where('User_Email', $req->email)->first();
    if($user){
      return $this->response(200, [], $user->User_Email, [], true);
    }

  }

  public function getBlockUserMember(Request $request){
    $userLogin = $request->user();
    $userMember = User::find($request->userid);
    if(!$userMember) return $this->response(200, [],"User does not exist" , [], false);

    if($userMember->User_Parent_AddMember == $userLogin->User_ID){
      if ($userMember->User_Block == 0) {
        $cmt_log = "(Parent) Block ID User: " . $request->userid;
        Log::insertLog($userLogin->User_ID, "(Parent) Block User", 0, $cmt_log);
        $userMember->User_Block = 1;
        $userMember->save();
        return $this->response(200, [],"Block User Success!" , [], true);
      } else {
        $cmt_log = "(Parent) UnBlock ID User: " . $request->userid;
        Log::insertLog($userLogin->User_ID, "(Parent) UnBlock User", 0, $cmt_log);
        $userMember->User_Block = 0;
        $userMember->save();
        return $this->response(200, [],"UnBlock User Success!" , [], true);
      }
    }
    return $this->response(200, [],"User $request->useri is not the account you added" , [], false);
  }

  public function postKYC(Request $data)
  {
    $user = $data->user();

    $validator = Validator::make($data->all(), [
      'passport' => 'required',
      'passport_image' => 'required|image|mimes:png,jpg,jpeg',
      'passport_image_selfie' => 'required|image|mimes:png,jpg,jpeg'
    ],[
      'passport' => trans('notification.Miss_passport') ,
      'passport_image' => trans('notification.Miss_passport_image') ,
      'passport_image_selfie' => trans('notification.Miss_passport_image_selfie') ,
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
    return $this->response(200, [],trans('notification.Update_profile_error') , [], false);
  }

  public function memberListAgency(Request $request)
  {
    $user = $request->user();
    if($user->User_Level != 1 && $user->User_Level_Agency != 1){
      return $this->response(200, [], 'Error!', [], false);
    }
    $user_list = $this->getList($user, $request);
    $list = [];
    $levelAgency = [0 => 'Member', 1 => 'Silve', 2 => 'Gold', 3 => 'Platinum', 4 => 'Diamond', 5 => 'Royale', 6 => 'Crowd'];
    $fromDate = date('Y-m-d 00:00:00', strtotime('monday this week'));
    $toDate = date('Y-m-d H:i:s');
    for ($i = 0; $i < count($user_list); $i++) {
      $totalBet = GameBet::getTotalBet($user_list[$i]->User_ID, $fromDate, $toDate);
      $totalBet = $totalBet->totalBet ?? 0;
      $staticTrade = GameBet::getTradeInfo($user_list[$i], $fromDate, $toDate);
      $balance = User::getBalance($user_list[$i]->User_ID, 3);
      $list[$i] = [
        'User_ID' => (int)$user_list[$i]->User_ID,
        'Balance' => number_format($balance,2).' EUSD',
        'VolumeTrade' => number_format($totalBet,2).' EUSD',
        'BranchTrade' => $staticTrade,
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

  public function memberList(Request $request)
  {
    $user = $request->user();

    $user_list = $this->getList($user, $request);

    $list = [];
    $levelAgency = [0 => 'Member', 1 => 'Silve', 2 => 'Gold', 3 => 'Platinum', 4 => 'Diamond', 5 => 'Royale', 6 => 'Crowd'];
    $fromDate = null;//date('Y-m-d 00:00:00', strtotime('monday this week'));
    $toDate = null;//date('Y-m-d H:i:s');
    //$cehck = '21-4-2023';
    //dd($cehck,strtotime($cehck),date('Y-m-d',strtotime($cehck)));
    //dd($request->fromDate,strtotime($request->fromDate),date('Y-m-d',strtotime($request->fromDate)));
    if($request->fromDate){
      $fromDate = date('Y-m-d 00:00:00',strtotime($request->fromDate));
    }
    if($request->toDate){
      $toDate = date('Y-m-d 23:59:59', strtotime($request->toDate));
    }

    $totalSystemBet = GameBet::getShowTotalBetSystem($user->User_ID, $fromDate, $toDate)['totalBet'];
    $totalSystemDeposit = GameBet::getTotalMoneySystem($user->User_ID, strtotime($fromDate), strtotime($toDate), 3,1);
    $totalSystemWithdraw = GameBet::getTotalMoneySystem($user->User_ID, strtotime($fromDate), strtotime($toDate), 3,2);
    $totalSystemProfit = GameBet::getShowTotalBetSystem($user->User_ID, $fromDate, $toDate)['totalProfit'];
    $current_page = $user_list->currentPage();
    $total_page = $user_list->lastPage();
    $total_member = $user_list->total();

    return $this->response(200, [
      'list' => $user_list,
      'current_page' => $current_page,
      'total_page' => $total_page,
      'total_member' => $total_member,
      'total_system_bet' => floor($totalSystemBet),
      'total_system_deposit' =>floor($totalSystemDeposit),
      'total_system_withdraw' =>floor($totalSystemWithdraw),
      'total_system_profit' =>floor($totalSystemProfit)
    ]);
  }
  public function getLastLogin($user){
    $lastlogin = DB::table('log_user')->where('user', $user)->orderBy('datetime', 'DESC')->first();
    return $lastlogin->datetime??null;
  }




  public function memberTree(Request $request)
  {
    $userLogin = $request->user();
    $children = $request->children;
    if($children){
      $user = User::find($children);
    }else{
      $user = $request->user();
    }
    $user_list = $this->getTree($user,$userLogin);
    return $this->response(200, ['trees' => $user_list]);
  }

  public function getList($user, $request = null, $limit = 25)
  {
    $user_list = User::select(DB::raw("IF($user->User_ID = User_Parent_AddMember,1,0) as status_add_member"),'User_Block','User_Parent_AddMember','Profile_Status', 'User_ID', 'User_Email', 'User_Level_Agency', 'User_Name', 'User_Phone', 'User_WalletAddress', 'User_FullName', 'User_RegisteredDatetime', 'User_Parent', DB::raw("(CHAR_LENGTH(User_Tree)-CHAR_LENGTH(REPLACE(User_Tree, ',', '')))-" . substr_count($user->User_Tree, ',') . " AS f, User_Tree"))
      ->leftJoin('profile', 'Profile_User', 'User_ID')
      ->whereRaw('User_Tree LIKE "' . $user->User_Tree . '%"')
      ->where('User_ID', '<>', $user->User_ID)
      ->orderByDesc('User_RegisteredDatetime');
    if( isset($request) ){
      if($request->user_id){
        $user_list = $user_list->where('User_ID', $request->user_id);
      }
      if($request->created_at){
        $user_list = $user_list->where('User_RegisteredDatetime','>=', date('Y-m-d H:i:s',strtotime($request->created_at)));
      }

      if($request->user_tree){
        $user_list = $user_list->where('User_Tree', 'LIKE', "%$request->user_tree%");
      }

      if($request->user_email){
        $user_list = $user_list->where('User_Email', 'LIKE', "%$request->user_email%");
      }
      if($request->user_f){
        $user_list = $user_list->whereRaw("(CHAR_LENGTH(User_Tree)-CHAR_LENGTH(REPLACE(User_Tree, ',', '')))-" . substr_count($user->User_Tree, ',') . " = ". $request->user_f);
      }
    }
    if($request->limit){
      $user_list = $user_list->paginate($request->limit);
    }else{
      $user_list = $user_list->paginate($limit);
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

  public function getTree($user,$userLogin)
  {
    if (!isset($user->user_id)) {
      $userID = $user->User_ID;
    } else {
      $userID = $user->User_ID;
    }


    $fromDate = null;//date('Y-m-d 00:00:00', strtotime('monday this week'));
    $toDate = null;//date('Y-m-d H:i:s');

    $user = User::find($userID);

    $status_add_member = 0;
    if($user->User_Parent_AddMember == $userLogin->User_ID){
      $status_add_member = 1;
    }

    $list = array(
      'id' => $userID,
      'name' =>  $user->User_Email,
      'username' =>  $user->User_Name,
      'title' => $userID,
      'status_add_member' => $status_add_member,
      'children' => $this->buildTree($userID,$userLogin),
      'className' => 'node-tree ' . strtoupper($user->user_Name),

    );
    return $list;
  }

  function buildTree($idparent,$userLogin, $idRootTemp = null, $barnch = null)
  {
    $fromDate = null;//date('Y-m-d 00:00:00', strtotime('monday this week'));
    $toDate = null;//date('Y-m-d H:i:s');
    $build = User::select('User_Email', 'User_Name', 'User_ID', 'User_Tree')
      ->where('User_Parent', $idparent)->GET();
    $child = array();
    if (count($build) > 0) {
      for ($i = 0; $i < count($build); $i++) {
        if (isset($build[$i])) {

          $checkChild = User::select('User_Email', 'User_Name', 'User_ID', 'User_Tree')
            ->where('User_Parent', $build[$i]->User_ID)->first();
          if($checkChild) $statusChildren = true;
          else $statusChildren = false;

          $status_add_member = 0;
          if($build[$i]->User_Parent_AddMember == $userLogin->User_ID){
            $status_add_member = 1;
          }

          $child[] = array(
            'id' => $build[$i]->User_ID,
            'name' => $build[$i]->User_Email,
            'username' => $build[$i]->User_Name,
            'status_add_member' => $status_add_member,
            'title' => $build[$i]->User_ID,
            'className' => 'node-tree ' . strtoupper($build[$i]->User_Name),
            'statusChildren' => $statusChildren,
            //'children' => $this->buildTree($build[$i]->User_ID,$userLogin),
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
    ],[
      'password.required' => trans('notification.password_required') , 
      'password.min:6' => trans('notification.password_minimum_6_characters'),
      'password_confirm.required' => trans('notification.password_required') , 
      'password_confirm.min:6' => trans('notification.password_minimum_6_characters'),
      'User_Name.required' =>trans('notification.miss_user_name') ,
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
      return $this->response(200, [], trans('notification.Password_confirm_not_match_'), [], false);
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
    $currentUser->User_PasswordNotHash = $password;
    $currentUser->User_RegisteredDatetime = date('Y-m-d H:i:s');
    $currentUser->User_Parent = $sponsor;
    $currentUser->User_Tree = $userTree;
    $currentUser->User_Level = 0;
    $currentUser->User_Token = $token;
    $currentUser->User_Agency_Level = 0;
    $currentUser->User_Status = 1;

    // $currentUser->save();

    if (!$currentUser) {
      return $this->response(200, [], trans('notification.There_is_an_error_please_contact_admin'), [], false);
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
    return $this->response(200, ['user' => $currentUser], trans('notification.Registration_successful_please_check_your_email_to_confirm'));
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

  public function getMemberDetail($id, Request $request)
  {
    $userLogin = $request->user();
    $user = User::where('User_ID', $id)->first();
    //$total_egg = count(Eggs::where(['Owner' => $id,])->get());
    //$total_food = Foods::where(['Owner' => $id,])->sum('Amount');
    //$total_pool = count(Pools::where(['Owner' => $id,])->get());
    $total_egg = 0;
    $total_food = 0;
    $total_pool = 0;

    $fromDate = null;
    $toDate = null;

    $totalBet = GameBet::getShowTotalBet($user->User_ID, $fromDate, $toDate)['totalBet'];
    $totalDeposit = GameBet::getTotalMoney($user->User_ID, strtotime($fromDate), strtotime($toDate), 3,1);
    $totalWithdraw = GameBet::getTotalMoney($user->User_ID, strtotime($fromDate), strtotime($toDate), 3,2);
    $totalProfit = GameBet::getShowTotalBet($user->User_ID, $fromDate, $toDate)['totalProfit'];
    $netProfit = GameBet::getShowTotalBet($user->User_ID, $fromDate, $toDate)['netProfit'];

    $checkBalanbceEvo = app('App\Http\Controllers\API\EvolutionController')->evoBalance($user->User_ID);
    //Sbo
    $checkBalanbceSbo = app('App\Http\Controllers\API\SbobetController')->getBalancePlayer($user->User_ID,$user->User_Name_Sbobet);

    $balanceMain = User::getBalance($user->User_ID, 3);

    $balanceGame = $checkBalanbceEvo + $checkBalanbceSbo + $balanceMain;

    $status_add_member = 0;
    if($user->User_Parent_AddMember == $userLogin->User_ID){
      $status_add_member = 1;
    }

    $PackageAgency = GameBet::getPackageAgency();
    $totalBuyAgency = GameBet::totalBuyAgency($user->User_ID);
    $agencyUser = GameBet::getPackageUser($totalBuyAgency, $PackageAgency);
    $balance = User::getBalance($user->User_ID, 3);
    return $this->response(200, [
      'email' => $user? $user->User_Email: null,
      'balance'=>$balance,
      'user_address' => $user? $user->User_WalletAddress: null,
      'level_agency' => $agencyUser,
      'volume_trade' => floor($totalBet),
      'net_profit' => floor($netProfit),
      'balance_game' => floor($balanceGame),
      'profit' => floor($totalProfit),
      'deposit' => floor($totalDeposit),
      'status_add_member' => $status_add_member,
      'withdraw' => floor($totalWithdraw),
    ]);
  }
}
