<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullableToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('created_by',60)->nullable()->change();
            $table->string('updated_by',60)->nullable()->change();
            $table->string('deleted_by',60)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('created_by',60)->nullable(false)->change();
            $table->string('updated_by',60)->nullable(false)->change();
            $table->string('deleted_by',60)->nullable(false)->change();
        });
    }
}
