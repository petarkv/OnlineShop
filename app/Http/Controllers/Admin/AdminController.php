<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Admin;
use Session;
use Auth;
use Hash;
use Image;

class AdminController extends Controller
{
    public function dashboard(){
        $title = "Dashboard";
        Session::put('page','dashboard');
        return \view('admin.admin_dashboard')->with(\compact('title'));
    }

    public function settings(){
        Session::put('page','settings');
        $title = "Admin Settings";
        // Auth::guard('admin')->user()->id;
        $adminDetails = Admin::where('email',Auth::guard('admin')->user()->email)->first();
        return \view('admin.admin_settings')->with(\compact('adminDetails','title'));
    }

    public function login(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            $rules = [
                'email' => 'required|email|max:255',
                'password' => 'required',
            ];

            $customMessages = [
                'email.required' => 'Email is required',
                'email.email' => 'Valid email is required',
                'password.required' => 'Password is required',
            ];

            $this->validate($request,$rules,$customMessages);

            if (Auth::guard('admin')->attempt(['email'=>$data['email'],'password'=>$data['password']])) {
                return \redirect('admin/dashboard');
            }else{
                Session::flash('error_message','Invalin Email or Password');
                return \redirect()->back();
            }
        }
        return \view('admin.admin_login');
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return \redirect('/admin');
    }

    public function checkCurrentPassword(Request $request){
        $data = $request->all();
        // echo "<pre>"; print_r($data);
        // echo Auth::guard('admin')->user()->password; die;
        if (Hash::check($data['current_password'],Auth::guard('admin')->user()->password)) {
            echo "Same Pwds";
        }else {
            echo "Not Same Pwds";
        }
    }

    public function updateCurrentPassword(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            // Check if current password is correct
            if (Hash::check($data['current_password'],Auth::guard('admin')->user()->password)) {
                // Check if new and confirm password is matching
                if ($data['new_password']==$data['confirm_password']) {
                    Admin::where('id',Auth::guard('admin')->user()->id)->update(['password'=>\bcrypt($data['new_password'])]);
                    Session::flash('success_message','Password has been updated successfully');
                }else{
                    Session::flash('error_message','New Password and Confirm Password not match');  
                }
            }else {
                Session::flash('error_message','Your current Password is incorect');
            }
            return \redirect()->back();
        }
    }

    public function updateAdminDetails(Request $request){
        Session::put('page','update-admin-details');
        $title = "Update Admin Details";
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $rules = [
                'admin_name' => 'required|regex:/^[\pL\s\-]+$/u',
                'admin_mobile' => 'required|numeric',
                'admin_image' => 'image',
            ];
            $customMessages = [
                'admin_name.required' => 'Name is required',
                'admin_name.regex:/^[\pL\s\-]+$/u' => 'Name is only alphabetical',
                'admin_mobile.required' => 'Mobile is required',
                'admin_mobile.numeric' => 'Mobile is only numeric',
                'admin_image.image' => 'Valid image required',
            ];
            $this->validate($request,$rules,$customMessages);

            // Upload Image
            if ($request->hasFile('admin_image')) {
                $image_tmp = $request->file('admin_image');
                if ($image_tmp->isValid()) {
                    // Get image extension
                    $extension = $image_tmp->getClientOriginalExtension();
                    // Generate new image name
                    $imageName = \rand(111,99999).'.'.$extension;
                    $imagePath = 'images/admin_images/admin_photos/'.$imageName;
                    // Upload image
                    Image::make($image_tmp)->resize(200,200)->save($imagePath);
                }else if (!empty($data['current_admin_image'])) {
                    $imageName = $data['current_admin_image'];
                }else{
                    $imageName = "";
                }
            }else if (!empty($data['current_admin_image'])) {
                $imageName = $data['current_admin_image'];
            }else{
                $imageName = "";
            }

            // Update Admin Details
            Admin::where('email',Auth::guard('admin')->user()->email)
            ->update(['name'=>$data['admin_name'],'mobile'=>$data['admin_mobile'],'image'=>$imageName]);
            Session::flash('success_message','Admin details updated successfully!');            
            return \redirect()->back();
        }
        return \view('admin.update_admin_details')->with(\compact('title'));
    }
}
