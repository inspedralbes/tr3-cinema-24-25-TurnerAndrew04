<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@cinema.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create sample movies
        $movies = [
            [
                'id' => 'tt0468569',
                'title' => 'The Dark Knight',
                'year' => '2008',
                'poster' => 'https://m.media-amazon.com/images/M/MV5BMTMxNTMwODM0NF5BMl5BanBnXkFtZTcwODAyMTk2Mw@@._V1_SX300.jpg',
                'plot' => 'When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.',
                'duration' => '152 min',
            ],
            [
                'id' => 'tt0109830',
                'title' => 'Forrest Gump',
                'year' => '1994',
                'poster' => 'https://m.media-amazon.com/images/M/MV5BNWIwODRlZTUtY2U3ZS00Yzg1LWJhNzYtMmZiYmEyNmU1NjMzXkEyXkFqcGdeQXVyMTQxNzMzNDI@._V1_SX300.jpg',
                'plot' => 'The presidencies of Kennedy and Johnson, the Vietnam War, the Watergate scandal and other historical events unfold from the perspective of an Alabama man with an IQ of 75.',
                'duration' => '142 min',
            ],
        ];

        foreach ($movies as $movie) {
            DB::table('movies')->insert(array_merge($movie, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // Create seats
        $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'];
        foreach ($rows as $row) {
            for ($number = 1; $number <= 10; $number++) {
                DB::table('seats')->insert([
                    'row' => $row,
                    'number' => $number,
                    'is_vip' => $row === 'F',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Create some sample sessions
        $dates = ['2025-03-20', '2025-03-21', '2025-03-22'];
        $times = ['16:00', '18:00'];
        $movieIds = array_column($movies, 'id');
        
        foreach ($dates as $date) {
            foreach ($times as $index => $time) {
                DB::table('cinema_sessions')->insert([
                    'movie_id' => $movieIds[$index % count($movieIds)],
                    'date' => $date,
                    'time' => $time,
                    'is_special_day' => $index === 1, // Make the 18:00 sessions special days
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}