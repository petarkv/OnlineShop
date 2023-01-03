<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Product;
use App\Category;
use App\Section;
use App\ProductsAttribute;
use App\ProductsImage;
use App\Brand;
use Session;
use Image;

class ProductController extends Controller
{
    public function products(){
        Session::put('page','products');
        $products = Product::with(['category'=>function($query){
            $query->select('id','category_name');
        },'section'=>function($query){
            $query->select('id','name');
        }])->get();
        // $products = \json_decode(\json_encode($products));
        // echo "<pre>"; print_r($products); die;
        $title = "Products";
        return \view('admin.products.products')->with(\compact('products','title'));
    }

    public function updateProductStatus(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if ($data['status']=="Active") {
                $status = 0;
            }else{
                $status = 1;
            }
            Product::where('id',$data['product_id'])->update(['status'=>$status]);
            return \response()->json(['status'=>$status,'product_id'=>$data['product_id']]);
        }
    }

    public function deleteProduct($id){
        // Delete Product
        Product::where('id',$id)->delete();
        $message = "Product has been deleted successfully!";
        Session::flash('success_message',$message);
        return \redirect()->back();
    }

    public function addEditProduct(Request $request,$id=null){
        if ($id=="") {
            $title = "Add Product";
            $product = new Product;
            $productdata = array();
            $message = "Product added successfully!";
        }else{
            $title = "Edit Product";
            $productdata = Product::find($id);
            $productdata = \json_decode(\json_encode($productdata),true);
            // echo "<pre>"; \print_r($productdata); die;
            $product = Product::find($id);
            $message = "Product updated successfully!";
        }

        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

        // Product Validation
        $rules = [
            'category_id' => 'required',
            'brand_id' => 'required',
            'product_name' => 'required|regex:/^[\pL\s\-]+$/u',
            'product_code' => 'required|regex:/^[\w-]*$/',            
            'product_price' => 'required|numeric',
            'product_color' => 'required|regex:/^[\pL\s\-]+$/u',
            // 'product_image' => 'image',
        ];
        $customMessages = [
            'category_id.required' => 'Category is required',
            'brand_id.required' => 'Brand is required',
            'product_name.required' => 'Product Name is required',
            'product_name.regex' => 'Product Name is only alphabetical',
            'product_code.required' => 'Product Code is required',
            'product_code.regex' => 'Valid Product Code is required', 
            'product_price.required' => 'Product Price is required',
            'product_price.numeric' => 'Valid Product Price is required',
            'product_color.required' => 'Product Color is required',
            'product_color.regex' => 'Valid Product Color is required',                     
            // 'product_image.image' => 'Valid Product Image required',
        ];
        $this->validate($request,$rules,$customMessages);

        if (empty($data['is_featured'])) {
            $is_featured = "No";
        }else{
            $is_featured = "Yes";
        }

        if (empty($data['product_discount'])) {
            $data['product_discount'] = 0.00;
        }
        
        if (empty($data['product_weight'])) {
            $data['product_weight'] = 0.00;
        }
        
        if (empty($data['description'])) {
            $data['description'] = "";
        }
        
        if (empty($data['wash_care'])) {
            $data['wash_care'] = "";
        }

        if (empty($data['fabric'])) {
            $data['fabric'] = "";
        }

        if (empty($data['pattern'])) {
            $data['pattern'] = "";
        }

        if (empty($data['sleeve'])) {
            $data['sleeve'] = "";
        }

        if (empty($data['fit'])) {
            $data['fit'] = "";
        }

        if (empty($data['occasion'])) {
            $data['occasion'] = "";
        }

        if (empty($data['meta_title'])) {
            $data['meta_title'] = "";
        }

        if (empty($data['meta_keywords'])) {
            $data['meta_keywords'] = "";
        }

        if (empty($data['meta_description'])) {
            $data['meta_description'] = "";
        }

        if (empty($data['pattern'])) {
            $data['pattern'] = "";
        }

        if (empty($data['pattern'])) {
            $data['pattern'] = "";
        }

        if (empty($data['product_video'])) {
            $data['product_video'] = "";
        }

        // if (empty($data['main_image'])) {
        //     $data['main_image'] = "";
        // }

        // Upload Product Image
        if ($request->hasFile('main_image')) {
            $image_tmp = $request->file('main_image');
            if ($image_tmp->isValid()) {
                // Upload Images after resize
                $image_name = $image_tmp->getClientOriginalName();
                $extension = $image_tmp->getClientOriginalExtension();
                $imageName = $image_name.'-'.rand(111,99999).'.'.$extension;
                $large_image_path = 'images/product_images/large/'.$imageName;
                $medium_image_path = 'images/product_images/medium/'.$imageName;
                $small_image_path = 'images/product_images/small/'.$imageName;
                Image::make($image_tmp)->save($large_image_path);  // W:1400 H:1200
                Image::make($image_tmp)->resize(520,600)->save($medium_image_path);
                Image::make($image_tmp)->resize(260,300)->save($small_image_path);
                $product->main_image = $imageName;
            }
        }else{
            $imageName = "";
            $product->main_image = $imageName;
        }
 
        // Upload Product Video
        if ($request->hasFile('product_video')) {
            $video_tmp = $request->file('product_video');
            if ($video_tmp->isValid()) {
                // Upload video
                $video_name = $video_tmp->getClientOriginalName();
                $extension = $video_tmp->getClientOriginalExtension();
                $videoName = $video_name.'-'.rand(111,99999).'.'.$extension;
                $video_path = 'videos/product_videos/';                
                $video_tmp->move($video_path,$videoName);
                $product->product_video = $videoName;
            }
        }else{
            $videoName = "";
            $product->product_video = $videoName;
        }

        // Save Product details in products table
        $categoryDetails = Category::find($data['category_id']);
        $product->section_id = $categoryDetails['section_id'];
        $product->brand_id = $data['brand_id'];
        $product->category_id = $data['category_id'];
        $product->product_name = $data['product_name'];
        $product->product_code = $data['product_code'];
        $product->product_color = $data['product_color'];
        $product->product_price = $data['product_price'];
        $product->product_discount = $data['product_discount'];
        $product->product_weight = $data['product_weight'];
        $product->description = $data['description'];
        $product->wash_care = $data['wash_care'];
        $product->fabric = $data['fabric'];
        $product->pattern = $data['pattern'];
        $product->sleeve = $data['sleeve'];
        $product->fit = $data['fit'];
        $product->occasion = $data['occasion'];
        $product->meta_title = $data['meta_title'];
        $product->meta_keywords = $data['meta_keywords'];
        $product->meta_description = $data['meta_description'];
        // if (!empty($data['is_featured'])) {
        //     $product->is_featured = $data['is_featured'];
        // }else{
            // $product->is_featured = "No";
        // } 
        $product->is_featured = $is_featured;
        $product->status = 1;
        // $product->product_video = $videoName;       
        // $product->main_image = $imageName;
        $product->save();

        Session::flash('success_message',$message);
        return \redirect('admin/products');
        }
        
        // Filter Arrays
        // $fabricArray = array('Cotton','Polyester','Wool');
        // $sleeveArray = array('Full Sleeve','Half Sleeve','Short Sleeve','Sleeveless');
        // $patternArray = array('Checked','Plain','Printed','Self','Solid');
        // $fitArray = array('Regular','Slim');
        // $occasionArray = array('Casual','Slim');
        $productFilters = Product::productFilters();
        $fabricArray = $productFilters['fabricArray'];
        $sleeveArray = $productFilters['sleeveArray'];
        $patternArray = $productFilters['patternArray'];
        $fitArray = $productFilters['fitArray'];
        $occasionArray = $productFilters['occasionArray'];

        // Section with Categories and Sub Categories
        $categories = Section::with('categories')->get();
        $categories = \json_decode(\json_encode($categories),true);
        // echo "<pre>"; print_r($categories); die;

        // Get all Brands
        $brands = Brand::where('status',1)->get();
        $brands = \json_decode(\json_encode($brands),true);

        return \view('admin.products.add_edit_product')->with(\compact('title','fabricArray','sleeveArray',
                    'patternArray','fitArray','occasionArray','categories','productdata','brands'));
    }

    public function deleteProductImage($id){
        // Get Product Image
        $productImage = Product::select('main_image')->where('id',$id)->first();
        // Get Product Image Path
        $small_image_path = 'images/product_images/small/';
        $medium_image_path = 'images/product_images/medium/';
        $large_image_path = 'images/product_images/large/';

        // Delete Product Image from product_images folder if exists
        if (file_exists($small_image_path.$productImage->main_image)) {
            unlink($small_image_path.$productImage->main_image);
        }
        if (file_exists($medium_image_path.$productImage->main_image)) {
            unlink($medium_image_path.$productImage->main_image);
        }
        if (file_exists($large_image_path.$productImage->main_image)) {
            unlink($large_image_path.$productImage->main_image);
        }

        // Delete product Image from categories table
        Product::where('id',$id)->update(['main_image'=>'']);

        $message = "Product Image has been deleted successfully!";
        Session::flash('success_message',$message);
        return \redirect()->back();
    }

    public function deleteProductVideo($id){
        // Get Product Video
        $productVideo = Product::select('product_video')->where('id',$id)->first();
        // Get Product Video Path
        $product_video_path = 'videos/product_videos/';
        // Delete Product Video from product_videos folder if exists
        if (file_exists($product_video_path.$productVideo->product_video)) {
            unlink($product_video_path.$productVideo->product_video);
        }
        // Delete Product Image from categories table
        Product::where('id',$id)->update(['product_video'=>'']);

        $message = "Product Video has been deleted successfully!";
        Session::flash('success_message',$message);
        return \redirect()->back();
    }

    public function addAttributes(Request $request,$id){
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            foreach ($data['sku'] as $key => $value) {
                if (!empty($value)) {

                    // SKU already exists check
                    $attrCountSKU = ProductsAttribute::where(['sku'=>$value])->count();
                    if ($attrCountSKU>0) {
                        $message = "SKU already exists. Please enter another SKU.";
                        Session::flash('error_message',$message);
                        return \redirect()->back();
                    }

                    // Size already exists check
                    $attrCountSize = ProductsAttribute::where(['product_id'=>$id,'size'=>$data['size'][$key]])->count();
                    if ($attrCountSize>0) {
                        $message = "Size already exists. Please enter another Size.";
                        Session::flash('error_message',$message);
                        return \redirect()->back();
                    }

                    $attribute = new ProductsAttribute;
                    $attribute->product_id = $id;
                    $attribute->sku = $value;
                    $attribute->size = $data['size'][$key];
                    $attribute->price = $data['price'][$key];
                    $attribute->stock = $data['stock'][$key];
                    $attribute->status = 1;
                    $attribute->save();
                }
            }
            $success_message = "Product Attributes added successfully.";
            Session::flash('success_message',$success_message);
            return \redirect()->back();
        }
        $title = "Product Attributes";
        $productdata = Product::with('attributes')->find($id);
        $productdata = \json_decode(\json_encode($productdata),true);
        // echo "<pre>"; print_r($productdata); die;
        return \view('admin.products.add_attributes')->with(\compact('title','productdata'));
    }

    public function editAttributes(Request $request,$id){
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            foreach ($data['attrId'] as $key => $attr) {
                if(!empty($attr)){
                    ProductsAttribute::where(['id'=>$data['attrId'][$key]])->update(['price'=>$data['price'][$key],
                                                                                    'stock'=>$data['stock'][$key]]);
                }
                $success_message = "Product Attributes updated successfully.";
                Session::flash('success_message',$success_message);
                return \redirect()->back();
            }
        }
    }

    public function updateAttributeStatus(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if ($data['status']=="Active") {
                $status = 0;
            }else{
                $status = 1;
            }
            ProductsAttribute::where('id',$data['attribute_id'])->update(['status'=>$status]);
            return \response()->json(['status'=>$status,'attribute_id'=>$data['attribute_id']]);
        }
    }

    public function deleteAttribute($id){
        // Delete Attribute
        ProductsAttribute::where('id',$id)->delete();
        $message = "Product Attribute has been deleted successfully!";
        Session::flash('success_message',$message);
        return \redirect()->back();
    }

    public function addImages(Request $request,$id){
        if ($request->isMethod('post')) {
            // $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                foreach ($images as $key => $image) {
                    $productImage = new ProductsImage;
                    $image_tmp = Image::make($image);
                    $extension = $image->getClientOriginalExtension();
                    $imageName = rand(111,999999).time().".".$extension;

                    $large_image_path = 'images/product_images/large/'.$imageName;
                    $medium_image_path = 'images/product_images/medium/'.$imageName;
                    $small_image_path = 'images/product_images/small/'.$imageName;
                    Image::make($image_tmp)->save($large_image_path);  // W:1400 H:1200
                    Image::make($image_tmp)->resize(520,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(260,300)->save($small_image_path);
                    $productImage->image = $imageName;
                    $productImage->product_id = $id;
                    $productImage->status = 1;
                    $productImage->save();
                }
                $message = "Product Image(s) has been added successfully!";
                Session::flash('success_message',$message);
                return \redirect()->back();
            }
        }
        $productdata = Product::with('images')->select('id','product_name','product_code','product_color','main_image')->find($id);
        $productdata = \json_decode(\json_encode($productdata),true);
        // echo "<pre>"; print_r($productdata); die;
        $title = "Product Images";
        return \view('admin.products.add_images')->with(\compact('productdata','title'));
    }

    public function updateImageStatus(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if ($data['status']=="Active") {
                $status = 0;
            }else{
                $status = 1;
            }
            ProductsImage::where('id',$data['image_id'])->update(['status'=>$status]);
            return \response()->json(['status'=>$status,'image_id'=>$data['image_id']]);
        }
    }

    public function deleteImage($id){
        // Get Image
        $productImage = ProductsImage::select('image')->where('id',$id)->first();
        // Get Product Image Path
        $small_image_path = 'images/product_images/small/';
        $medium_image_path = 'images/product_images/medium/';
        $large_image_path = 'images/product_images/large/';

        // Delete Product Image from product_images folder if exists
        if (file_exists($small_image_path.$productImage->image)) {
            unlink($small_image_path.$productImage->image);
        }
        if (file_exists($medium_image_path.$productImage->image)) {
            unlink($medium_image_path.$productImage->image);
        }
        if (file_exists($large_image_path.$productImage->image)) {
            unlink($large_image_path.$productImage->image);
        }

        // Delete Image from products_images table
        ProductsImage::where('id',$id)->delete();

        $message = "Image has been deleted successfully!";
        Session::flash('success_message',$message);
        return \redirect()->back();
    }
}
