<?php

use App\Models\Block;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->createMissingSystemBlock(
            name: 'FRONT CSS',
            description: 'Системный layout-блок для пользовательских CSS-стилей публичной страницы. Вводите только CSS без тега <style>.',
            placement: Block::PLACEMENT_FRONT_CSS,
            defaultOrder: 4,
        );

        $this->createMissingSystemBlock(
            name: 'FRONT JS',
            description: 'Системный layout-блок для пользовательского JS публичной страницы. Вводите только JavaScript без тега <script>.',
            placement: Block::PLACEMENT_FRONT_JS,
            defaultOrder: 5,
        );
    }

    public function down(): void
    {
        $blockIds = DB::table('blocks')
            ->whereIn('placement', [
                Block::PLACEMENT_FRONT_CSS,
                Block::PLACEMENT_FRONT_JS,
            ])
            ->where('is_system', true)
            ->pluck('id');

        if ($blockIds->isEmpty()) {
            return;
        }

        DB::table('block_variables')
            ->whereIn('block_id', $blockIds)
            ->delete();

        DB::table('blocks')
            ->whereIn('id', $blockIds)
            ->delete();
    }

    private function createMissingSystemBlock(string $name, string $description, string $placement, int $defaultOrder): void
    {
        $exists = DB::table('blocks')
            ->where('placement', $placement)
            ->where('is_system', true)
            ->exists();

        if ($exists) {
            return;
        }

        $now = now();

        $blockId = DB::table('blocks')->insertGetId([
            'name' => $name,
            'description' => $description,
            'blade_template' => '',
            'placement' => $placement,
            'is_system' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('block_variables')->insert([
            [
                'block_id' => $blockId,
                'name' => 'order',
                'label' => $name,
                'type' => 'text',
                'default_value' => (string) $defaultOrder,
                'required' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'block_id' => $blockId,
                'name' => 'visibility',
                'label' => 'Видимость',
                'type' => 'boolean',
                'default_value' => '1',
                'required' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
};
