<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use Session;
use Auth;
use App\Category;
use App\Product;
use App\ProductsAttribute;
use App\Cart;
use App\Coupon;
use App\User;

class ProductController extends Controller
{
    public function listing(Request $request){
        Paginator::useBootstrap();
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $url = $data['url'];
            $categoryCount = Category::where(['url'=>$url,'status'=>1])->count();
            if ($categoryCount>0) {
                // echo "Postoji Kategorija"; die;
                $categoryDetails = Category::catDetails($url);
                // echo "<pre>"; print_r($categoryDetails); die;
                // $categoryProducts = Product::with('brand')->whereIn('category_id',$categoryDetails['catIds'])->
                // where('status',1)->get()->toArray();
                $categoryProducts = Product::with('brand')->whereIn('category_id',$categoryDetails['catIds'])->
                where('status',1);

                // If Fabric filter is selected
                if (isset($data['fabric']) && !empty($data['fabric'])) {
                    $categoryProducts->whereIn('products.fabric',$data['fabric']);
                }

                // If Sleeve filter is selected
                if (isset($data['sleeve']) && !empty($data['sleeve'])) {
                    $categoryProducts->whereIn('products.sleeve',$data['sleeve']);
                }

                // If Pattern filter is selected
                if (isset($data['pattern']) && !empty($data['pattern'])) {
                    $categoryProducts->whereIn('products.pattern',$data['pattern']);
                }

                // If Fit filter is selected
                if (isset($data['fit']) && !empty($data['fit'])) {
                    $categoryProducts->whereIn('products.fit',$data['fit']);
                }

                // If Occasion filter is selected
                if (isset($data['occasion']) && !empty($data['occasion'])) {
                    $categoryProducts->whereIn('products.occasion',$data['occasion']);
                }

                // If Sort option is selected
                if (isset($data['sort']) && !empty($data['sort'])) {
                    if ($data['sort']=="product_latest") {
                        $categoryProducts->orderBy('id','Desc');
                    }else if ($data['sort']=="product_name_a_z") {
                        $categoryProducts->orderBy('product_name','Asc');
                    }else if ($data['sort']=="product_name_z_a") {
                        $categoryProducts->orderBy('product_name','Desc');
                    }else if ($data['sort']=="price_lowest") {
                        $categoryProducts->orderBy('product_price','Asc');
                    }else if ($data['sort']=="price_highest") {
                        $categoryProducts->orderBy('product_price','Desc');
                    }
                }else{
                    $categoryProducts->orderBy('id','Desc');
                }
                $categoryProducts = $categoryProducts->paginate(30);
                // echo "<pre>"; print_r($categoryProducts); die;
                return \view('front.products.ajax_products_listing')->with(\compact('categoryDetails','categoryProducts','url'));
            }else{
            abort(404);
            }

        }else {
            $url = Route::getFacadeRoot()->current()->uri();
            $categoryCount = Category::where(['url'=>$url,'status'=>1])->count();
            if ($categoryCount>0) {
                // echo "Postoji Kategorija"; die;
                $categoryDetails = Category::catDetails($url);
                // echo "<pre>"; print_r($categoryDetails); die;
                // $categoryProducts = Product::with('brand')->whereIn('category_id',$categoryDetails['catIds'])->
                // where('status',1)->get()->toArray();
                $categoryProducts = Product::with('brand')->whereIn('category_id',$categoryDetails['catIds'])->
                where('status',1);
                $categoryProducts = $categoryProducts->paginate(3);

                // Product Filters
                $productFilters = Product::productFilters();
                $fabricArray = $productFilters['fabricArray'];
                $sleeveArray = $productFilters['sleeveArray'];
                $patternArray = $productFilters['patternArray'];
                $fitArray = $productFilters['fitArray'];
                $occasionArray = $productFilters['occasionArray'];

                $page_name = "listing";
                // echo "<pre>"; print_r($categoryProducts); die;
                return \view('front.products.listing')->with(\compact('categoryDetails','categoryProducts','url',
                            'fabricArray','sleeveArray','patternArray','fitArray','occasionArray','page_name'));
            }else{
            abort(404);
            }
        }        
    }

    public function detail($id){
        $productDetails = Product::with(['category','brand','attributes'=>function($query){
            $query->where('status',1);
        },'images'])->find($id)->toArray();
        // dd($productDetails); die;
        $total_stock = ProductsAttribute::where('product_id',$id)->sum('stock');
        $relatedProducts = Product::where('category_id',$productDetails['category']['id'])->
        where('id','!=',$id)->limit(3)->inRandomOrder()->get()->toArray();
        // dd($relatedProducts); die;
        return view('front.products.detail')->with(\compact('productDetails','total_stock','relatedProducts'));
    }

    public function getProductPrice(Request $request){
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            // $getProductPrice = ProductsAttribute::where(['product_id'=>$data['product_id'],'size'=>$data['size']])->first();
            // $discounted_price = Product::getDiscountedAttrPrice($data['product_id'],$data['size']);
            $getDiscountedAttrPrice = Product::getDiscountedAttrPrice($data['product_id'],$data['size']);
            return $getDiscountedAttrPrice;
        }
    }

    public function addToCart(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            // Check Product Stock is available or not
            $getProductStock = ProductsAttribute::where(['product_id'=>$data['product_id'],'size'=>$data['size']])->first()->toArray();
            // echo $getProductStock['stock']; die;

            if ($getProductStock['stock']<$data['quantity']) {
                $message = "Required Quantity is not available!";
                Session::flash('error_message',$message);
                return \redirect()->back();
            }

            // Generate Session Id if not exists
            $session_id = Session::get('session_id');
            if (empty($session_id)) {
                $session_id = Session::getId();
                Session::put('session_id',$session_id);
            }

            // Check Product if alrady exists in User Cart
            if (Auth::check()) {
                // User is logged in
                $countProduct = Cart::where(['product_id'=>$data['product_id'],'size'=>$data['size'],
                'user_id'=>Auth::user()->id])->count();
            }else {
                // User is not logged in
                $countProduct = Cart::where(['product_id'=>$data['product_id'],'size'=>$data['size'],
                'session_id'=>Session::get('session_id')])->count();
            }
            
            if ($countProduct>0) {
                $message = "Product already exists in Cart!";
                Session::flash('error_message',$message);
                return \redirect('cart');
            }

            if (Auth::check()) {
                $user_id = Auth::user()->id; 
            }else {
                $user_id = 0;
            }

            // Save Product in Cart
            // Cart::insert(['session_id'=>$session_id,'user_id'=>1,'product_id'=>$data['product_id'],'size'=>$data['size'],'quantity'=>$data['quantity']]);
            $cart = new Cart;
            $cart->session_id = $session_id;
            $cart->user_id = $user_id;
            $cart->product_id = $data['product_id'];
            $cart->size = $data['size'];
            $cart->quantity = $data['quantity'];
            $cart->save();

            $message = "Product has been added to Cart!";
            Session::flash('success_message',$message);
            return \redirect('cart');
        }
    }

    public function cart(){
        $userCartItems = Cart::userCartItems();
        // echo"<pre>"; print_r($userCartItems); die;
        return \view('front.products.cart')->with(\compact('userCartItems'));
    }

    public function updateCartItemQty(Request $request){
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; \print_r($data); die;

            // Get Cart Details
            $cartDetails = Cart::find($data['cartid']);

            // Get Available Product Stock
            $availableStock = ProductsAttribute::select('stock')->where(['product_id'=>$cartDetails['product_id'],'size'=>$cartDetails['size']])->
            first()->toArray();

            // echo "Demanded Stock: ".$data['qty'];
            // echo "<br>";
            // echo "Available Stock: ".$availableStock['stock']; die;

            // Check Stock is available or not
            if ($data['qty']>$availableStock['stock']) {
                $userCartItems = Cart::userCartItems();
                return \response()->json([
                    'status'=>false,
                    'message'=>'Product Stock is not available',
                    'view'=>(String)View::make('front.products.cart_items')->
                    with(\compact('userCartItems'))
                ]);
            }

            // Check Size is available or not
            $availableSize = ProductsAttribute::where(['product_id'=>$cartDetails['product_id'],'size'=>$cartDetails['size'],'status'=>1])->count();
            if ($availableSize==0) {
                $userCartItems = Cart::userCartItems();
                return \response()->json([
                    'status'=>false,
                    'message'=>'Product Size is not available',
                    'view'=>(String)View::make('front.products.cart_items')->
                    with(\compact('userCartItems'))
                ]);
            }

            Cart::where('id',$data['cartid'])->update(['quantity'=>$data['qty']]);
            $userCartItems = Cart::userCartItems();
            $totalCartItems = totalCartItems();
            return \response()->json([
                'status'=>true,
                'totalCartItems'=>$totalCartItems,
                'view'=>(String)View::make('front.products.cart_items')->
                    with(\compact('userCartItems'))]);
        }
    }

    public function deleteCartItem(Request $request){
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            Cart::where('id',$data['cartid'])->delete();
            $userCartItems = Cart::userCartItems();
            $totalCartItems = totalCartItems();
            return \response()->json([
                'totalCartItems'=>$totalCartItems,
                'view'=>(String)View::make('front.products.cart_items')->
                with(\compact('userCartItems'))
            ]);
        }
    }

    public function applyCoupon(Request $request){
        if ($request->ajax()) {
            $data = $request->all();
            // echo "<pre>"; \print_r($data); die;
            $userCartItems = Cart::userCartItems();
            $couponCount = Coupon::where('coupon_code',$data['code'])->count();
            if ($couponCount==0) {
                $userCartItems = Cart::userCartItems();
                $totalCartItems = totalCartItems();
                return \response()->json([
                    'status'=>false,
                    'message'=>'This Coupon is not valid!',
                    'totalCartItems'=>$totalCartItems,
                    'view'=>(String)View::make('front.products.cart_items')->
                    with(\compact('userCartItems'))
                ]);
            }else {
                // Check for other coupon conditions

                // Get Coupon Details
                $couponDetails = Coupon::where('coupon_code',$data['code'])->first();

                // Check if Coupon is inactive
                if ($couponDetails->status==0) {
                    $message = 'This Coupon is not active!';
                }

                // Check if Coupon is expired
                $expiry_date = $couponDetails->expiry_date;
                // $current_date = date('y-m-d');
                $current_date = Carbon::now()->format('Y-m-d');
                // echo "<pre>"; \print_r($current_date); die;
                if ($expiry_date<$current_date) {
                    $message = 'This Coupon is expired!';
                }

                // Check if Coupon is from selected categories
                // Get all selected categories from Coupon
                $catArr = \explode(',',$couponDetails->categories);

                // Get Cart Items
                $userCartItems = Cart::userCartItems();
                // echo "<pre>"; print_r($userCartItems); die;

                // Check if any Item belongs to Coupon Category
                foreach ($userCartItems as $key => $item) {
                    if (!\in_array($item['product']['category_id'],$catArr)) {
                        $message = 'This Coupon Code is not for one of the selected products!';
                    }
                }

                // Check if Coupon belongs to logged in User
                // Get all selected Users of Coupon
                $userArr = $catArr = \explode(",",$couponDetails->users);

                // Get User ID's of all selected Users
                foreach ($userArr as $key => $user) {
                    $getUserID = User::select('id')->where('email',$user)->first()->toArray();
                    $userID[] = $getUserID['id'];
                }

                // Get Cart Total Amount
                $total_amount = 0;
                foreach ($userCartItems as $key => $item) {

                    // if (!\in_array($item['product']['category_id'],$catArr)) {
                    //     $message = 'This Coupon Code is not for one of the selected products!';
                    // }

                    if (!\in_array($item['user_id'],$userID)) {
                        $message = 'This Coupon Code is not for You!';
                    }

                    $attrPrice = Product::getDiscountedAttrPrice($item['product_id'],$item['size']);
                    $total_amount = $total_amount + ($attrPrice['final_price']*$item['quantity']);
                }

                // echo $total_amount; die;
                if (isset($message)) {
                    $userCartItems = Cart::userCartItems();
                    $totalCartItems = totalCartItems();
                    return \response()->json([
                        'status'=>false,
                        'message'=>$message,
                        'totalCartItems'=>$totalCartItems,
                        'view'=>(String)View::make('front.products.cart_items')->
                        with(\compact('userCartItems'))
                    ]);
                }else{
                    // echo "Coupon can be redeemed!"; die;

                    // Check if Amount Type is Fixed or Percentage
                    if ($couponDetails->amount_type=="Fixed") {
                        $couponAmount = $couponDetails->amount;
                    }else {
                        $couponAmount = $total_amount * ($couponDetails->amount/100);
                    }

                    // Add Coupon  Code and Amount in Session Variable
                    
                }

            }
        }
    }
}
