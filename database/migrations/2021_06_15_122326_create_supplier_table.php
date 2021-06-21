<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier', function (Blueprint $table) {
            $table->increments('sid')->comment('Supplier ID');
            $table->string('name', 500)->comment('Supplier Name');
            $table->text('address')->comment('Supplier Address');
            $table->string('email', 100)->comment('Email Address')->nullable();
            $table->integer('other_phone')->comment('Other phones')->nullable();
            $table->text('rmk')->comment('Remark')->nullable();
            $table->integer('sts')->comment('Status (1=Acc/0=InAcc/2=Suspend)')->default('1');
            $table->integer('create_user_id')->comment('Created User ID');
            $table->dateTime('create_date_time')->comment('Created D+T');
            $table->integer('update_user_id')->comment('Last Modification User ID')->nullable();
            $table->dateTime('update_date_time')->comment('Last Modification D+T')->nullable();
        });
        DB::statement("ALTER TABLE `supplier` comment 'Supplier Master'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supplier');
    }
}
