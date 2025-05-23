<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '+1234567890',
            'address_line1' => '123 Admin St',
            'city' => 'San Francisco',
            'state' => 'CA',
            'postal_code' => '94105',
            'country' => 'US',
            'timezone' => 'America/Los_Angeles',
            'status' => 'active',
            'notification_preferences' => json_encode(['email' => true, 'sms' => false]),
            'is_admin' => true,
        ]);
        $admin->assignRole('admin');

        // Create sales user
        $sales = User::create([
            'first_name' => 'Sales',
            'last_name' => 'Representative',
            'email' => 'sales@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '+1987654321',
            'address_line1' => '456 Sales Ave',
            'city' => 'New York',
            'state' => 'NY',
            'postal_code' => '10001',
            'country' => 'US',
            'timezone' => 'America/New_York',
            'status' => 'active',
            'notification_preferences' => json_encode(['email' => true, 'sms' => true]),
            'is_admin' => false,
        ]);
        $sales->assignRole('sales');

        // Create customer user
        $customer = User::create([
            'first_name' => 'Customer',
            'last_name' => 'User',
            'email' => 'customer@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '+1122334455',
            'address_line1' => '789 Customer Blvd',
            'address_line2' => 'Apt 101',
            'city' => 'Chicago',
            'state' => 'IL',
            'postal_code' => '60601',
            'country' => 'US',
            'timezone' => 'America/Chicago',
            'status' => 'active',
            'notification_preferences' => json_encode(['email' => true, 'sms' => false]),
            'is_admin' => false,
        ]);
        $customer->assignRole('customer');

        // Create editor user
        $editor = User::create([
            'first_name' => 'Editor',
            'last_name' => 'User',
            'email' => 'editor@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'phone' => '+1555666777',
            'address_line1' => '321 Editor St',
            'city' => 'Seattle',
            'state' => 'WA',
            'postal_code' => '98101',
            'country' => 'US',
            'timezone' => 'America/Los_Angeles',
            'status' => 'active',
            'notification_preferences' => json_encode(['email' => true, 'sms' => false]),
            'is_admin' => false,
        ]);
        $editor->assignRole('editor');

        // Generate 10 random customer users for testing
        \App\Models\User::factory(10)->create()->each(function ($user) {
            $user->assignRole('customer');
        });

        $this->command->info('Users have been created successfully!');
    }
}
