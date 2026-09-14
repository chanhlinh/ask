<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller; use Illuminate\Http\Request; use Illuminate\Support\Facades\Auth;
class AuthenticatedSessionController extends Controller { public function store(Request $r){$c=$r->validate(['email'=>['required','email'],'password'=>['required','string']]);if(!Auth::attempt($c,$r->boolean('remember'))){return response()->json(['message'=>'The supplied credentials are incorrect.'],422);} $r->session()->regenerate();abort_unless($r->user()->is_admin,403);return ['ok'=>true];} public function destroy(Request $r){Auth::logout();$r->session()->invalidate();$r->session()->regenerateToken();return response()->noContent();} }
