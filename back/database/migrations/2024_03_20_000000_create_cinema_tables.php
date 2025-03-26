<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->string('id')->primary(); // IMDB ID
            $table->string('title');
            $table->string('year');  // Cambié de string a year
            $table->string('poster');
            $table->text('plot');
            $table->string('duration');  // Cambié de string a integer para duración (en minutos)
            $table->timestamps();
        });

        Schema::create('cinema_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('movie_id');
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
            $table->date('date');
            $table->time('time');
            $table->boolean('is_special_day')->default(false);
            $table->timestamps();
        });

        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->string('row');
            $table->integer('number');
            $table->boolean('is_vip')->default(false);
            $table->unique(['row', 'number']);
            $table->timestamps();
        });

        // Schema::create('tickets', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('session_id')->constrained('cinema_sessions')->onDelete('cascade');
        //     $table->foreignId('seat_id')->constrained('seats')->onDelete('cascade');
        //     $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        //     $table->decimal('price', 8, 2);
        //     $table->string('customer_name');
        //     $table->string('customer_email');
        //     $table->string('customer_phone');
        //     $table->unique(['session_id', 'seat_id']); // Prevent double booking
        //     $table->timestamps();
        // });

        // Add is_admin column to users table
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('seats');
        Schema::dropIfExists('cinema_sessions');
        Schema::dropIfExists('movies');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }
}