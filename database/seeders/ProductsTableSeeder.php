<?php

use Illuminate\Database\Seeder;
use App\Product;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productRecords = [
            ['id'=>1, 
            'category_id'=>4, 
            'section_id'=>1, 
            'product_name'=>'Blue Casual T-Shirt', 
            'product_code'=>'BTS001', 
            'product_color'=>'Blue', 
            'main_image'=>'', 
            'description'=>'Casual Fred Perry', 
            'wash_care'=>'', 
            'fabric'=>'', 
            'pattern'=>'', 
            'sleeve'=>'', 
            'fit'=>'Slim fit', 
            'occasion'=>'', 
            'product_price'=>95, 
            'product_discount'=>0, 
            'product_weight'=>188, 
            'product_video'=>'', 
            'meta_title'=>'', 
            'meta_description'=>'', 
            'meta_keywords'=>'', 
            'is_featured'=>'No',
            'status'=>1],

            ['id'=>2, 
            'category_id'=>4, 
            'section_id'=>1, 
            'product_name'=>'Red Casual T-Shirt', 
            'product_code'=>'RTS001', 
            'product_color'=>'Red', 
            'main_image'=>'', 
            'description'=>'Casual Fred Perry', 
            'wash_care'=>'', 
            'fabric'=>'', 
            'pattern'=>'', 
            'sleeve'=>'', 
            'fit'=>'Slim fit', 
            'occasion'=>'', 
            'product_price'=>95, 
            'product_discount'=>10, 
            'product_weight'=>188, 
            'product_video'=>'', 
            'meta_title'=>'', 
            'meta_description'=>'', 
            'meta_keywords'=>'', 
            'is_featured'=>'Yes',
            'status'=>1]
        ]; 
        
        Product::insert($productRecords);
            
    }
}
