<?php

use Illuminate\Database\Seeder;
use App\Banner;

class BannersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bannerRecords = [
            ['id'=>1,'image'=>'banner1.png','link'=>'','title'=>'Dr. Martens','alt'=>'Dr. Martens','status'=>1],
            ['id'=>2,'image'=>'banner2.png','link'=>'','title'=>'Superdry','alt'=>'Superdry','status'=>1],
            ['id'=>3,'image'=>'banner3.png','link'=>'','title'=>'DC Shoes','alt'=>'DC Shoes','status'=>1],            
        ];
        Banner::insert($bannerRecords);
    }
}
