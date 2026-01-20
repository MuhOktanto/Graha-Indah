<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin Abi Noval Fauzi',
            'code' => bin2hex(random_bytes(20)),
            'email' => 'admin@ijat.com',
            'email_verified_at' => now(),
            'password' => bcrypt('12345'),
            'phone_number' => '08174835153',
            'remember_token' => Str::random(10),
            'avatar' => 'img/avatar/a.png'
        ]);

        $admin->syncRoles('admin');
    }
}
