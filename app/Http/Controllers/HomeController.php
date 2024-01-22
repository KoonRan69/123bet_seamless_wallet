<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public $config;
    public $domain = 'https://ex-api-yy.xxttgg.com/';
    public $compakyKey = '2F4B29F27A4E497AB8FC779944E54A01';
    public $agent = 'Pi123_Sbobet_VND'; // AgencySaba123Betnow , Betnow_Sbobet_123, R2035_Sbobet_CNY

    //public $is_maintain = 0;

    public function __construct()
    {
        //$this->middleware('auth:api');
        $this->config = config('urlSBOBET.sbobet');
        $this->domain = 'https://ex-api-yy.xxttgg.com/';
        $this->compakyKey = '2F4B29F27A4E497AB8FC779944E54A01';
        $this->agent = 'Pi123_Sbobet_VND'; // AgencySaba123Betnow , Betnow_Sbobet_123, R2035_Sbobet_CNY
        $this->config = config('urlSBOBET.sbobet');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function loginSbobet(Request $req)
    {
        if ($req->test == 1) {
            $this->postUpdatePlayerGroup($req);
        }
        if ($req->create == 1) {
            $this->CreatMember($req);
        }
        if ($req->deposit == 1) {
            $this->depositSbobet($req);
        }
        if($req->creat_agent == 1){
            $this->CreateMemberAgent($req);
        }
        return view('System.Basic.Index');
    }

    public function getBalance(Request $req){
        $data = ['AccountName' => 'now_259683', "Balance" => 5000, "ErrorCode" => 0, "ErrorMessage" => "No Error"];
        return response()->json($data);
    }

    public function depositSbobet(Request $req)
    {
        $url = $this->config['url'].'/web-root/restricted/player/deposit.aspx';
        $User_Name_Sbobet = $req->user;
        $userBalance = $req->amount;
        $txCode = Str::random(29);
        $body = [
            "Username" => $User_Name_Sbobet,
            "Amount" => $userBalance,
            "CompanyKey" => $this->config['CompanyKey'],
            "ServerId" => $this->config['ServerId'],
            "TxnId" => "$txCode",
        ];
        $topup_str = json_encode($body);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        ));

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $topup_str);
        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        $check = json_decode($result);
        dd($check, $result, 123);
    }

    public function CreatMember(Request $req)
    {
        $url = $this->config['url'] . '/web-root/restricted/player/register-player.aspx';

        $body = [
            "Username" => $req->user,
            "Agent" => $this->agent,
            "CompanyKey" => $this->config['CompanyKey'],
            "ServerId" => "YY-Production",
        ];
        $topup_str = json_encode($body);
        #Curl init
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            //'X-Access-Token: 5e3fcc78ef404a85ab3dd961ecfeed1f',
            // 'Content-Length: '.strlen($topup_str),
        ));

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $topup_str);
        $result = curl_exec($ch);
        $err = curl_error($ch);

        curl_close($ch);

        $check = json_decode($result);
        dd($check, $result, $body, $topup_str);
        if ($err) {
            return $this->response(200, $err, 'register_failed', [], false);
        }
        if ($check->error->id != 0) {
            return $this->response(200, $check->error->msg, 'register_failed', [], false);
        }
    }

    public function postUpdatePlayerGroup($request)
    {

        $url = $this->config['url'] . '/web-root/restricted/player/update-player-usergroup.aspx';

        $body = [
            "Username" => "now_123Betnow_314678",
            "CompanyKey" => $this->config['CompanyKey'],
            "ServerId" => $this->config['ServerId'],
            "PlayerUserGroup" => "a",
        ];//$request->Portfolio
        $topup_str = json_encode($body);
        #Curl init
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            //'X-Access-Token: 5e3fcc78ef404a85ab3dd961ecfeed1f',
            // 'Content-Length: '.strlen($topup_str),
        ));

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $topup_str);
        $result = curl_exec($ch);
        $err = curl_error($ch);

        curl_close($ch);

        $check = json_decode($result);

        if ($err) {
            return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => "login failed!"]);
        }
        if ($check->error->id != 0) {
            return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => "login failed!"]);
        }
        dd($check);
        $url = $check->url;
        return view('System.Basic.Iframe', compact('url'));
        return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "login success!"]);
    }

    public function postLoginEvolution($request)
    {

        $usernameArray = explode("_", $request->username);

        $user = User::where('User_ID', $usernameArray[1])->first();
        if (!$user) {
            return ['status' => false, 'message' => "User is not exist", 'data' => null];
        }

        if ($user->User_Evo == 0) {
            return ['status' => false, 'message' => "Not registered Evolution!", 'data' => null];
        }

        $username = $request->username;
        dd($username);
        $body = '{
                  "uuid": "' . md5($username) . '",
                  "player": {
                    "id": "' . $username . '",
                    "update": true,
                    "firstName": "' . $username . '",
                    "lastName": "' . $username . '",
                    "nickname": "' . $username . '",
                    "country": "VN",
                    "language": "en",
                    "currency": "CNY",
                    "session": {
                      "id": "' . md5($username) . '",
                      "ip": "89.45.67.50"
                    },
                    "group": {
                      "id": "qe6glrwau24joiu3",
                      "action": "assign"
                    }
                  },
                  "config": {
                    "brand": {
                      "id": "1",
                      "skin": "1"
                    },

                    "channel": {
                      "wrapped": false,
                      "mobile": true
                    },
                    "urls": {
                      "cashier": "https://789api.net/",
                      "responsibleGaming": "https://789api.net/",
                      "lobby": "https://789api.net/evodemo.php",
                      "sessionTimeout": "https://789api.net/"
                    },
                    "freeGames": true
                  }
                }';


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.luckylivegames.com/ua/v1/1gvsw90kwuok5zqs/15a59174850db01115f28c0bd1705230");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        $result = curl_exec($ch);
        $data = json_decode($result);
        dd($data);
        $url = $data->entry;
        //$url = [] ;
        //$url['entry'] = $data->entry ;
        //$url['entryEmbedded'] =  $data->entryEmbedded;
        return ['status' => true, 'message' => "Login success", 'data' => $url];

    }

    public function postLoginSbobet(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            //'Portfolio' => 'required',
            'password' => 'required|',
        ]);

        if ($request->typegame == 'Evolution') {

            //if($request->username != 'now_350205') return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => 'Coming soon']);

            $data = $this->postLoginEvolution($request);

            if ($data['status'] == false) {
                return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => $data['message']]);
            } else {
                $url = $data['data'];
                return view('System.Basic.Iframe', compact('url'));
            }
        }

        $url = $this->config['url'] . '/web-root/restricted/player/login.aspx';
        $urlBetnow = "https://123betnow.net/";

