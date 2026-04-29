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
                'name' => 'Основной банер',
                'slug' => 'hero-split',
                'description' => 'Главный экран с большим заголовком и карточкой справа',
                'blade_template' => '<div class="flex w-full flex-col items-center justify-center bg-dark dark:bg-orange-600 p-8 h-[400px]" >
                                      <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 fill-current text-orange-100" viewBox="0 0 512 512"><rect x="32" y="48" width="448" height="416" rx="48" ry="48" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M96 112l80 64-80 64M192 240h64"/></svg>
                                      </div>

                                      <div class="mt-8 text-center">
                                        <h1 class="text-7xl font-bold text-orange-100">Привет мир.</h1>
                                        <p class="mx-auto mt-4 lg:w-1/2 text-gray-50">Вы успешно установили Larding. Теперь вы можете редактировать этот текст через панель управления.</p>
                                      </div>

                                      <button class="mt-8 block rounded-lg border border-orange-700 bg-orange-600 py-1.5 px-4 font-medium text-white transition-colors hover:bg-orange-700 active:bg-orange-800 disabled:opacity-50">Начать</button>

                                      <!--<button class="mt-2 block rounded-lg bg-transparent py-1.5 px-4 font-medium text-blue-50 hover:text-blue-700 transition-colors hover:bg-gray-100 active:bg-gray-200 disabled:opacity-50">Узнать больше ?</button>-->
                                    </div>',
            ],
            [
                'name' => 'Карточки',
                'slug' => 'capabilities-grid',
                'description' => 'Секция возможностей в 4 карточки',
                'blade_template' => '<div class="flex w-full bg-dark dark:bg-gray-900 mx-auto px-6 py-12" >
                                      <div class="container mx-auto px-6 py-12">
                                          <!-- Заголовок блока -->
                                          <h2 class="text-3xl font-bold text-center mb-12 text-orange-600">На что способен larding</h2>

                                          <!-- Сетка с 4 карточками -->
                                          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                              <!-- Карточка 1 -->
                                              <button class="p-8 border border-gray-200 rounded-lg bg-white w-full hover:bg-gray-50 hover:border-b-4 hover:border-b-orange-500 active:bg-gray-100 transition-all duration-200">
                                                  <div class="flex justify-center items-center text-gray-500">
                                                      <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 512 512">
                                                            <path fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" d="M160 336V48l32 16 32-16 31.94 16 32.37-16L320 64l31.79-16 31.93 16L416 48l32.01 16L480 48v224"/>
                                                            <path d="M480 272v112a80 80 0 01-80 80h0a80 80 0 01-80-80v-48H48a15.86 15.86 0 00-16 16c0 64 6.74 112 80 112h288" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/>
                                                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M224 144h192M288 224h128"/>
                                                        </svg>
                                                      
                                                  </div>
                                                  <div class="text-center mt-4 h-40">
                                                      <h3 class="font-bold text-gray-700">Одностаничник</h3>
                                                      <p class="text-gray-500 text-sm mt-2">
                                                          интуитивный редактор, готовые блоки и адаптивный дизайн — всё, чтобы быстро запустить эффективную посадочную страницу без лишних сложностей.
                                                      </p>
                                                  </div>
                                              </button>

                                              <!-- Карточка 2 -->
                                              <button class="p-8 border border-gray-200 rounded-lg bg-white w-full hover:bg-gray-50 hover:border-b-4 hover:border-b-orange-500 active:bg-gray-100 transition-all duration-200">
                                                  <div class="flex justify-center items-center text-gray-500">                      
                                                      <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 512 512">
                                                        <circle cx="176" cy="416" r="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>
                                                        <circle cx="400" cy="416" r="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>
                                                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M48 80h64l48 272h256"/>
                                                        <path d="M160 288h249.44a8 8 0 007.85-6.43l28.8-144a8 8 0 00-7.85-9.57H128" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>
                                                    </svg>
                                                  </div>
                                                  <div class="text-center mt-4 h-40">
                                                      <h3 class="font-bold text-gray-700">Мини-магазин</h3>
                                                      <p class="text-gray-500 text-sm mt-2">
                                                          управление товарами, корзина, интеграция с платежными системами и минималистичный дизайн, чтобы сосредоточиться на продажах, а не на технических деталях.
                                                      </p>
                                                  </div>
                                              </button>

                                              <!-- Карточка 3 -->
                                              <button class="p-8 border border-gray-200 rounded-lg bg-white w-full hover:bg-gray-50 hover:border-b-4 hover:border-b-orange-500 active:bg-gray-100 transition-all duration-200">
                                                  <div class="flex justify-center items-center text-gray-500">
                                                      <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 512 512">
                                                        <rect x="32" y="128" width="448" height="320" rx="48" ry="48" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/>
                                                        <path d="M144 128V96a32 32 0 0132-32h160a32 32 0 0132 32v32M480 240H32M320 240v24a8 8 0 01-8 8H200a8 8 0 01-8-8v-24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/>
                                                    </svg>
                                                  </div>
                                                  <div class="text-center mt-4 h-40">
                                                      <h3 class="font-bold text-gray-700">Портфолио</h3>
                                                      <p class="text-gray-500 text-sm mt-2">
                                                          удобная загрузка работ, гибкие галереи, адаптивные шаблоны и возможность быстро обновить портфолио, чтобы всегда демонстрировать актуальные проекты.
                                                      </p>
                                                  </div>
                                              </button>

                                              <!-- Карточка 4 -->
                                              <button class="p-8 border border-gray-200 rounded-lg bg-white w-full hover:bg-gray-50 hover:border-b-4 hover:border-b-orange-500 active:bg-gray-100 transition-all duration-200">
                                                  <div class="flex justify-center items-center text-gray-500 fill-gray-500">
                                                      <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 512 512">
                                                        <path d="M368 415.86V72a24.07 24.07 0 00-24-24H72a24.07 24.07 0 00-24 24v352a40.12 40.12 0 0040 40h328" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/>
                                                        <path d="M416 464h0a48 48 0 01-48-48V128h72a24 24 0 0124 24v264a48 48 0 01-48 48z" 
                                                        fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32"/>
                                                        <path d="M240 128h64M240 192h64M112 256h192M112 320h192M112 384h192" 
                                                        fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                                                        <path d="M176 208h-64a16 16 0 01-16-16v-64a16 16 0 0116-16h64a16 16 0 0116 16v64a16 16 0 01-16 16z"
                                                        fill="text-gray-500" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="0"/>
                                                    </svg>
                                                  </div>
                                                  <div class="text-center mt-4 h-40">
                                                      <h3 class="font-bold text-gray-700">Блог</h3>
                                                      <p class="text-gray-500 text-sm mt-2 ">
                                                          простой редактор статей, поддержка тегов, комментариев и SEO-оптимизация, чтобы сосредоточиться на контенте, а не на вёрстке.
                                                      </p>
                                                  </div>
                                              </button>
                                          </div>
                                      </div>
                                    </div>',
            ],
            [
                'name' => 'Форма обратной связи',
                'slug' => 'contact-cta',
                'description' => 'Финальный призыв с формой связи',
                'blade_template' => '<div class="flex w-full bg-dark dark:bg-gray-800 mx-auto px-6 py-12 " >
                                        <div class="container mx-auto px-6 py-12">
                                            <div class="p-8 rounded">
                                             

                                              <form class="lead-form grid grid-cols-6 gap-4"  method="POST" action="/leads">
                                                <input type="hidden" name="name_form" value="Форма обратной связи" />
                                                <input type="hidden" name="block_id" value="{{ $block_id }}" />

                                                <div class="col-span-2 col-start-2 py-4">
                                                    <h1 class="font-medium text-3xl text-orange-600 ">Связаться</h1>
                                                    <p class="text-gray-400 mt-6 ">Ваши идеи и замечания делают нашу CMS лучше! Поделитесь мыслями — мы внимательно изучаем каждый отзыв.</p>
                                                </div>
                                                <div class="col-span-2  grid grid-cols-2 gap-4 ">
                                                        <div class="">
                                                            <label for="name" class="text-sm text-gray-400 block mb-1 font-medium">Имя</label>
                                                            <input type="text" name="name" id="name" class="bg-gray-100 border border-gray-200 rounded py-1 px-3 block focus:ring-orange-500 focus:border-orange-500 text-gray-700 w-full" placeholder="Как к вам обращатся?" />
                                                        </div>
                                                        <div>
                                                            <label for="email" class="text-sm text-gray-400 block mb-1 font-medium">Телефон</label>
                                                            <input type="text" name="phone" id="email" class="bg-gray-100 border border-gray-200 rounded py-1 px-3 block focus:ring-orange-500 focus:border-orange-600 text-gray-700 w-full" placeholder="+7" />
                                                        </div>
                                                        <div class="col-span-2 ">
                                                            <label for="job" class="text-sm text-gray-400 block mb-1 font-medium">Текст</label>
                                                            <textarea type="text" name="job" id="job" row="12" class="bg-gray-100 border border-gray-200 rounded py-1 px-3 block focus:ring-orange-500 focus:border-orange-600 text-gray-700 w-full min-h-[50px]" placeholder="Ваше сообщение"/></textarea>
                                                        </div>
                                                        <div class="col-span-2 flex justify-end gap-4 py-3"> 
                                                        <p class="lead-form-message"></p>
                                                          <button type="submit" class="py-2 px-9 bg-orange-500 text-white rounded hover:bg-orange-600 active:bg-orange-700 disabled:opacity-50">Отправить</button>
                                                        
                                                          <!-- Secondary -->
                                                          <!-- <button class="py-2 px-4 bg-white border border-gray-200 text-gray-600 rounded hover:bg-gray-100 active:bg-gray-200 disabled:opacity-50">Cancel</button> -->
                                                      
                                                        </div>

                                                </div>

                                                
                                              </form>
                                            </div>
                                        </div>
                                    </div>',
            ],
            [
                'name' => 'Отзывы',
                'slug' => 'testimonials',
                'description' => 'Секция отзывов в три карточки',
                'blade_template' => '<div class="flex w-full bg-dark dark:bg-gray-50 mx-auto px-6 py-12" style="">
                                        <div class="container bg-dark dark:bg-gray-50 mx-auto px-6 py-12">
                                            <!-- Заголовок блока -->
                                            <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Отзывы</h2>

                                            <!-- Сетка с 4 карточками -->
                                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                                <!-- Карточка 1 -->
                                                <article class="p-8 w-full hover:bg-gray-200 transition-all duration-200">
                                                  <time class="text-sm/6 text-gray-400">Март 10, 2024</time>
                                                  <h2 class="font-semibold">Boost your conversion rate</h2>
                                                  <p class="line-clamp-3">
                                                    Nulla dolor velit adipisicing duis excepteur esse in duis nostrud occaecat mollit incididunt deserunt sunt. Ut ut
                                                    sunt laborum ex occaecat eu tempor labore enim adipisicing minim ad. Est in quis eu dolore occaecat excepteur fugiat
                                                    dolore nisi aliqua fugiat enim ut cillum. Labore enim duis nostrud eu. Est ut eiusmod consequat irure quis deserunt
                                                    ex. Enim laboris dolor magna pariatur. Dolor et ad sint voluptate sunt elit mollit officia ad enim sit consectetur
                                                    enim.
                                                  </p>

                                                  <div class="relative mt-8 flex items-center gap-x-4 justify-self-end">
                                                      <div class="row-span-2 rounded-full bg-cover bg-center bg-[url(http://localhost:8001/img/rev01.jpg)] h-24 w-24"> </div>
                                                      <div class="text-sm/6">
                                                        <p class="font-semibold text-gray-500">
                                                          <a href="#">
                                                            <span class="absolute inset-0"></span>
                                                            Michael Foster
                                                          </a>
                                                        </p>
                                                        <p class="text-gray-400">Co-Founder / CTO</p>
                                                      </div>
                                                    </div>
                                                </article>

                                                <!-- Карточка 2 -->
                                                
                                                <article class="p-8 w-full hover:bg-gray-200 transition-all duration-200">
                                                  <time class="text-sm/6 text-gray-400">Март 10, 2024</time>
                                                  <h2 class="font-semibold">Boost your conversion rate</h2>
                                                  <p class="line-clamp-3">
                                                    Nulla dolor velit adipisicing duis excepteur esse in duis nostrud occaecat mollit incididunt deserunt sunt. Ut ut
                                                    sunt laborum ex occaecat eu tempor labore enim adipisicing minim ad. Est in quis eu dolore occaecat excepteur fugiat
                                                    dolore nisi aliqua fugiat enim ut cillum. Labore enim duis nostrud eu. Est ut eiusmod consequat irure quis deserunt
                                                    ex. Enim laboris dolor magna pariatur. Dolor et ad sint voluptate sunt elit mollit officia ad enim sit consectetur
                                                    enim.
                                                  </p>

                                                  <div class="relative mt-8 flex items-center gap-x-4 justify-self-end">
                                                      <div class="row-span-2 rounded-full bg-cover bg-center bg-[url(http://localhost:8001/img/rev02.jpg)] h-24 w-24"> </div>
                                                      <div class="text-sm/6">
                                                        <p class="font-semibold text-gray-500">
                                                          <a href="#">
                                                            <span class="absolute inset-0"></span>
                                                            Michael Foster
                                                          </a>
                                                        </p>
                                                        <p class="text-gray-400">Co-Founder / CTO</p>
                                                      </div>
                                                    </div>
                                                </article>

                                                

                                                <!-- Карточка 3 -->
                                                <article class="p-8 w-full hover:bg-gray-200 transition-all duration-200">
                                                  <time class="text-sm/6 text-gray-400">Март 10, 2024</time>
                                                  <h2 class="font-semibold">Boost your conversion rate</h2>
                                                  <p class="line-clamp-3">
                                                    Nulla dolor velit adipisicing duis excepteur esse in duis nostrud occaecat mollit incididunt deserunt sunt. Ut ut
                                                    sunt laborum ex occaecat eu tempor labore enim adipisicing minim ad. Est in quis eu dolore occaecat excepteur fugiat
                                                    dolore nisi aliqua fugiat enim ut cillum. Labore enim duis nostrud eu. Est ut eiusmod consequat irure quis deserunt
                                                    ex. Enim laboris dolor magna pariatur. Dolor et ad sint voluptate sunt elit mollit officia ad enim sit consectetur
                                                    enim.
                                                  </p>

                                                  <div class="relative mt-8 flex items-center gap-x-4 justify-self-end">
                                                      <div class="row-span-2 rounded-full bg-cover bg-center bg-[url(http://localhost:8001/img/rev03.jpg)] h-24 w-24"> </div>
                                                      <div class="text-sm/6">
                                                        <p class="font-semibold text-gray-500">
                                                          <a href="#">
                                                            <span class="absolute inset-0"></span>
                                                            Michael Foster
                                                          </a>
                                                        </p>
                                                        <p class="text-gray-400">Co-Founder / CTO</p>
                                                      </div>
                                                    </div>
                                                </article>

                                                <!-- Карточка 4 -->
                                                <article class="p-8 w-full hover:bg-gray-200 transition-all duration-200">
                                                  <time class="text-sm/6 text-gray-400">Март 10, 2024</time>
                                                  <h2 class="font-semibold">Boost your conversion rate</h2>
                                                  <p class="line-clamp-3">
                                                    Nulla dolor velit adipisicing duis excepteur esse in duis nostrud occaecat mollit incididunt deserunt sunt. Ut ut
                                                    sunt laborum ex occaecat eu tempor labore enim adipisicing minim ad. Est in quis eu dolore occaecat excepteur fugiat
                                                    dolore nisi aliqua fugiat enim ut cillum. Labore enim duis nostrud eu. Est ut eiusmod consequat irure quis deserunt
                                                    ex. Enim laboris dolor magna pariatur. Dolor et ad sint voluptate sunt elit mollit officia ad enim sit consectetur
                                                    enim.
                                                  </p>

                                                  <div class="relative mt-8 flex items-center gap-x-4 justify-self-end">
                                                      <div class="row-span-2 rounded-full bg-cover bg-center bg-[url(http://localhost:8001/img/rev01.jpg)] h-24 w-24"> </div>
                                                      <div class="text-sm/6">
                                                        <p class="font-semibold text-gray-500">
                                                          <a href="#">
                                                            <span class="absolute inset-0"></span>
                                                            Michael Foster
                                                          </a>
                                                        </p>
                                                        <p class="text-gray-400">Co-Founder / CTO</p>
                                                      </div>
                                                    </div>
                                                </article>
                                            </div>
                                        </div>
                                    </div>',
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
