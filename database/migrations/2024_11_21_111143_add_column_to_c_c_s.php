<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_c_s', function (Blueprint $table) {
            
            $table->json('impact_on')->nullable(); // Store the first group of checkboxes
            $table->json('impact_on_facility')->nullable(); // Store the second group of checkboxes
            $table->json('impact_on_documents')->nullable(); // Store the third group of checkboxes
            $table->string('risk_assessment'); // Store 'Yes' or 'No'
            $table->text('risk_justification')->nullable(); // Justification if 'No' is selected
            $table->text('others')->nullable(); // Textarea for "Others"

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('c_c_s', function (Blueprint $table) {
            //
        });
    }
};
