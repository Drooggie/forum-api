<?php

namespace App\Models;

use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Topic extends Model
{

    use BelongsToUser;

    protected $fillable = ['title', 'user_id'];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
