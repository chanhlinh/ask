<?php
namespace App\Events;
use App\Models\Question;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
class QuestionChanged implements ShouldBroadcastNow { use Dispatchable, InteractsWithSockets, SerializesModels; public function __construct(public Question $question) {} public function broadcastOn(): array { return [new Channel('event.'.$this->question->event_id)]; } public function broadcastAs(): string { return 'question.changed'; } public function broadcastWith(): array { return ['question'=>['id'=>$this->question->id,'body'=>$this->question->body,'display_name'=>$this->question->is_anonymous ? null : $this->question->display_name,'is_anonymous'=>$this->question->is_anonymous,'status'=>$this->question->status,'votes_count'=>$this->question->votes()->count(),'created_at'=>$this->question->created_at?->toIso8601String()]]; } }
