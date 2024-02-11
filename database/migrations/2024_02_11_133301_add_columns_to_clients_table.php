<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumns( 'clients', ['birthplace', 'drivers_license_id'])){
            Schema::table('clients', function (Blueprint $table) {
                $table->string('birthplace')->nullable();
                $table->string('drivers_license_id')->nullable();
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumns('clients', ['birthplace', 'drivers_license_id'])){

            Schema::table('clients', function (Blueprint $table) {
                $table->dropColumn('birthplace');
                $table->dropColumn('drivers_license_id');
            });
        }

    }
}
