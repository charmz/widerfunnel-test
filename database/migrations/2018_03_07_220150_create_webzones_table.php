<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebzonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webzones', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('zone_id');
            $table->decimal('paid_traffic', 5, 2);
            $table->bigInteger('monthly_unique_pageviews');
            $table->decimal('observed_conversion_rate', 5, 2);
            $table->integer('first_impressions');
            $table->integer('insight_potential');
            $table->integer('funnel_dropoff');
            $table->integer('development_ease');
            $table->integer('political_ease');
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
        Schema::dropIfExists('webzones');
    }
}
