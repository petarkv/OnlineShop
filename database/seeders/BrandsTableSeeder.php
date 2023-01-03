<?php

use Illuminate\Database\Seeder;
use App\Brand;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $brandRecords = [
            ['id'=>1,'name'=>'Alpha Industries','status'=>1],
            ['id'=>2,'name'=>'Fred Perry','status'=>1],
            ['id'=>3,'name'=>'Dr Martens','status'=>1],
            ['id'=>4,'name'=>'Lonsdale','status'=>1],
        ];
        Brand::insert($brandRecords);
    }
}
