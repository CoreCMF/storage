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
            $table->string('name',30)           ->comment('驱动名称');
            $table->string('driver',30)         ->comment('驱动标识')->unique() ;
            $table->string('access_id',60)      ->comment('客户ID') ->nullable();
            $table->string('access_key',80)     ->comment('客户密钥')->nullable();
            $table->string('bucket',80)         ->comment('回调地址')->nullable();
            $table->string('endpoint',180)      ->comment('回调地址')->nullable();
            $table->boolean('status')           ->comment('开关状态')->default(false);
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
