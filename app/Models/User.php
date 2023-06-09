<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
    ];

    public function types() {
        return $this->hasMany(Type::class);
    }
    
    public function flavors() {
        return $this->hasMany(Flavor::class);
    }
    
    public function products() {
        return $this->hasMany(Product::class);
    }
    
    public function sales() {
        return $this->hasMany(Sale::class);
    }
    
    public function stocks() {
        return $this->hasMany(Stock::class);
    }

    public function expenses() {
        return $this->hasMany(Expense::class);
    }
}
