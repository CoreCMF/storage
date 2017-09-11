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
            $table->string('service',30)        ->comment('驱动标识')->unique() ;
            $table->string('client_id',60)      ->comment('客户ID') ->nullable();
            $table->string('client_secret',80)  ->comment('客户密钥')->nullable();
            $table->string('redirect',180)      ->comment('回调地址')->nullable();
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
