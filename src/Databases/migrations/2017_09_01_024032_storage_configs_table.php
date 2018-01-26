<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StorageConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storage_configs', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('disks', 30)          ->comment('磁盘')->unique();
            $table->string('driver', 30)         ->comment('驱动');
            $table->string('access_id', 60)      ->comment('客户ID') ->nullable();
            $table->string('access_key', 80)     ->comment('客户密钥')->nullable();
            $table->string('bucket', 80)         ->comment('Bucket')->nullable();
            $table->string('domain', 160)        ->comment('域名')->nullable();
            $table->string('app_id', 80)         ->comment('腾讯云AppId')->nullable();
            $table->string('region', 8)          ->comment('腾讯云所在地域')->nullable();
            $table->boolean('transport')         ->comment('https')->default(false);
            $table->string('status', 16)         ->comment('开关状态')->default('close');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storage_configs');
    }
}
