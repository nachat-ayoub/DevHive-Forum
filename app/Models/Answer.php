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

    protected $casts = [
        'total_votes' => 'integer',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function question() {
        return $this->belongsTo(Question::class);
    }

    public function votes() {
        return $this->hasMany(AnswerVote::class);
    }

}
