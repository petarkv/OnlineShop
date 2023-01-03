<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->delete();
        $adminRecords = [
            ['id'=>1,'name'=>'admin','type'=>'admin','mobile'=>'666999',
            'email'=>'admin@onlineshop.com','password'=>bcrypt('12345678'),'image'=>'','status'=>1],

            ['id'=>2,'name'=>'peca','type'=>'admin','mobile'=>'777999',
            'email'=>'peca@onlineshop.com','password'=>bcrypt('12345678'),'image'=>'','status'=>1],
        ];

        DB::table('admins')->insert($adminRecords);

        // foreach ($adminRecords as $key => $records) {
        //     \App\Admin::create($records);
        // }
    }
}
