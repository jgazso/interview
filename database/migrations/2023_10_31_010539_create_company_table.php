<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id('companyId');
            $table->string('companyName');
            $table->string('companyRegistrationNumber');
            $table->date('companyFoundationDate');
            $table->string('country');
            $table->string('zipCode');
            $table->string('city');
            $table->string('streetAddress');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('companyOwner');
            $table->integer('employees');
            $table->string('activity');
            $table->boolean('active')->default(false);
            $table->string('email');
            $table->string('password');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
