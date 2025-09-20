<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'poll_option_id',
        'email',
    ];

    /**
     * Get the poll option that owns the vote.
     */
    public function pollOption(): BelongsTo
    {
        return $this->belongsTo(PollOption::class);
    }

    /**
     * Get the poll through the poll option.
     */
    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class, 'poll_id', 'id')
                    ->through('pollOption');
    }

    /**
     * Scope to get votes by email.
     */
    public function scopeByEmail($query, string $email)
    {
        return $query->where('email', $email);
    }

    /**
     * Scope to get votes for a specific poll.
     */
    public function scopeForPoll($query, int $pollId)
    {
        return $query->whereHas('pollOption', function ($q) use ($pollId) {
            $q->where('poll_id', $pollId);
        });
    }

    /**
     * Check if an email has already voted for a specific poll.
     */
    public static function hasVotedForPoll(string $email, int $pollId): bool
    {
        return static::forPoll($pollId)->byEmail($email)->exists();
    }
}