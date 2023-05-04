<?php

namespace App\Http\Controllers;

use App\Models\ForgetPasswordToken;
use App\Mail\ForgetPasswordMail;
use Illuminate\Http\Request;
use App\User;
use Mail;

class ForgetPasswordController extends Controller
{
    public function requestEmail(Request $request)
    {
        $usedEmail = User::where('email', $request->email)->first();
        if ($usedEmail == null) {
            return redirect()->back()->with('ERR', 'Alamat e-mail tidak terdaftar.');
        }
        
        $existing_token = ForgetPasswordToken::where('user_id', $usedEmail->id);
        if ($existing_token != null) {
            $existing_token->delete();
        }

        $reset_password_token = ForgetPasswordToken::create([
            'user_id' => $usedEmail->id,
            'token' => bcrypt($usedEmail->id.$usedEmail->email)
        ]);

        $data['user'] = $usedEmail;
        $data['token'] = $reset_password_token->token;
        $mail = Mail::to($usedEmail->email)->send(new ForgetPasswordMail($data));

        return redirect()->back()->with('OK', 'Kami telah mengirimkan tautan di alamat email Anda untuk mengatur ulang kata sandi Anda');
    }
    
    public function redirect()
    {
        $reset_password_token = ForgetPasswordToken::where('user_id', $_GET['id'])->first();
    	if(isset($_GET['token']) != null && $reset_password_token != null)
        {
        	if ($reset_password_token->token == $_GET['token']) {
            	return redirect()->route('forget-password.index', $reset_password_token->user_id)->with('TOKEN', 'TRUE');
        	}
        }
        return redirect()->route('login')->with('ERR', 'Token tidak cocok.');
    }
    
    public function index($id)
    {
        if (!session('TOKEN')) {
            return redirect()->route('login')->with('ERR', 'Token tidak cocok');
        }
        $data['user'] = User::find($id);
    	$data['token'] = ForgetPasswordToken::where('user_id', $id)->pluck('token')->first();
        return view('auth.forget_password', $data);
    }
    
    public function update($id)
    {
        $user = User::find($id);
        $user->update([
            'password' => bcrypt(request('password')),
        ]);

        $reset_password_token = ForgetPasswordToken::where('user_id', $id)->first();
        $reset_password_token->delete();

        return redirect()->route('login')->with('OK', 'Password berhasil diperbarui.');
    }
}
