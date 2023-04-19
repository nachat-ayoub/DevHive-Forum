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

    protected $casts = [
        'total_votes' => 'integer',
    ];

    public function user() {
        return $this->hasOne(User::class);
    }

    public function answers() {
        return $this->hasMany(Answer::class);
    }

    public function votes() {
        return $this->hasMany(QuestionVote::class);
    }

    public function status() {
        return $this->hasOne(QuestionStatus::class);
    }

    public function incrementViews() {
        $this->increment('views');
    }

    // public function getQuestionDataAttribute() {
    //     $total_votes = $this->votes()->sum('value');
    //     $vote_count = $this->votes()->count();
    //     $answers_count = $this->answers()->count();
    //     $views = $this->views()->count();

    //     $data = $this->toArray();
    //     $data['total_votes'] = $total_votes;
    //     $data['vote_count'] = $vote_count;
    //     $data['answers_count'] = $answers_count;
    //     $data['views'] = $views;

    //     return $data;
    // }

}
