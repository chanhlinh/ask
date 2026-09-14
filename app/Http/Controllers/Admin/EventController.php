<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; use App\Models\Event; use Illuminate\Http\Request; use Illuminate\Support\Facades\Storage; use Illuminate\Validation\Rule;
class EventController extends Controller {
 public function index(){return Event::latest()->get()->map(fn($e)=>$this->data($e));}
 public function store(Request $r){$data=$this->validated($r);if($r->hasFile('logo')){$data['logo_path']=$r->file('logo')->store('event-logos','public');}$event=Event::create($data); return response()->json(['event'=>$this->data($event)],201);}
 public function update(Request $r,Event $event){$data=$this->validated($r,$event); if($r->hasFile('logo')){$this->deleteLogo($event);$data['logo_path']=$r->file('logo')->store('event-logos','public');}$event->update($data);return ['event'=>$this->data($event->fresh())];}
 public function destroy(Event $event){$this->deleteLogo($event);$event->delete();return response()->noContent();}
 public function urls(Event $event){$public=url('/e/'.$event->code); return ['public_url'=>$public,'screen_url'=>$public.'/screen','qr_url'=>url('/api/e/'.$event->code.'/qr')];}
 private function validated(Request $r,?Event $event=null):array{$code=['required','alpha_dash','min:3','max:64',Rule::unique('events','code')->ignore($event)];$data=$r->validate(['name'=>['required','string','max:160'],'code'=>$code,'accent_color'=>['required','regex:/^#[0-9A-Fa-f]{6}$/'],'is_active'=>['sometimes','boolean'],'moderation_enabled'=>['sometimes','boolean'],'anonymous_enabled'=>['sometimes','boolean'],'upvotes_enabled'=>['sometimes','boolean'],'show_names'=>['sometimes','boolean'],'logo'=>['nullable','image','mimes:png,jpg,jpeg,webp','max:2048']]);unset($data['logo']);return $data;}
 private function data(Event $e):array{return ['id'=>$e->id,'name'=>$e->name,'code'=>$e->code,'logo_url'=>$e->logo_url,'accent_color'=>$e->accent_color,'is_active'=>$e->is_active,'moderation_enabled'=>$e->moderation_enabled,'anonymous_enabled'=>$e->anonymous_enabled,'upvotes_enabled'=>$e->upvotes_enabled,'show_names'=>$e->show_names,'created_at'=>$e->created_at?->toIso8601String()];}
 private function deleteLogo(Event $e):void{if($e->logo_path)Storage::disk('public')->delete($e->logo_path);}
}
