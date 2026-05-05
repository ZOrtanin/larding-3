<?php

use App\Models\Block;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blocks', function (Blueprint $table) {
            $table->string('placement')->default(Block::PLACEMENT_CONTENT)->after('blade_template');
            $table->boolean('is_system')->default(false)->after('placement');
        });

        $this->createSystemLayoutBlocks();
    }

    public function down(): void
    {
        Schema::table('blocks', function (Blueprint $table) {
            $table->dropColumn(['placement', 'is_system']);
        });
    }

    private function createSystemLayoutBlocks(): void
    {
        $now = now();
        $definitions = [
            [
                'name' => 'HEAD HTML',
                'description' => 'Системный layout-блок для вставки пользовательского HTML в <head> публичной страницы.',
                'placement' => Block::PLACEMENT_HEAD,
            ],
            [
                'name' => 'BODY START HTML',
                'description' => 'Системный layout-блок для вставки HTML сразу после открытия <body> на публичной странице.',
                'placement' => Block::PLACEMENT_BODY_START,
            ],
            [
                'name' => 'BODY END HTML',
                'description' => 'Системный layout-блок для вставки HTML перед закрывающим </body> на публичной странице.',
                'placement' => Block::PLACEMENT_BODY_END,
            ],
            [
                'name' => 'FRONT CSS',
                'description' => 'Системный layout-блок для пользовательских CSS-стилей публичной страницы. Вводите только CSS без тега <style>.',
                'placement' => Block::PLACEMENT_FRONT_CSS,
            ],
            [
                'name' => 'FRONT JS',
                'description' => 'Системный layout-блок для пользовательского JS публичной страницы. Вводите только JavaScript без тега <script>.',
                'placement' => Block::PLACEMENT_FRONT_JS,
            ],
        ];

        foreach ($definitions as $index => $definition) {
            $exists = DB::table('blocks')
                ->where('placement', $definition['placement'])
                ->where('is_system', true)
                ->exists();

            if ($exists) {
                continue;
            }

            $blockId = DB::table('blocks')->insertGetId([
                'name' => $definition['name'],
                'description' => $definition['description'],
                'blade_template' => '',
                'placement' => $definition['placement'],
                'is_system' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('block_variables')->insert([
                [
                    'block_id' => $blockId,
                    'name' => 'order',
                    'label' => $definition['name'],
                    'type' => 'text',
                    'default_value' => (string) ($index + 1),
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
    }
};
