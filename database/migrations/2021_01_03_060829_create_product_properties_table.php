<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->string('size')->nullable();
            $table->string('material')->nullable();
            $table->string('color')->nullable();
            $table->string('design')->nullable();
            $table->string('sleeve')->nullable();
            $table->string('piece')->nullable();
            $table->string('set_type')->nullable();
            $table->string('description')->nullable();
            $table->string('maintenance')->nullable();
            $table->string('made_in')->nullable();
            $table->string('origin')->nullable();
            $table->string('type')->nullable();
            $table->string('for_use')->nullable();
            $table->string('collar')->nullable();
            $table->string('height')->nullable();
            $table->string('physical_feature')->nullable();
            $table->string('production_time')->nullable();
            $table->string('demension')->nullable();
            $table->string('crotch')->nullable();
            $table->string('close')->nullable();
            $table->string('drop')->nullable();
            $table->string('cumin')->nullable();
            $table->string('close_shoes')->nullable();
            $table->string('typeـofـclothing')->nullable();
            $table->string('specialized_features')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('product_properties');
    }
}
