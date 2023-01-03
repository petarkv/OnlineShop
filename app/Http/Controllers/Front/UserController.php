<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Cart;
use App\Country;
use Session;
use Auth;

class UserController extends Controller
{
    public function loginRegister(){
        Session::forget('error_message');
        Session::forget('success_message');
        return \view('front.users.login_register');
    }

    public function registerUser(Request $request){
        if ($request->isMethod('post')) {
            Session::forget('error_message');
            Session::forget('success_message');
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            // Check if User already exists
            $userCount = User::where('email',$data['email'])->count();
            if ($userCount>0) {
                $message = "Email already exists!";
                Session::flash('error_message',$message);
                return \redirect()->back();
            }else {
                // Register User
                $user = new User;
                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->mobile = $data['mobile'];
                $user->password = \bcrypt($data['password']);
                $user->status = 0;
                $user->save();

                // Send Confirmation Email
                $email = $data['email'];
                $messageData = [
                    'email' => $data['email'],
                    'name' => $data['name'],
                    'code' => \base64_encode($data['email'])
                ];
                Mail::send('emails.confirmation',$messageData,function($message) use($email){
                    $message->to($email)->subject('Confirm your OnlineShop Account');
                });

                // Redirect Back with success message
                $message = "Please confirm your email to activate your account";
                Session::put('success_message',$message);
                return \redirect()->back();

                // if (Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])) {

                //     // Update User Cart with user id
                //     if (!empty(Session::get('session_id'))) {
                //         $user_id = Auth::user()->id;
                //         $session_id = Session::get('session_id');
                //         Cart::where('session_id',$session_id)->update(['user_id'=>$user_id]);
                //     }

                //     // Send Register Email
                //     $email = $data['email'];
                //     $messageData = ['name'=>$data['name'],'mobile'=>$data['mobile'],'email'=>$data['email']];
                //     Mail::send('emails.register',$messageData,function($message) use($email){
                //         $message->to($email)->subject('PecaPunker OnlineShop');
                //     });


                //     // echo "<pre>"; print_r(Auth::user()); die;
                //     return \redirect('/cart');
                // }
            }
        }
    }

    public function confirmAccount($email){
        Session::forget('error_message');
        Session::forget('success_message');
        // Decode User Email
        $email = base64_decode($email);
        // Check if User email exists
        $userCount = User::where('email',$email)->count();
        if ($userCount>0) {
           // Check if User email is activated or not
           $userDetails = User::where('email',$email)->first();
           if ($userDetails->status == 1) {
               $message = "Your Email account is already activated. Please login.";
               Session::put('error_message',$message);
               return \redirect('/login-register');
           }else {
               // Update User Status to 1 to activate account
               User::where('email',$email)->update(['status'=>1]);

                // Send Register Email
                $messageData = ['name'=>$userDetails['name'],'mobile'=>$userDetails['mobile'],'email'=>$email];
                Mail::send('emails.register',$messageData,function($message) use($email){
                    $message->to($email)->subject('PecaPunker OnlineShop');
                });

                // Redirect to Login/Register page with success message
                $message = "Your Email account is activated. You can login now.";
                Session::put('success_message',$message);
                return \redirect('/login-register');
           }
        }else{
            abort(404);
        }
    }

    public function checkEmail(Request $request){
        // Check if email already exists
        $data = $request->all();
        $emailCount = User::where('email',$data['email'])->count();
        if ($emailCount>0) {
            return "false";
        }else {
            return "true";
        }
    }

