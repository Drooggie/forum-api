<?php

namespace App\Models;

use App\Traits\BelongsToUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{

    use BelongsToUser;

    protected $fillable = ['post', 'user_id', 'topic_id'];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
}
