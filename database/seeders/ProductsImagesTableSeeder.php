<?php

use Illuminate\Database\Seeder;
use App\ProductsImage;

class ProductsImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productsImageRecors = [
            ['id'=>1,'product_id'=>5,'image'=>'z24j011d-ma1-jacket.jpg-87798.jpg','status'=>1]
        ];
        ProductsImage::insert($productsImageRecors);
    }
}
