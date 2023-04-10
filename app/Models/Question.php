<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Question
 * @package App\Models
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Question extends Model {
    use HasFactory;

    protected $fillable = [
        'title', 'body', 'user_id',
    ];

    public function user() {
        return $this->hasOne(User::class);
    }

    public function answers() {
        return $this->hasMany(Answer::class);
    }
}
