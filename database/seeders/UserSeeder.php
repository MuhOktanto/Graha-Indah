<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     *
     * @return void
     */
    public function run()
    {
        // User manual user@ijat.com
        $user = User::create([
            'name' => 'User Ijat',
            'code' => bin2hex(random_bytes(20)),
            'email' => 'user@ijat.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'phone_number' => '081234567890',
            'remember_token' => Str::random(10),
            'avatar' => 'img/avatar/u.png'
        ]);

        $user->syncRoles('user');

        // Faker 199 tambahan
        $faker = \Faker\Factory::create();

        for ($i = 1; $i < 200; $i++) {
            $user = User::create([
                'name' => $faker->name(),
                'code' => bin2hex(random_bytes(20)),
                'email' => $faker->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password default
                'phone_number' => $faker->phoneNumber(),
                'remember_token' => Str::random(10),
                'avatar' => 'img/avatar/' . substr($faker->name(), 0, 1) . '.png'
            ]);

            $user->syncRoles('user');
        }
    }
}
