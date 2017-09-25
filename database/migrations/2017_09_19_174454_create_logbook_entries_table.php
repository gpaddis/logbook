<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogbookEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logbook_entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('patron_category_id');
            $table->dateTime('visited_at');
            $table->boolean('recorded_live')->default(false);

            $table->foreign('patron_category_id')
                ->references('id')->on('patron_categories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logbook_entries');
    }
}
