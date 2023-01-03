<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use App\Brand;

class BrandController extends Controller
{
    public function brands(){
        Session::put('page','brands');
        $brands = Brand::get();
        $title = "Brands";
        return \view('admin.brands.brands')->with(\compact('brands','title'));
    }

    public function updateBrandStatus(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if ($data['status']=="Active") {
                $status = 0;
            }else{
                $status = 1;
            }
            Brand::where('id',$data['brand_id'])->update(['status'=>$status]);
            return \response()->json(['status'=>$status,'brand_id'=>$data['brand_id']]);
        }
    }

    public function addEditBrand(Request $request,$id=null){
        Session::put('page','brands');
        if ($id=="") {
            $title = "Add Brand";
            $brand = new Brand;
            $message = "Brand added successully!";
        }else{
            $title = "Edit Brand";
            $brand = Brand::find($id);
            $message = "Brand updated successully!";
        }

        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            // Brand Validation
            $rules = [
                'brand_name' => 'required|regex:/^[\pL\s\-]+$/u',                
            ];
            $customMessages = [
                'brand_name.required' => 'Brand Name is required',
                'brand_name.regex' => 'Brand Name is only alphabetical',                
            ];
            $this->validate($request,$rules,$customMessages);

            $brand->name = $data['brand_name'];
            $brand->status = 1;
            $brand->save();

            Session::flash('success_message',$message);
            return \redirect('admin/brands');
        }

        return \view('admin.brands.add_edit_brand')->with(\compact('title','brand'));
    }

    public function deleteBrand($id){
        // Delete Brand
        Brand::where('id',$id)->delete();
        $message = "Brand has been deleted successfully!";
        Session::flash('success_message',$message);
        return \redirect()->back();
    }
}
