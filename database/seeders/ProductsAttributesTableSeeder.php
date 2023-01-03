<?php

use Illuminate\Database\Seeder;
use App\ProductsAttribute;

class ProductsAttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productAttributesRecords = [
            ['id'=>1,'product_id'=>8,'size'=>'Small','price'=>185,'stock'=>5,'sku'=>'jack001w-S','status'=>1],
            ['id'=>2,'product_id'=>8,'size'=>'Medium','price'=>190,'stock'=>8,'sku'=>'jack001w-M','status'=>1],
            ['id'=>3,'product_id'=>8,'size'=>'Large','price'=>195,'stock'=>3,'sku'=>'jack001w-L','status'=>1]
        ];

        ProductsAttribute::insert($productAttributesRecords);
    }
}
