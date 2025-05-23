<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test that a user can view their profile.
     *
     * @return void
     */
    public function test_user_can_view_profile()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->get(route('admin.profile.show'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.profile.show');
        $response->assertViewHas('user', $user);
    }

    /**
     * Test that a user can view the profile edit page.
     *
     * @return void
     */
    public function test_user_can_view_edit_profile_page()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->get(route('admin.profile.edit'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.profile.edit');
        $response->assertViewHas('user', $user);
    }

    /**
     * Test that a user can update their profile.
     *
     * @return void
     */
    public function test_user_can_update_profile()
    {
        $user = User::factory()->create();
        
        $updatedData = [
            'first_name' => 'Updated',
            'last_name' => 'Name',
            'email' => $user->email,
            'phone' => '123-456-7890',
            'address_line1' => '123 Main St',
            'city' => 'Anytown',
            'state' => 'CA',
            'postal_code' => '12345',
            'country' => 'USA',
            'timezone' => 'America/New_York',
            'language' => 'en',
        ];
        
        $response = $this->actingAs($user)
            ->put(route('admin.profile.update'), $updatedData);
        
        $response->assertRedirect(route('admin.profile.show'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'first_name' => 'Updated',
            'last_name' => 'Name',
            'phone' => '123-456-7890',
        ]);
    }

    /**
     * Test that a user can view the password change page.
     *
     * @return void
     */
    public function test_user_can_view_password_change_page()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->get(route('admin.profile.edit-password'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.profile.password');
    }

    /**
     * Test that a user can change their password.
     *
     * @return void
     */
    public function test_user_can_change_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        
        $passwordData = [
            'current_password' => 'password123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];
        
        $response = $this->actingAs($user)
            ->put(route('admin.profile.update-password'), $passwordData);
        
        $response->assertRedirect(route('admin.profile.show'));
        $response->assertSessionHas('success');
        
        // Verify the password was changed
        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    /**
     * Test that a user cannot change password with incorrect current password.
     *
     * @return void
     */
    public function test_user_cannot_change_password_with_incorrect_current_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        
        $passwordData = [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ];
        
        $response = $this->actingAs($user)
            ->put(route('admin.profile.update-password'), $passwordData);
        
        $response->assertSessionHasErrors('current_password');
        
        // Verify the password was not changed
        $this->assertTrue(Hash::check('password123', $user->fresh()->password));
    }

    /**
     * Test that a user can view the notification preferences page.
     *
     * @return void
     */
    public function test_user_can_view_notification_preferences_page()
    {
        $user = User::factory()->create([
            'notification_preferences' => ['email_notifications' => true],
        ]);
        
        $response = $this->actingAs($user)
            ->get(route('admin.profile.edit-notifications'));
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.profile.notifications');
        $response->assertViewHas('preferences');
    }

    /**
     * Test that a user can update their notification preferences.
     *
     * @return void
     */
    public function test_user_can_update_notification_preferences()
    {
        $user = User::factory()->create();
        
        $preferences = [
            'notification_preferences' => [
                'email_notifications' => true,
                'marketing_emails' => false,
                'system_updates' => true,
                'new_features' => true,
            ],
        ];
        
        $response = $this->actingAs($user)
            ->put(route('admin.profile.update-notifications'), $preferences);
        
        $response->assertRedirect(route('admin.profile.show'));
        $response->assertSessionHas('success');
        
        $this->assertEquals($preferences['notification_preferences'], $user->fresh()->notification_preferences);
    }

    /**
     * Test that a user can upload a profile photo.
     *
     * @return void
     */
    public function test_user_can_upload_profile_photo()
    {
        Storage::fake('public');
        
        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('profile.jpg');
        
        $response = $this->actingAs($user)
            ->post(route('admin.profile.upload-photo'), [
                'profile_photo' => $file,
            ]);
        
        $response->assertRedirect(route('admin.profile.show'));
        $response->assertSessionHas('success');
        
        // Get the updated user
        $updatedUser = $user->fresh();
        
        // Check that the profile photo path was saved and the file exists
        $this->assertNotNull($updatedUser->profile_photo);
        Storage::disk('public')->assertExists($updatedUser->profile_photo);
    }
}
