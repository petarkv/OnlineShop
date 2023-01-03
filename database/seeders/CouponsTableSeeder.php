<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Coupon;

class CouponsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $couponsRecords = [
            ['id'=>1,'coupon_option'=>'Manual','coupon_code'=>'ppos10','categories'=>'1,2','users'=>'datingsitebre@gmail.com,ddog@onlineshop.com',
            'coupon_type'=>'Single','amount_type'=>'Percentage','amount'=>'10','expiry_date'=>'2021-09-15','status'=>1]
        ];
        Coupon::insert($couponsRecords);
    }
}
