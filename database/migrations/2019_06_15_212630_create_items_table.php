<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description');
            $table->string('model_number')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('serial_number')->unique()->nullable();
            $table->integer('inventory_number')->unique()->nullable();
            $table->string('quantity')->nullable();
            $table->string('owner')->nullable();
            $table->string('department')->nullable();
            $table->string('location')->nullable();
            $table->date('date_acquired')->nullable();
            $table->date('date_in_service')->nullable();
            $table->date('date_manufactured')->nullable();
            $table->string('status')->nullable();
            $table->string('fe_id')->unique()->nullable();
            $table->date('date_decommissioned')->nullable();
            $table->string('reason_for_decommission')->nullable();
            $table->date('last_inventoried')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
