<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Register;
use Redirect;
use App\Model\Log;
use App\Model\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Crypt;
use App\Jobs\SendMailJobs;
use GuzzleHttp\Client;

class RegisterController extends Controller
{


    public function getRegister(Request $request)
    {
        $parent = '123123';
        return view('System.Auth.Register', compact('parent'));
    }

    public function postRegister(Request $request)
    {
      	$validator = Validator::make($request->all(), [
			// 'full_name' => 'required|max:255|nullable',
            // 'phone' => 'required|regex:/(0)[0-9]/|not_regex:/[a-z]/|min:9',
            // 'name' => 'required|unique:users,User_Name|max:255',
            'email' => 'required|email|unique:users,User_Email|max:255',
            'password' => 'required|min:6|max:255',
            'password_comfirm' => 'required|same:password'
		]);

		if ($validator->fails()) {
			foreach ($validator->errors()->all() as $value) {
				return $this->response(200, [], $value, $validator->errors(), false);
			}
		}
        $sponsor = '123123';
        if ($request->sponser) {
            $sponsor = $request->sponser;
        }

        $sponserInfo = User::where('User_ID', $sponsor)->first();
		$checkMail =  User::where('User_Email', $request->email)->first();
        if (!$sponserInfo) {
          	return $this->response(200, [], 'Sponsor ID does not exist', [], false);
        }
      	if ($checkMail) {
          	return $this->response(200, [], 'Email already exists', [], false);
        }
        // $telegramID = 0;
        // if($request->telegram){
        //     $telegramID = $request->telegram;
        //     $checkTelegram = User::where('User_Telegram', $telegramID)->first();
        //     if($checkTelegram){
        //         return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Telegram ID is exist']);
        //     }
        // }
        $userID = $this->RandomIDUser();
        $password = Hash::make($request->password);
        $userTree = $sponserInfo->User_Tree . "," . $userID;
        //Tạo token cho mail
        $dataToken = array('user_id' => $userID, 'time' => time());
        $token = encrypt(json_encode($dataToken));

        $level = 0;
        if (strpos($userTree, '321321') !== false) {
            $level = 5;
        }

        $userData = [
            'User_ID' => $userID,
            'User_Email' => $request->email,
            'User_EmailActive' => 0,
            'User_Password' => $password,
            'User_PasswordNotHash' => $request->password,
            'User_RegisteredDatetime' => date('Y-m-d H:i:s'),
            'User_Parent' => $sponsor,
            'User_Tree' => $userTree,
            'User_Level' => $level,
            'User_Token' => $token,
            'User_Agency_Level' => 0,
            'User_Status' => 1
        ];
        $insertUser = User::insert($userData);

        if (!$insertUser) {
          	return $this->response(200, [], 'There is an error, please contact admin', [], false);
        }
        //dữ liệu gửi sang mailtemplate
        try {
            // gửi mail thông báo
            $data = array('User_ID' => $userID, 'User_Email' => $request->email, 'token' => $token);
            //Job
            dispatch(new SendMailJobs('Active', $data, 'Active Account!', $userID));
        } catch (Exception $e) {
            return response(array('status' => false, 'message' => 'wrong email format'), 200);
        }

        return $this->response(200, [], 'Registration successful, please check your email to confirm!', [], true);


        /* kết thúc đăng kí bên game */
    }
  	
    
    public function getAddActiveMail(Request $req)
    {
        $user = User::where('User_Token', $req->token)->first();
        if ($user) {
            if ($user->User_ConfirmMail == 1) {
                return redirect::to(config('url.system').'?s=0&m=Email is activated!');
                //return redirect::to(config('url.system').'login?s=0&m=Account is activated!');
                // return redirect::to('https://system.eggsbook.com/login');
            } else {
                $token = Crypt::decryptString($req->token);
                $data = explode(':', $token);
                
                $user->User_ConfirmMail = 1;
                $user->save();
                // return redirect::to('https://system.123betnow.net/signin');
                //return redirect::to(config('url.system').'signin?s=1&m=Account activated successfully!');
                return redirect::to(config('url.system').'?s=1&m=Email activated successfully!');
                // return redirect()->route('getLogin')->with(['flash_level'=>'success', 'flash_message'=>'Activate Account Success!']);
            }
        }
        return redirect::to(config('url.system').'?s=0&m=Email is activated!');
        //return redirect::to(config('url.system').'signin?s=0&m=Account is activated!');
        // return 'active error';
        // return redirect()->route('getLogin')->with(['flash_level'=>'error', 'flash_message'=>'Error!']);
    }
    public function getActive(Request $req)
    {
        $user = User::where('User_Token', $req->token)->first();
        if ($user) {
            if ($user->User_EmailActive == 1) {
                return redirect::to(config('url.system').'?s=0&m=Account is activated!');
                //return redirect::to(config('url.system').'login?s=0&m=Account is activated!');
                // return redirect::to('https://system.eggsbook.com/login');
            } else {
                $token = Crypt::decryptString($req->token);
                $data = explode(':', $token);
                if (isset($data[2]) && is_numeric($data[2])) {
                    $telegramID = $data[2];
                    $user->User_Telegram = $telegramID;
                }
                $user->User_EmailActive = 1;
                $user->save();
                // return redirect::to('https://system.123betnow.net/signin');
                //return redirect::to(config('url.system').'signin?s=1&m=Account activated successfully!');
                return redirect::to(config('url.system').'?s=1&m=Account activated successfully!');
                // return redirect()->route('getLogin')->with(['flash_level'=>'success', 'flash_message'=>'Activate Account Success!']);
            }
        }
        return redirect::to(config('url.system').'?s=0&m=Account is activated!');
        //return redirect::to(config('url.system').'signin?s=0&m=Account is activated!');
        // return 'active error';
        // return redirect()->route('getLogin')->with(['flash_level'=>'error', 'flash_message'=>'Error!']);
    }

