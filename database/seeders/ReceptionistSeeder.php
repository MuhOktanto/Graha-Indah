<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ReceptionistSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     *
     * @return void
     */
    public function run()
    {
        // Akun manual: resep@ijat.com
        $receptionist = User::create([
            'name' => 'Receptionist Ijat',
            'code' => bin2hex(random_bytes(20)),
            'email' => 'resep@ijat.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345'),
            'phone_number' => '081234567891',
            'remember_token' => Str::random(10),
            'avatar' => 'img/avatar/r.png'
        ]);

        $receptionist->syncRoles('receptionist');

        // Tambahkan receptionist random lainnya
        $faker = \Faker\Factory::create();

        for ($i = 1; $i < 50; $i++) {
            $receptionist = User::create([
                'name' => $faker->name(),
                'code' => bin2hex(random_bytes(20)),
                'email' => $faker->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password default
                'phone_number' => $faker->phoneNumber(),
                'remember_token' => Str::random(10),
                'avatar' => 'img/avatar/' . substr($faker->name(), 0, 1)  . '.png'
            ]);

            $receptionist->syncRoles('receptionist');
        }
    }
}
