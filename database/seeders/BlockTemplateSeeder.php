<?php

namespace Database\Seeders;

use App\Models\BlockTemplate;
use Illuminate\Database\Seeder;

class BlockTemplateSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Hero Split',
                'slug' => 'hero-split',
                'description' => 'Главный экран с большим заголовком и карточкой справа',
                'blade_template' => '<section class="relative overflow-hidden bg-zinc-950 px-6 py-24 text-white">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(249,115,22,0.22),_transparent_28%)]"></div>
    <div class="relative mx-auto grid max-w-7xl items-center gap-12 lg:grid-cols-2">
        <div>
            <p class="text-sm uppercase tracking-[0.35em] text-orange-300">Новый блок</p>
            <h1 class="mt-4 text-5xl font-semibold tracking-tight md:text-6xl">Сильный первый экран под продукт или услугу.</h1>
            <p class="mt-6 max-w-2xl text-lg leading-8 text-zinc-300">Используйте этот шаблон как основу для современного hero-блока в стартовой структуре сайта.</p>
            <div class="mt-8 flex flex-wrap gap-4">
                <a href="#contact" class="inline-flex rounded-2xl bg-orange-500 px-6 py-3 text-sm font-semibold text-white">Основной CTA</a>
                <a href="#about" class="inline-flex rounded-2xl border border-white/10 px-6 py-3 text-sm font-semibold text-zinc-200">Вторичный CTA</a>
            </div>
        </div>
        <div class="rounded-[28px] border border-white/10 bg-white/[0.04] p-6">
            <div class="rounded-[22px] border border-white/10 bg-zinc-900 p-6">
                <p class="text-sm text-zinc-400">Preview</p>
                <p class="mt-3 text-2xl font-semibold text-white">Карточка для скриншота, метрик или списка возможностей.</p>
            </div>
        </div>
    </div>
</section>',
            ],
            [
                'name' => 'Capabilities Grid',
                'slug' => 'capabilities-grid',
                'description' => 'Секция возможностей в 4 карточки',
                'blade_template' => '<section class="bg-zinc-900 px-6 py-24 text-white">
    <div class="mx-auto max-w-7xl">
        <div class="max-w-3xl">
            <p class="text-sm uppercase tracking-[0.35em] text-orange-300">Возможности</p>
            <h2 class="mt-4 text-4xl font-semibold tracking-tight">Покажите, что умеет ваш продукт или команда.</h2>
        </div>
        <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            <article class="rounded-[28px] border border-white/10 bg-white/[0.03] p-6">
                <p class="text-sm text-orange-300">01</p>
                <h3 class="mt-4 text-2xl font-semibold">Первая функция</h3>
                <p class="mt-3 text-sm leading-7 text-zinc-400">Короткое описание первой возможности.</p>
            </article>
            <article class="rounded-[28px] border border-white/10 bg-white/[0.03] p-6">
                <p class="text-sm text-orange-300">02</p>
                <h3 class="mt-4 text-2xl font-semibold">Вторая функция</h3>
                <p class="mt-3 text-sm leading-7 text-zinc-400">Короткое описание второй возможности.</p>
            </article>
            <article class="rounded-[28px] border border-white/10 bg-white/[0.03] p-6">
                <p class="text-sm text-orange-300">03</p>
                <h3 class="mt-4 text-2xl font-semibold">Третья функция</h3>
                <p class="mt-3 text-sm leading-7 text-zinc-400">Короткое описание третьей возможности.</p>
            </article>
            <article class="rounded-[28px] border border-white/10 bg-white/[0.03] p-6">
                <p class="text-sm text-orange-300">04</p>
                <h3 class="mt-4 text-2xl font-semibold">Четвёртая функция</h3>
                <p class="mt-3 text-sm leading-7 text-zinc-400">Короткое описание четвёртой возможности.</p>
            </article>
        </div>
    </div>
</section>',
            ],
            [
                'name' => 'Contact CTA',
                'slug' => 'contact-cta',
                'description' => 'Финальный призыв с формой связи',
                'blade_template' => '<section class="bg-zinc-950 px-6 py-24 text-white">
    <div class="mx-auto grid max-w-7xl gap-10 lg:grid-cols-[0.9fr_1.1fr]">
        <div>
            <p class="text-sm uppercase tracking-[0.35em] text-orange-300">Связаться</p>
            <h2 class="mt-4 text-4xl font-semibold tracking-tight">Подведите пользователя к заявке или запросу.</h2>
            <p class="mt-6 text-lg leading-8 text-zinc-400">Этот шаблон хорошо подходит для финального CTA-блока на странице.</p>
        </div>
        <div class="rounded-[32px] border border-white/10 bg-white/[0.03] p-8">
            <div class="grid gap-4 md:grid-cols-2">
                <input type="text" class="rounded-xl border border-white/10 bg-zinc-950 px-4 py-3 text-sm text-white" placeholder="Имя" />
                <input type="text" class="rounded-xl border border-white/10 bg-zinc-950 px-4 py-3 text-sm text-white" placeholder="Телефон" />
            </div>
            <textarea class="mt-4 min-h-[140px] w-full rounded-xl border border-white/10 bg-zinc-950 px-4 py-3 text-sm text-white" placeholder="Сообщение"></textarea>
            <button class="mt-4 inline-flex rounded-xl bg-orange-500 px-6 py-3 text-sm font-semibold text-white">Отправить</button>
        </div>
    </div>
</section>',
            ],
            [
                'name' => 'Testimonials',
                'slug' => 'testimonials',
                'description' => 'Секция отзывов в три карточки',
                'blade_template' => '<section class="bg-white px-6 py-24 text-zinc-900">
    <div class="mx-auto max-w-7xl">
        <div class="max-w-3xl">
            <p class="text-sm uppercase tracking-[0.35em] text-orange-500">Отзывы</p>
            <h2 class="mt-4 text-4xl font-semibold tracking-tight">Социальное доказательство в современном визуальном стиле.</h2>
        </div>
        <div class="mt-12 grid gap-6 lg:grid-cols-3">
            <article class="rounded-[28px] border border-zinc-200 bg-stone-50 p-6">
                <p class="text-base leading-8 text-zinc-600">«Здесь будет отзыв клиента о продукте или услуге.»</p>
            </article>
            <article class="rounded-[28px] border border-zinc-200 bg-stone-50 p-6">
                <p class="text-base leading-8 text-zinc-600">«Вторая карточка подтверждает ценность предложения.»</p>
            </article>
            <article class="rounded-[28px] border border-zinc-200 bg-stone-50 p-6">
                <p class="text-base leading-8 text-zinc-600">«Третья карточка закрывает блок и усиливает доверие.»</p>
            </article>
        </div>
    </div>
</section>',
            ],
        ];

        foreach ($templates as $template) {
            BlockTemplate::updateOrCreate(
                ['slug' => $template['slug']],
                $template
            );
        }
    }
}
