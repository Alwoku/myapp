<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use AmoCRM\Exceptions\AmoCRMApiException;
use League\OAuth2\Client\Token\AccessTokenInterface;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        "client_id",
        "client_secret",
        "grant_type",
        "redirect_uri",
        "refresh_token",
        "access_token",
    ];

  
}
