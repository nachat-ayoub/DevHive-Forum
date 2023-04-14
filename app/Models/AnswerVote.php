<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnswerVote extends Model {
    use HasFactory;

    protected $fillable = [
        'answer_id',
        'user_id',
        'value',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function answer() {
        return $this->belongsTo(Answer::class);
    }
}
