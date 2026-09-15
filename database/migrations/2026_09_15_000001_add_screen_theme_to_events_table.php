<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->string('screen_background_path')->nullable()->after('logo_path');
            $table->string('screen_background_color', 7)->default('#123B5D')->after('accent_color');
            $table->string('screen_card_color', 7)->default('#FFFFFF')->after('screen_background_color');
            $table->string('screen_highlight_color', 7)->default('#C7A86B')->after('screen_card_color');
            $table->string('screen_text_color', 7)->default('#1F2933')->after('screen_highlight_color');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'screen_background_path',
                'screen_background_color',
                'screen_card_color',
                'screen_highlight_color',
                'screen_text_color',
            ]);
        });
    }
};
