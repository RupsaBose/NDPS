<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeizuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seizures', function (Blueprint $table) {
            $table->increments('seizure_id')->nullable(false);
            $table->integer('ps_id')->nullable(true); 
            $table->integer('case_no')->nullable(false);
            $table->integer('case_year')->nullable(false);
            $table->integer('drug_id')->nullable(false);
            $table->double('quantity_of_drug',8,3)->nullable(false);  
            $table->integer('seizure_quantity_weighing_unit_id')->nullable(false);
            $table->date('date_of_seizure')->nullable(true);
            $table->date('date_of_disposal')->nullable(true);
            $table->double('disposal_quantity',8,3)->nullable(true);  
            $table->integer('disposal_quantity_weighing_unit_id')->nullable(true);
            $table->integer('storage_location_id')->nullable(true);            
            $table->integer('agency_id')->nullable(true);            
            $table->integer('district_id')->nullable(false);  
            $table->integer('certification_court_id')->nullable(true);
            $table->double('quantity_of_sample',6,3)->nullable(true);  
            $table->integer('sample_quantity_weighing_unit_id')->nullable(true); 
            $table->date('date_of_certification')->nullable(true);
            $table->string('certification_flag');
            $table->string('disposal_flag');
            $table->text('remarks')->nullable(true);
            $table->text('magistrate_remarks')->nullable(true);
            $table->string('user_name');
            $table->timestamps();
            
            $table->foreign('ps_id')->references('ps_id')->on('ps_details');
            $table->foreign('agency_id')->references('agency_id')->on('agency_details');            
            $table->foreign('seizure_quantity_weighing_unit_id')->references('unit_id')->on('units');
            $table->foreign('disposal_quantity_weighing_unit_id')->references('unit_id')->on('units');
            $table->foreign('sample_quantity_weighing_unit_id')->references('unit_id')->on('units');
            $table->foreign('storage_location_id')->references('storage_id')->on('storage_details');
            $table->foreign('district_id')->references('district_id')->on('districts');
            $table->foreign('certification_court_id')->references('court_id')->on('court_details');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seizures');
    }
}
