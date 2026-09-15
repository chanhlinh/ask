<?php
namespace App\Http\Controllers\Admin;
use App\Events\QuestionChanged; use App\Http\Controllers\Controller; use App\Models\Event; use App\Models\Question; use Illuminate\Http\Request; use Illuminate\Validation\Rule;
class QuestionModerationController extends Controller {
 public function index(Request $r,Event $event){$status=$r->string('status')->value();$query=$event->questions()->withCount('votes')->latest();if(in_array($status,[Question::PENDING,Question::APPROVED,Question::ANSWERED,Question::REJECTED],true))$query->where('status',$status);return ['questions'=>$query->limit(300)->get()->map(fn($q)=>$this->data($q))];}
 public function update(Request $r,Event $event,Question $question){abort_unless($question->event_id===$event->id,404);$status=$r->validate(['status'=>['required',Rule::in([Question::PENDING,Question::APPROVED,Question::ANSWERED,Question::REJECTED])]])['status'];$question->update(['status'=>$status]);QuestionChanged::dispatch($question->fresh());return ['question'=>$this->data($question->fresh())];}
 private function data(Question $q):array{return ['id'=>$q->id,'body'=>$q->body,'display_name'=>$q->is_anonymous?null:$q->display_name,'is_anonymous'=>$q->is_anonymous,'status'=>$q->status,'votes_count'=>(int)$q->votes_count,'created_at'=>$q->created_at?->toIso8601String()];}
}
