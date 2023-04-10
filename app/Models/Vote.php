<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model {
    use HasFactory;

    protected $fillable = [
        'value',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function answer() {
        return $this->belongsTo(Answer::class);
    }

    public function question() {
        return $this->belongsTo(Question::class);
    }

    public function scopeForQuestion($query, $question_id) {
        return $query->where('question_id', $question_id)->whereNull('answer_id');
    }

    public function scopeForAnswer($query, $answer_id) {
        return $query->where('answer_id', $answer_id)->whereNull('question_id');
    }

    public function setAnswer($answer_id) {
        $this->answer_id = $answer_id;
        $this->question_id = null;
    }

    public function setQuestion($question_id) {
        $this->question_id = $question_id;
        $this->answer_id = null;
    }

    public function save(array $options = []) {
        if (is_null($this->question_id) && is_null($this->answer_id)) {
            throw new \Exception('A vote must belong to either a question or an answer.');
        }
        return parent::save($options);
    }
}