//    $user = User::where('User_Name_Sbobet', $request->username)->first();
//    if (!$user) {
//      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => "User is not exist"]);
//    }
//
//    $User_Name_Sbobet = $user->User_Name_Sbobet;
//
//    if(!$User_Name_Sbobet){
//      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => "Not registered sbobet!"]);
//    }
//
//    if($user->User_Sbobet_Password != $request->password){
//      return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => "Incorrect password"]);
//    }

        $body = [
            "Username" => $request->username,
            "CompanyKey" => $this->config['CompanyKey'],
            "ServerId" => "YY-Production",
            "Portfolio" => $request->typegame,
        ];//$request->Portfolio
        $topup_str = json_encode($body);
        #Curl init
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            //'X-Access-Token: 5e3fcc78ef404a85ab3dd961ecfeed1f',
            // 'Content-Length: '.strlen($topup_str),
        ));

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $topup_str);
        $result = curl_exec($ch);
        $err = curl_error($ch);

        curl_close($ch);

        $check = json_decode($result);
//        dd($check, $result, $body, $topup_str);
        if ($err) {
            return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => "login failed!"]);
        }
        if ($check->error->id != 0) {
            return redirect()->back()->with(['flash_level' => 'error', 'flash_message' => "login failed!"]);
        }
        echo 'https:' . $check->url;
        dd($check, $err, 3131, 'https:' . $check->url);
        exit;
        $url = $check->url;
        return view('System.Basic.Iframe', compact('url'));
        return redirect()->back()->with(['flash_level' => 'success', 'flash_message' => "login success!"]);
    }
    // đăng ký agent
    public function CreateMemberAgent(Request $request){
        dd(123);
        //$url = $this->config['url'].'/web-root/restricted/agent/register-agent.aspx';
        //$betLimit = '{
        //"SEXYBCRT":{
        //	"LIVE":{"limitId":[260312,260317]}
        //    }
        //}';

        //$body = "&CompanyKey=".$this->config['CompanyKey']."&currency=".$this->config['currency']."&Username=".$this->config['prefix'].$user->User_ID."&Agent=".$this->config['Agent']."ServerId=".$this->config['ServerId'];

        //chính
        $url = 'https://ex-api-yy.xxttgg.com/web-root/restricted/agent/register-agent.aspx';
        $body = [
            "Username" => "R2035_Sbobet_CNY",
            "Password"=> "R2035Sbobet",
            "Currency"=>"CNY",
            "Min"=>1,
            "Max"=> 1000,
            "MaxPerMatch"=> 2000,
            "CasinoTableLimit"=>1,
            "CompanyKey"=> "2F4B29F27A4E497AB8FC779944E54A01",
            "ServerId"=> "YY-ADMIN",
        ];

        //dd($body);

        //demo
        //$body = [
        //"Username" => "Betnow_Sbobet_123",
        //"Password"=> "Sbobet123456",
        //"Currency"=>"USD",
        //"Min"=>1,
        //"Max"=> 100000,
        //"MaxPerMatch"=> 200000,
        //"CasinoTableLimit"=>1,
        //"CompanyKey"=> "32AA14B122094C1C8B17B7B20DC8DA9B",
        //"ServerId"=> "YY-TEST",
        //];
        $topup_str   = json_encode($body);
        //dd($body);
        #Curl init
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        #FIXME: Hardcoded Access Token
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            //'X-Access-Token: 5e3fcc78ef404a85ab3dd961ecfeed1f',
            // 'Content-Length: '.strlen($topup_str),
        ));

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $topup_str);
        $result = curl_exec($ch);

        #Ensure to close curl
        curl_close($ch);


        //$response = curl_exec($curl);

        //$err = curl_error($curl);

        //curl_close($curl);
        $check= json_decode($result);
        dd($check);
        if ($err) {
            return $this->response(200, $err, trans('notification.register_failed'), [], false);
        }
        dd($check);
        if($check->status != 0000){
            return $this->response(200, $check->desc , trans('notification.register_failed'), [], false);
        }
        $user->User_Sbobet_Password = $request->password;
        $user->save();
        return $this->response(200, [], trans('notification.register_success'), [], true);
    }

}
