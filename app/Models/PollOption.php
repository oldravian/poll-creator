<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PollOption extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'poll_id',
        'option_text',
    ];

    /**
     * Get the poll that owns the option.
     */
    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    /**
     * Get the votes for the poll option.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Get the vote count for this option.
     */
    public function getVoteCountAttribute(): int
    {
        return $this->votes()->count();
    }

    /**
     * Get the vote percentage for this option.
     */
    public function getVotePercentageAttribute(): float
    {
        $totalVotes = $this->poll->total_votes;
        
        if ($totalVotes === 0) {
            return 0;
        }

        return round(($this->vote_count / $totalVotes) * 100, 2);
    }

    /**
     * Check if a specific email has voted for this option.
     */
    public function hasVoteFromEmail(string $email): bool
    {
        return $this->votes()->where('email', $email)->exists();
    }
}