    public function loginUser(Request $request){
        if ($request->isMethod('post')) {
            Session::forget('error_message');
            Session::forget('success_message');
            $data = $request->all();
            // echo "<pre>"; \print_r($data); die;
            if (Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])) {
                // Check Email is activated or not
                $userStatus = User::where('email',$data['email'])->first();
                if ($userStatus->status == 0) {
                    Auth::logout();
                    $message = "Your account is not activated yet. Please confirm your email to activate.";
                    Session::put('error_message',$message);
                    return \redirect()->back();
                }

                // Update User Cart with user id
                if (!empty(Session::get('session_id'))) {
                    $user_id = Auth::user()->id;
                    $session_id = Session::get('session_id');
                    Cart::where('session_id',$session_id)->update(['user_id'=>$user_id]);
                }

                return \redirect('/cart');
            }else {
                $message = "Invalid e-mail or Password";
                Session::flash('error_message',$message);
                return \redirect()->back();
            }
        }
    }

    public function logoutUser(){
        Auth::logout();
        return \redirect('/');
    }

    public function forgotPassword(Request $request){        
        if ($request->isMethod('post')) {
            Session::forget('success_message');
            Session::forget('error_message');           
            $data = $request->all();
            $emailCount = User::where('email',$data['email'])->count();
            if ($emailCount == 0) {
                $message = "Email does not exists!";
                Session::put('error_message',$message);
                Session::forget('success_message');
                return \redirect()->back();
            }
            // Generate Random Password
            $random_password = Str::random(8);

            // Encode/Secure Password
            $new_password = \bcrypt($random_password);

            // Update Password
            User::where('email',$data['email'])->update(['password'=>$new_password]);

            // Get User's Name
            $userName = User::select('name')->where('email',$data['email'])->first();

            // Send Password
            $email = $data['email'];
            $name = $userName->name;
            $messageData = [
                'email'=>$email,
                'name'=>$name,
                'password'=>$random_password
            ];
            Mail::send('emails.forgot_password',$messageData,function($message) use($email){
                $message->to($email)->subject('New Password - OnlineShop');
            });

            // Redirect to Login/Register Page with success message
            $message = "Please check your email for New Password!";
            Session::put('success_message',$message);
            Session::forget('error_message');
            return \redirect('/login-register');
        }
        return \view('front.users.forgot_password');
    }

    public function account(Request $request){        
        $user_id = Auth::user()->id;
        $userDetails = User::find($user_id)->toArray();
        // $userDetails = json_decode(json_encode($userDetails),true);
        // dd($userDetails); die;

        $countries = Country::where('status',1)->get()->toArray();
        // dd($countries); die;
        
        if ($request->isMethod('post')) {
            $data = $request->all();

            Session::forget('success_message');
            Session::forget('error_message');
            
            $rules = [
                'name' => 'required|regex:/^[\pL\s\-]+$/u',
                // 'admin_mobile' => 'required|numeric',
            ];
            $customMessages = [
                'name.required' => 'Name is required',
                'name.regex:/^[\pL\s\-]+$/u' => 'Name is only alphabetical',
                // 'admin_mobile.required' => 'Mobile is required',
                // 'admin_mobile.numeric' => 'Mobile is only numeric',
            ];
            $this->validate($request,$rules,$customMessages);

            $user = User::find($user_id);
            $user->name = $data['name'];
            $user->address = $data['address'];
            $user->city = $data['city'];
            $user->country = $data['country'];
            $user->postal_code = $data['postal_code'];
            $user->mobile = $data['mobile'];
            $user->save();
            $message = "Your account details have been updated successfully";
            Session::put('success_message',$message);
            // Session::forget('error_message');
            return \redirect()->back();
        }
        return \view('front.users.account')->with(\compact('userDetails','countries'));
    }

    public function checkUserPassword(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $user_id = Auth::user()->id;
            $checkPassword = User::select('password')->where('id',$user_id)->first();
            if (Hash::check($data['current_password'],$checkPassword->password)) {
                return "true";
            }else{
                return "false";
            }
        }
    }

    public function updateUserPassword(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $user_id = Auth::user()->id;
            $checkPassword = User::select('password')->where('id',$user_id)->first();
            if (Hash::check($data['current_password'],$checkPassword->password)) {
                // Update Current Password
                $new_password = \bcrypt($data['new_password']);
                User::where('id',$user_id)->update(['password'=>$new_password]);
                $message = "Password updated successfully";
                Session::put('success_message',$message);
                Session::forget('error_message');
                return \redirect()->back();
            }else{
                $message = "Current Password is Incorrect";
                Session::put('error_message',$message);
                Session::forget('success_message');
                return \redirect()->back();
            }
        }
    }
}
