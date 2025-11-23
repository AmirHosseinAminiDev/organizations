<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $role = Role::firstOrCreate(
            ['name' => 'operator'],
            ['label' => 'کاربر اپراتور']
        );

//        User::firstOrCreate(
//            ['phone' => '09919986371'],
//            [
//                'name'          => 'اپراتور',
//                'last_name'     => 'سیستم',
//                'phone'         => '09120000000',
//                'national_code' => '1234567890',
//                'email'         => null,
//                'password'      => Hash::make('password'),
//                'role_id'       => $role->id,
//                'organization_id' => null, // اگر خواستی وصلش کنی تغییر بده
//            ]
//        );
    }
}
