<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'profile_photo',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
        'timezone',
        'language',
        'status',
        'notification_preferences',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
        'status' => 'string',
        'notification_preferences' => 'array',
        'last_login_at' => 'datetime',
        'trial_ends_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'full_name',
        'full_address',
    ];

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        if ($this->first_name && $this->last_name) {
            return "{$this->first_name} {$this->last_name}";
        }
        
        return $this->name;
    }

    /**
     * Get the user's first name.
     *
     * @return string|null
     */
    public function getFirstNameAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        if ($this->name) {
            $nameParts = explode(' ', $this->name, 2);
            return $nameParts[0];
        }
        
        return null;
    }

    /**
     * Get the user's last name.
     *
     * @return string|null
     */
    public function getLastNameAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        if ($this->name) {
            $nameParts = explode(' ', $this->name, 2);
            return $nameParts[1] ?? '';
        }
        
        return null;
    }

    /**
     * Set the user's name.
     *
     * @param string $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        
        if ($value) {
            $nameParts = explode(' ', $value, 2);
            $this->attributes['first_name'] = $nameParts[0];
            $this->attributes['last_name'] = $nameParts[1] ?? '';
        }
    }

    /**
     * Set the user's first name.
     *
     * @param string $value
     * @return void
     */
    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = $value;
        $this->updateNameFromParts();
    }

    /**
     * Set the user's last name.
     *
     * @param string $value
     * @return void
     */
    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = $value;
        $this->updateNameFromParts();
    }
    
    /**
     * Update the name field from first_name and last_name parts.
     *
     * @return void
     */
    protected function updateNameFromParts()
    {
        if (isset($this->attributes['first_name'])) {
            $name = $this->attributes['first_name'];
            
            if (isset($this->attributes['last_name']) && $this->attributes['last_name']) {
                $name .= ' ' . $this->attributes['last_name'];
            }
            
            $this->attributes['name'] = $name;
        }
    }

    /**
     * Get the user's full address.
     *
     * @return string|null
     */
    public function getFullAddressAttribute()
    {
        if (!$this->address_line1) {
            return null;
        }

        $address = $this->address_line1;
        
        if ($this->address_line2) {
            $address .= ", {$this->address_line2}";
        }
        
        $address .= ", {$this->city}, {$this->state} {$this->postal_code}";
        
        if ($this->country) {
            $address .= ", {$this->country}";
        }
        
        return $address;
    }

    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->is_admin || $this->hasRole('admin');
    }

    /**
     * Check if the user is active.
     *
     * @return bool
     */
    public function isActive()
    {
        return !$this->status || $this->status === 'active';
    }

    /**
     * Check if the user is a customer.
     *
     * @return bool
     */
    public function isCustomer()
    {
        return $this->hasRole('customer');
    }

    /**
     * Check if the user is a sales representative.
     *
     * @return bool
     */
    public function isSales()
    {
        return $this->hasRole('sales');
    }

    /**
     * Set the user's login timestamp.
     *
     * @return void
     */
    public function setLastLoginAt()
    {
        $this->last_login_at = now();
        $this->save();
    }

    /**
     * Get the formatted phone number.
     *
     * @return string|null
     */
    public function formattedPhone()
    {
        if (!$this->phone) {
            return null;
        }

        // Implement phone formatting logic based on country
        return $this->phone;
    }

    /**
     * Get all orders belonging to the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the user's subscription.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get all transcripts belonging to the user.
     */
    public function transcripts()
    {
        return $this->hasMany(Transcript::class);
    }
}
