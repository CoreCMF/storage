<?php
namespace CoreCMF\Storage\Databases\seeds;

use DB;
use Illuminate\Database\Seeder;

class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('storage_configs')->insert([
            'disks' => 'oss_01',
            'driver'=> 'oss',
        ]);
        DB::table('storage_configs')->insert([
            'disks' => 'qiniu_01',
            'driver'=> 'qiniu',
        ]);
        DB::table('storage_configs')->insert([
            'disks' => 'cos_01',
            'driver'=> 'cos',
        ]);
        DB::table('storage_configs')->insert([
            'disks' => 'upyun_01',
            'driver'=> 'upyun',
        ]);
    }
}
