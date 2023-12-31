<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;
use App\Models\Review;
use App\Models\Booking;
use Illuminate\Auth\Notifications\ResetPassword;
class User extends \Classiebit\Eventmie\Models\User
{
    use HasApiTokens, HasFactory, Notifiable, Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the reviews for the user .
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the booking for the user .
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id', 'id');
    }

    public function sendPasswordResetNotification($token) 
    {   
        $url = route('eventmie.password.reset',[$token]);

        if(\Request::route()->getName() == 'api-password.email')
            $url = route('eventmie.password.reset',[$token]).'?api=true';

        
        // The trick is first to instantiate the notification itself
        $notification = new ResetPassword($token);
        // Then use the createUrlUsing method
        $notification->createUrlUsing(function ($token) use($url) {

            return $url;
        });

        // Then you pass the notification
        $this->notify($notification);
    }
    
}
