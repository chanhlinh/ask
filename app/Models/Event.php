<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
class Event extends Model { protected $fillable=['name','code','logo_path','accent_color','is_active','moderation_enabled','anonymous_enabled','upvotes_enabled','show_names']; protected function casts(): array { return ['is_active'=>'boolean','moderation_enabled'=>'boolean','anonymous_enabled'=>'boolean','upvotes_enabled'=>'boolean','show_names'=>'boolean']; } public function questions(): HasMany { return $this->hasMany(Question::class); } public function getLogoUrlAttribute(): ?string { return $this->logo_path ? asset('storage/'.$this->logo_path) : null; } protected static function booted(): void { static::creating(function(self $event){$event->code ??= Str::lower(Str::random(8));}); } }
