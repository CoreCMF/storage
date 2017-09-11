<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SocialiteUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socialite_users', function (Blueprint $table) {
            $table->increments('user_id')->unsigned();
            $table->string('github',64)->comment('GitHub')        ->nullable();
            $table->string('facebook',64)->comment('Facebook')    ->nullable();
            $table->string('google',64)->comment('Google')       ->nullable();
            $table->string('linkedin',64)->comment('LinkedIn')    ->nullable();
            $table->string('twitter',64)->comment('Twitter')      ->nullable();
            $table->string('qq',64)->comment('QQ')                ->nullable();
            $table->string('wechat',64)->comment('微信')          ->nullable();
            $table->string('wechatweb',64)->comment('微信WEB')    ->nullable();
            $table->string('weibo',64)->comment('微博')           ->nullable();
            $table->string('renren',64)->comment('人人')          ->nullable();
            $table->string('douban',64)->comment('豆瓣')          ->nullable();
            $table->string('baidu',64)->comment('百度')           ->nullable();
            $table->string('taobao',64)->comment('淘宝')          ->nullable();
            $table->string('alipay',64)->comment('支付宝')        ->nullable();
            $table->foreign('user_id')->references('id')->on('core_users')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('socialite_users');
    }
}
