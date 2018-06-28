<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaratranslateTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laratranslate_translations',function (Blueprint $table){
            $table->increments('id');
            $table->string('column',128);
            $table->text('value');
            $table->string('language',32);
            $table->string('translatable_type',128);
            $table->integer('translatable_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('laratranslate_translations');
    }
}
