<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Image;
use App\Banner;

class BannerController extends Controller
{
    public function banners(){
        Session::put('page','banners');
        $banners = Banner::get()->toArray();
        // dd($banners); die;
        $title = "Banner";
        return \view('admin.banners.banners')->with(\compact('banners','title'));
    }

    public function addEditBanner($id=null,Request $request){
        if ($id=="") {
            // Add Banner
            $banner = new Banner;
            $title = "Add Banner Image";
            $message = "Banner added successfully!";
        }else{
            // Edit Banner
            $banner = Banner::find($id);
            $title = "Edit Banner Image";
            $message = "Banner updated successfully!";
        }

        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if (empty($data['link'])) {
                $banner->link = "";
            }else{
                $banner->link = $data['link'];
            }

            if (empty($data['title'])) {
                $banner->title = "";
            }else{
                $banner->title = $data['title'];
            }
            
            if (empty($data['alt'])) {
                $banner->alt = "";
            }else{
                $banner->alt = $data['alt'];
            }
            
            $banner->status = 1;
            
            // Upload Banner Image
            if ($request->hasFile('image')) {
                $image_tmp = $request->file('image');
                if ($image_tmp->isValid()) {
                    // Upload Images after resize
                    $image_name = $image_tmp->getClientOriginalName();
                    $extension = $image_tmp->getClientOriginalExtension();
                    $imageName = $image_name.'-'.rand(111,99999).'.'.$extension;
                    $banner_image_path = 'images/banner_images/'.$imageName;
                    Image::make($image_tmp)->resize(1170,480)->save($banner_image_path);
                    $banner->image = $imageName;
                }
            }else if (!empty($data['current_banner'])) {
                $imageName = $data['current_banner'];
            }else{
                $imageName = "";
            }
            $banner->save();
            Session::flash('success_message',$message);
            return \redirect('admin/banners');
        }
        
        return \view('admin.banners.add_edit_banner')->with(\compact('title','banner'));
    }

    public function updateBannerStatus(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if ($data['status']=="Active") {
                $status = 0;
            }else{
                $status = 1;
            }
            Banner::where('id',$data['banner_id'])->update(['status'=>$status]);
            return \response()->json(['status'=>$status,'banner_id'=>$data['banner_id']]);
        }
    }

    public function deleteBanner($id){
        // Get Banner Image
        $bannerImage = Banner::where('id',$id)->first();

        // Get Banner Image Path
        $banner_image_path = 'images/banner_images/';

        // Delete Banner Image if exists from banners folder
        if (file_exists($banner_image_path.$bannerImage->image)) {
            unlink($banner_image_path.$bannerImage->image);
        }

        // Delete Banner Image from banners table
        Banner::where('id',$id)->delete();

        Session::flash('success_message','Banner deleted successfully!');
        return \redirect()->back();

    }
}
