<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\PageBlockService;
use App\Services\PageRenderService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndexController extends Controller
{
    public function __construct(
        private readonly PageBlockService $pageBlockService,
        private readonly PageRenderService $pageRenderService,
    ) {
    }

    /**
     * Display the user's profile form.
     */
    public function index(Request $request): View {
        $data = [
            'title' => 'WORKED',
            'background_color' => '#fff',
        ];

        $sortedBlocks = $this->pageBlockService->getSortedBlocks();
        $renderedBlock = $this->pageRenderService->render(
            $sortedBlocks,
            (bool) $request->user(),
            $data
        );

        $siteName = Setting::getValue('site_name', 'Супер сайт') ?? 'Супер сайт';
        $siteDescription = Setting::getValue('site_description', '') ?? '';

        return view('template.index', [
            'user' => $request->user(),
            'block_id' => 1,
            'site_name' => $siteName,
            'site_description' => $siteDescription,
            'title' => 'WORKED',
            'block' => $renderedBlock,
        ]);
    }
}
