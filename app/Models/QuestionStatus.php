<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionStatus extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id',
        'question_id',
        'answer_id',
        'answered',
    ];

    public function user() {
        return $this->hasOne(User::class);
    }

    public function question() {
        return $this->hasOne(Question::class);
    }

    public function correct_answer() {
        return $this->hasOne(Answer::class);
    }
}
