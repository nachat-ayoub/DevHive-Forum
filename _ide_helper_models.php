<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Answer
 *
 * @property int $id
 * @property string $body
 * @property int $user_id
 * @property int $question_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Question|null $question
 * @property-read \Illuminate\Foundation\Auth\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AnswerVote> $votes
 * @property-read int|null $votes_count
 * @method static \Database\Factories\AnswerFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Answer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Answer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Answer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Answer whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Answer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Answer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Answer whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Answer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Answer whereUserId($value)
 */
	class Answer extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\AnswerVote
 *
 * @property int $id
 * @property int $answer_id
 * @property int $user_id
 * @property int $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Answer $answer
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\AnswerVoteFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerVote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerVote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerVote query()
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerVote whereAnswerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerVote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerVote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerVote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerVote whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AnswerVote whereValue($value)
 */
	class AnswerVote extends \Eloquent {}
}

namespace App\Models{
/**
 * Class Question
 *
 * @package App\Models
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @property int $id
 * @property string $title
 * @property string $body
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Answer> $answers
 * @property-read int|null $answers_count
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\QuestionVote> $votes
 * @property-read int|null $votes_count
 * @method static \Database\Factories\QuestionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Question newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Question newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Question query()
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Question whereUserId($value)
 */
	class Question extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\QuestionVote
 *
 * @property int $id
 * @property int $question_id
 * @property int $user_id
 * @property int $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Question $question
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\QuestionVoteFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionVote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionVote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionVote query()
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionVote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionVote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionVote whereQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionVote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionVote whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuestionVote whereValue($value)
 */
	class QuestionVote extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Answer> $answers
 * @property-read int|null $answers_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Question> $questions
 * @property-read int|null $questions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

