<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'password',
        'avatar'
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function setPasswordAttribute($value)  // Hashing incoming password
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function findForPassport(string $username): User  // Laravel/Passport username customisation.
    {
        return $this->where('username', $username)->first();
    }

    public function avatar(): string
    {
        return 'https://www.gravatar.com/avatar/' . md5($this->username) . '?s=40&=d=mm';
    }

    public function ownsThisTopic(Topic $topic): bool
    {
        return $this->id === $topic->user->id;
    }

    public function ownsThisPost(Post $post): bool
    {
        return $this->id === $post->user->id;
    }
}
