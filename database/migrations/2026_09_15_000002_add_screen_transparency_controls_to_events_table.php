<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('screen_card_transparent')->default(false)->after('screen_card_color');
            $table->unsignedTinyInteger('screen_overlay_opacity')->default(22)->after('screen_background_color');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['screen_card_transparent', 'screen_overlay_opacity']);
        });
    }
};
