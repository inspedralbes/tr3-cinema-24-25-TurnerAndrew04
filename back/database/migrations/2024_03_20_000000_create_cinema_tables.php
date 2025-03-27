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
            $table->string('row'); // Asegurar que 'row' existe antes de usarlo en unique()
            $table->integer('number');
            $table->boolean('is_vip')->default(false);
            $table->boolean('is_occupied')->default(false);
            $table->timestamps();
        
            // Agregar la restricción única
            $table->unique(['row', 'number']);
        });

        // Schema::create('seats', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('cinema_session_id')->constrained()->onDelete('cascade'); // Relación con CinemaSession            $table->string('row');
        //     $table->integer('number');
        //     $table->boolean('isVip')->default(false);
        //     $table->boolean('isOccupied')->default(false);
        //     $table->unique(['row', 'number']);
        //     $table->unique(['cinema_session_id', 'row', 'number']); // Aseguramos que no haya asientos duplicados en la misma sesión
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