<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Question extends Model { public const PENDING='pending', APPROVED='approved', ANSWERED='answered', REJECTED='rejected'; protected $fillable=['event_id','body','display_name','is_anonymous','status','browser_hash']; protected $appends=['votes_count']; protected function casts(): array { return ['is_anonymous'=>'boolean']; } public function event(): BelongsTo {return $this->belongsTo(Event::class);} public function votes(): HasMany {return $this->hasMany(QuestionVote::class);} public function getVotesCountAttribute(): int { return (int)($this->attributes['votes_count'] ?? $this->votes()->count()); } }