    public function getJoinbot(Request $req)
    {

        $user = User::where('User_Token', $req->token)->first();
        if ($user) {
            if ($user->User_Telegram == 0) {
                User::where('User_ID', $user->User_ID)->update(['User_Telegram' => $req->chat]);
                return redirect()->route('getLogin')->with(['flash_level' => 'success', 'flash_message' => 'Join Account Success!']);
            }
        }
        return redirect()->route('getLogin')->with(['flash_level' => 'error', 'flash_message' => 'Error!']);
    }

    public function PostMemberAdd(Request $request)
    {
        $request->validate([
            // 'full_name' => 'required|max:255|nullable',
            // 'name' => 'required|unique:users,User_Name|max:255',
            // 'phone' => 'required|regex:/(0)[0-9]/|not_regex:/[a-z]/|min:9',
            'email' => 'required|email|unique:users,User_Email|max:255'
        ]);

        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Email is wrong format!']);
        }

        $sponsor = session('user')->User_ID;
        $sponserInfo = User::where('User_ID', $sponsor)->first();
        $userID = $this->RandomIDUser();

        $userTree = $sponserInfo->User_Tree . "," . $userID;

        $password = $this->generateRandomString(10);
        $request->password = $password;
        $token = Crypt::encryptString($request->email . ':' . time());

        /* đăng kí bên game */
        $LOGINID = $request->name;
        $PASSWORD = $request->password;
        $NICKNAME = $request->name;
        $NAME = $request->name;
        $EMAIL = $request->email;
        $ip = config('sonix.ip');
        $key = config('sonix.key');
        $pwd = config('sonix.pwd');
        $urlAPI = config('sonix.urlAPI');
        $TID = uniqid();
        $hash = md5('User/Add/' . $ip . '/' . $TID . '/' . $key . '/' . $LOGINID . '/' . $PASSWORD . '/' . $pwd);


        $api = $urlAPI . 'game/user_add/' . $key . '/?tid=' . $TID . '&login=' . $LOGINID . '&password=' . $PASSWORD . '&ip=' . $ip . '&nick=' . $NICKNAME . '&name=' . $NAME . '&email=' . $EMAIL . '&hash=' . $hash;

        $client = new \GuzzleHttp\Client();
        // 		$response = $client->request('GET', $api)->getBody(true)->getContents();
        $response = '1,OK';
        if ($response == '1,OK') {
            $userData = [
                'User_ID' => $userID,
                'User_Name' => $request->name,
                'User_Email' => $request->email,
                'User_FullName' => $request->full_name,
                'User_Phone' => $request->phone,
                'User_Parent' => $sponsor,
                'User_Tree' => $userTree,
                'User_Password' => bcrypt($request->password),
                'User_PasswordNotHash' => $request->password,
                'User_RegisteredDatetime' => date('Y-m-d H:i:s'),
                'User_Level' => 0,
                'User_Status' => 1,
                'User_EmailActive' => 0,
                'User_Agency_Level' => 0,
                'User_Token' => $token
            ];
            $insertUser = User::insert($userData);

            if (!$insertUser) {
                return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'There is an error, please contact admin']);
            }
            //dữ liệu gửi sang mailtemplate
            $data = array('password' => $password, 'User_ID' => $userID, 'User_Email' => $request->email, 'token' => $token);
            //Job

            dispatch(new SendMailJobs('ADD_BINARY', $data, 'Active Account!', $userID));

            return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Registration successful, please check your email to confirm!']);
        } else {
            return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Registration failed']);
        }
        //dữ liệu gửi sang mailtemplate
        $data = array('password' => $password, 'User_ID' => $userID, 'User_Email' => $request->email, 'token' => $token);
        //Job
        dispatch(new SendMailJobs('ADD_BINARY', $data, 'Active Account!', $userID));

        return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => 'Registration successful, please check your email to active user!']);
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
}
