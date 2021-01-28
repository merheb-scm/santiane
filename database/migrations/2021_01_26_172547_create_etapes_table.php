<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtapesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voyage_id')
                ->constrained('voyages')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('type', 191);
            $table->string('transport_number', 191) ;
            $table->timestamp('departure_date') ;
            $table->timestamp('arrival_date') ;
            $table->string('departure', 255);
            $table->string('arrival', 255);
            $table->string('seat', 255)->nullable();
            $table->string('gate', 255)->nullable();
            $table->string('baggage_drop', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['voyage_id', 'departure'], 'voyage_departure_unique');
            $table->unique(['voyage_id', 'arrival'], 'voyage_arrival_unique');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('steps');
    }
}
