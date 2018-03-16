<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create( 'media', function(Blueprint $table) {

            //https://github.com/cipemotion/medialibrary

            $table->increments( 'id' );

            $table->string( 'path' );

            $table->string( 'slug' )->unique();

            $table->string( 'directory_path' );

            $table->string( 'extension', 32 );

            $table->string( 'mime_type', 128 ); // image/jpg, image/png

            $table->string( 'file_type', 128 ); // image , video, document

            $table->json( 'variations' );

            $table->json( 'properties' )->nullable();

            $table->string( 'storage_disk' );

            $table->unsignedInteger( 'uploaded_by' );
            $table->foreign( 'uploaded_by' )->references( 'id' )->on( 'users' );

            $table->timestamps();

        } );


        Schema::create( 'mediaables', function(Blueprint $table) {

            $table->unsignedInteger( 'media_id' );
            $table->foreign('media_id')->references('id')->on('media')->onDelete('cascade');

            $table->unsignedInteger( 'mediaable_id' );

            $table->string( 'mediaable_type' );

        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists( 'mediaables' );
        Schema::dropIfExists( 'media' );
        Schema::enableForeignKeyConstraints();
    }
}
