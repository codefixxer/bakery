<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            ['name'=>'super',   'email'=>'super@example.com',   'password'=>'password123'],
            ['name'=>'shop_user',   'email'=>'shop@example.com',   'password'=>'password123'],
            ['name'=>'lab_user',    'email'=>'lab@example.com',    'password'=>'password123'],
            ['name'=>'master_user', 'email'=>'master@example.com', 'password'=>'password123'],
            ['name'=>'admin_user',  'email'=>'admin@example.com',  'password'=>'password123'],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email'=>$data['email']],
                [
                   'name'     => $data['name'],
                   'password' => Hash::make($data['password']),
                ]
            );

            // sync exactly one role
            $user->syncRoles($data['role']);
        }
    }
}
