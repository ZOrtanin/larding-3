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
        $headBlocks = $this->pageBlockService->getSortedBlocksByPlacement('head');
        $bodyStartBlocks = $this->pageBlockService->getSortedBlocksByPlacement('body_start');
        $bodyEndBlocks = $this->pageBlockService->getSortedBlocksByPlacement('body_end');
        $frontCssBlocks = $this->pageBlockService->getSortedBlocksByPlacement('front_css');
        $frontJsBlocks = $this->pageBlockService->getSortedBlocksByPlacement('front_js');
        $renderedBlock = $this->pageRenderService->render(
            $sortedBlocks,
            (bool) $request->user(),
            $data
        );
        $renderedHeadBlocks = $this->pageRenderService->renderRaw($headBlocks, $data);
        $renderedBodyStartBlocks = $this->pageRenderService->renderRaw($bodyStartBlocks, $data);
        $renderedBodyEndBlocks = $this->pageRenderService->renderRaw($bodyEndBlocks, $data);
        $renderedFrontCssBlocks = $this->pageRenderService->renderRaw($frontCssBlocks, $data);
        $renderedFrontJsBlocks = $this->pageRenderService->renderRaw($frontJsBlocks, $data);

        $siteName = Setting::getValue('site_name', 'Супер сайт') ?? 'Супер сайт';
        $siteDescription = Setting::getValue('site_description', '') ?? '';
        $editorBlocks = $this->pageBlockService->getEditorBlocks();

        return view('template.index', [
            'user' => $request->user(),
            'block_id' => 1,
            'site_name' => $siteName,
            'site_description' => $siteDescription,
            'title' => 'WORKED',
            'block' => $renderedBlock,
            'head_html' => $renderedHeadBlocks,
            'front_css' => $renderedFrontCssBlocks,
            'body_start_html' => $renderedBodyStartBlocks,
            'body_end_html' => $renderedBodyEndBlocks,
            'front_js' => $renderedFrontJsBlocks,
            'editorBlocks' => $editorBlocks,
        ]);
    }
}
