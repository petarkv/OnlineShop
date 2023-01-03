<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

use App\Category;

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//ADMIN ROUTES
Route::prefix('/admin')->namespace('Admin')->group(function(){
    // LOGIN
    Route::match(['get','post'],'/','AdminController@login');
    // ADMIN MIDDLEWARE GROUP
    Route::group(['middleware'=>['admin']],function(){
        
        Route::get('dashboard','AdminController@dashboard'); // DASHBOARD       
        Route::get('settings','AdminController@settings');  // ADMIN SETTINGS        
        Route::get('logout','AdminController@logout'); // ADMIN LOGOUT        
        Route::post('check-current-password','AdminController@checkCurrentPassword'); // CHECK CURRENT PASSWORD        
        Route::post('update-current-password','AdminController@updateCurrentPassword'); // UPDATE CURRENT PASSWORD        
        Route::match(['get','post'],'update-admin-details', 'AdminController@updateAdminDetails'); // UPDATE ADMIN DETAILS

        // SECTIONS
        Route::get('sections','SectionController@sections'); // View Sections      
        Route::post('update-section-status','SectionController@updateSectionStatus'); // Update Section status

        // BRANDS
        Route::get('brands','BrandController@brands');  // View Brands
        Route::post('update-brand-status','BrandController@updateBrandStatus');  // Update Brand Status
        Route::match(['get','post'],'add-edit-brand/{id?}','BrandController@addEditBrand');  // Add - Edit Brand
        Route::get('delete-brand/{id}','BrandController@deleteBrand');  // Delete Brand

        // CATEGORIES
        Route::get('categories','CategoryController@categories'); // View categories
        Route::post('update-category-status','CategoryController@updateCategoryStatus'); // Update category status
        Route::match(['get','post'],'add-edit-category/{id?}','CategoryController@addEditCategory');  // Add - Edit
        Route::post('append-categories-level','CategoryController@appendCategoriesLevel');  // Append Categories Level
        Route::get('delete-category-image/{id}','CategoryController@deleteCategoryImage');  // Delete Category Image
        Route::get('delete-category/{id}','CategoryController@deleteCategory');  // Delete Category

        // PRODUCTS
        Route::get('products','ProductController@products');  // View Products
        Route::post('update-product-status','ProductController@updateProductStatus'); // Update product status
        Route::get('delete-product/{id}','ProductController@deleteProduct');  // Delete Product
        Route::match(['get','post'],'add-edit-product/{id?}','ProductController@addEditProduct');  // Add - Edit Product
        Route::get('delete-product-image/{id}','ProductController@deleteProductImage');  // Delete Product Image
        Route::get('delete-product-video/{id}','ProductController@deleteProductVideo');  // Delete Product Video

        // ATTRIBUTES
        Route::match(['get','post'],'add-attributes/{id}','ProductController@addAttributes');  // Add Product Attributes
        Route::post('edit-attributes/{id}','ProductController@editAttributes');  // Edit Product Attributes
        // Route::match(['get','post'],'edit-attributes/{id}','ProductController@editAttributes');
        Route::post('update-attribute-status','ProductController@updateAttributeStatus');  // Update Attribute Status
        Route::get('delete-attribute/{id}','ProductController@deleteAttribute');  // Delete Attribute

        // IMAGES
        Route::match(['get','post'],'add-images/{id}','ProductController@addImages');  // Add Product Images
        Route::post('update-image-status','ProductController@updateImageStatus');  // Update Image Status
        Route::get('delete-image/{id}','ProductController@deleteImage');  // Delete Image

        // BANNERS
        Route::get('banners','BannerController@banners');  // View Banners
        Route::match(['get','post'],'add-edit-banner/{id?}','BannerController@addEditBanner');  // Add - Edit Banner Image
        Route::post('update-banner-status','BannerController@updateBannerStatus');  // Update Banner Status
        Route::get('delete-banner/{id}','BannerController@deleteBanner');  // Delete Banner

        // COUPONS
        Route::get('coupons','CouponController@coupons');  // View Coupons
        Route::post('update-coupon-status','CouponController@updateCouponStatus');  // Update Coupon Status
        Route::match(['get','post'],'add-edit-coupon/{id?}','CouponController@addEditCoupon');  // Add - Edit Coupon
        Route::get('delete-coupon/{id}','CouponController@deleteCoupon');  // Delete Coupon
        
    });
    
});

// FRONT ROUTES
Route::namespace('Front')->group(function(){
    Route::get('/','IndexController@index');  // Home Page
    // Route::get('/{url}','ProductController@listing');  // Listing Page

    // Get Category Urls
    $catUrls = Category::select('url')->where('status',1)->get()->pluck('url')->toArray();
    // echo "<pre>"; print_r($catUrls); die;
    foreach ($catUrls as $url) {
        Route::get('/'.$url,'ProductController@listing');
    }

    Route::get('/product/{id}','ProductController@detail');  // PRODUCT DETAIL PAGE
    Route::post('/get-product-price','ProductController@getProductPrice');  // GET PRODUCT ATTRIBUTE PRICE
    Route::post('/add-to-cart','ProductController@addToCart');  // ADD TO CART
    Route::get('/cart','ProductController@cart');  // CART
    Route::post('/update-cart-item-qty','ProductController@updateCartItemQty');  // UPDATE CART ITEM QUANTITY
    Route::post('/delete-cart-item','ProductController@deleteCartItem');  // DELETE CART ITEM

    Route::get('/login-register',['as'=>'login','uses'=>'UserController@loginRegister']);  // LOGIN/REGISTER USER PAGE
    Route::post('/login','UserController@loginUser');  // LOGIN USER
    Route::post('/register','UserController@registerUser');  // REGISTER USER
    Route::match(['GET','POST'],'/check-email','UserController@checkEmail');  // CHECK EMAIL WHEN USER IS REGISTERING
    Route::get('/logout','UserController@logoutUser');  // LOGOUT USER
    Route::match(['GET','POST'],'/confirm/{code}','UserController@confirmAccount');  // CONFIRM USER ACCOUNT

    Route::match(['get','post'],'/forgot-password','UserController@forgotPassword');  // FORGOT PASSWORD

    Route::group(['middleware'=>['auth']],function(){        
        Route::match(['GET','POST'],'/account','UserController@account');  // USER ACCOUNT PAGE
        Route::post('/check-user-password','UserController@checkUserPassword');  // CHECK USER CURRENT PASSWORD
        Route::post('/update-user-password','UserController@updateUserPassword');  // UPDATE USER PASSWORD 
        
        Route::post('/apply-coupon','ProductController@applyCoupon');  // APPLY COUPON
    });     
});