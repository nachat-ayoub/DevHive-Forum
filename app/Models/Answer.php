<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Answer extends Model {
    use HasFactory;

    protected $fillable = [
        'body', 'user_id', 'question_id',
    ];

    public function user() {
        return $this->hasOne(User::class);
    }

    public function question() {
        return $this->hasOne(Question::class);
    }

    public function votes() {
        return $this->hasMany(AnswerVote::class);
    }

}
