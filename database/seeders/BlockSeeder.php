<?php

namespace Database\Seeders;

use App\Models\Block;
use App\Models\BlockVariable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlockSeeder extends Seeder
{
    public function run(): void
    {
        $blocks = require database_path('seeders/current_blocks.php');

        if (! is_array($blocks)) {
            throw new \RuntimeException('current_blocks.php must return an array');
        }

        DB::transaction(function () use ($blocks): void {
            BlockVariable::query()->delete();
            Block::query()->delete();

            foreach ($blocks as $blockData) {
                $block = Block::query()->create([
                    'name' => $blockData['name'],
                    'description' => $blockData['description'],
                    'blade_template' => $blockData['blade_template'],
                    'created_at' => $blockData['created_at'] ?? now(),
                    'updated_at' => $blockData['updated_at'] ?? now(),
                ]);

                $variables = $blockData['variables'] ?? [];

                foreach ($variables as $variableData) {
                    $block->variables()->create([
                        'name' => $variableData['name'],
                        'label' => $variableData['label'],
                        'type' => $variableData['type'],
                        'default_value' => $variableData['default_value'],
                        'required' => $variableData['required'] ?? false,
                        'created_at' => $variableData['created_at'] ?? now(),
                        'updated_at' => $variableData['updated_at'] ?? now(),
                    ]);
                }
            }
        });
    }
}
