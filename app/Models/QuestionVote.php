<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class QuestionVote extends Model { public $timestamps=false; protected $fillable=['question_id','browser_hash']; public function question(): BelongsTo {return $this->belongsTo(Question::class);} }
