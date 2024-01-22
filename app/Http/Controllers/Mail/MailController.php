<?php

namespace App\Http\Controllers\Mail;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\User;

class MailController extends Controller
{
    //
    public function confirmEmailSignUp(Request $request)
    {
        User::where('User_Token', $request->user_token)->update([
            'User_EmailActive' => 1,
        ]);

        echo 'active finish';
    }

    public function userForgotPassword(Request $request)
    {
        User::where('User_Token', $request->user_token)->update([
            'User_Password' => $request->user_password,
        ]);

        echo 'active finish';
    }
}
