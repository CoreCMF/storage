<?php
namespace CoreCMF\Socialite\Databases\seeds;

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
        DB::table('socialite_configs')->insert([
            'name' => '微信',
            'service' => 'wechat',
            'redirect'=> 'http://corecmf.dev/OAuth/wechat/callback',
        ]);
        DB::table('socialite_configs')->insert([
            'name' => 'QQ',
            'service' => 'qq',
            'redirect'=> 'http://corecmf.dev/OAuth/qq/callback',
        ]);
        DB::table('socialite_configs')->insert([
            'name' => '微信WEB',
            'service' => 'wechatweb',
            'redirect'=> 'http://corecmf.dev/OAuth/wechatweb/callback',
        ]);
        DB::table('socialite_configs')->insert([
            'name' => '微博',
            'service' => 'weibo',
            'redirect'=> 'http://corecmf.dev/OAuth/weibo/callback',
        ]);
        // DB::table('socialite_configs')->insert([
        //     'name' => '人人',
        //     'service' => 'renren',
        //     'redirect'=> 'http://corecmf.dev/OAuth/renren/callback',
        // ]);
        DB::table('socialite_configs')->insert([
            'name' => '豆瓣',
            'service' => 'douban',
            'redirect'=> 'http://corecmf.dev/OAuth/douban/callback',
        ]);
        // DB::table('socialite_configs')->insert([
        //     'name' => '百度',
        //     'service' => 'baidu',
        //     'redirect'=> 'http://corecmf.dev/OAuth/baidu/callback',
        // ]);
        // DB::table('socialite_configs')->insert([
        //     'name' => '支付宝',
        //     'service' => 'alipay',
        //     'redirect'=> 'http://corecmf.dev/OAuth/alipay/callback',
        // ]);
        // DB::table('socialite_configs')->insert([
        //     'name' => '淘宝',
        //     'service' => 'taobao',
        //     'redirect'=> 'http://corecmf.dev/OAuth/taobao/callback',
        // ]);
        DB::table('socialite_configs')->insert([
            'name' => 'GitHub',
            'service' => 'github',
            'redirect'=> 'http://corecmf.dev/OAuth/github/callback',
        ]);
        DB::table('socialite_configs')->insert([
            'name' => 'FaceBook',
            'service' => 'facebook',
            'redirect'=> 'http://corecmf.dev/OAuth/facebook/callback',
        ]);
        DB::table('socialite_configs')->insert([
            'name' => 'Google',
            'service' => 'google',
            'redirect'=> 'http://corecmf.dev/OAuth/google/callback',
        ]);
        DB::table('socialite_configs')->insert([
            'name' => 'Linkedin',
            'service' => 'linkedin',
            'redirect'=> 'http://corecmf.dev/OAuth/linkedin/callback',
        ]);
        DB::table('socialite_configs')->insert([
            'name' => 'Twitter',
            'service' => 'twitter',
            'redirect'=> 'http://corecmf.dev/OAuth/twitter/callback',
        ]);
    }
}
