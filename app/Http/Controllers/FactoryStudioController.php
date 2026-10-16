<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\FactoryStudioService;
use App\Services\TinkerSandboxService;
use Illuminate\Http\Request;

class FactoryStudioController extends Controller
{
    protected FactoryStudioService $factoryService;
    protected TinkerSandboxService $tinkerService;

    public function __construct(
        FactoryStudioService $factoryService,
        TinkerSandboxService $tinkerService
    ) {
        $this->factoryService = $factoryService;
        $this->tinkerService = $tinkerService;
    }

    /**
     * Generate fake data in batch via Web Factory Studio
     */
    public function generate(Request $request)
    {
        $request->validate([
            'count' => 'required|integer|min:1|max:1000',
            'state' => 'nullable|in:random,published,unpublished,popular',
            'category' => 'nullable|string',
            'date_range' => 'nullable|in:today,past_week,past_month,past_quarter,past_year',
        ]);

        $result = $this->factoryService->generate($request->all());

        if ($request->wantsJson()) {
            return response()->json($result);
        }

        return redirect()->route('posts.index')
            ->with('success', $result['message']);
    }

    /**
     * Web Tinker Console / Sandbox Code Execution
     */
    public function tinker(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $result = $this->tinkerService->execute($request->input('code'));

        return response()->json($result);
    }

    /**
     * 1-Click Database Reset to Baseline
     */
    public function reset(Request $request)
    {
        $result = $this->factoryService->resetToBaseline();

        if ($request->wantsJson()) {
            return response()->json($result);
        }

        return redirect()->route('posts.index')
            ->with('success', $result['message']);
    }

    /**
     * 1-Click Purge Fake Data Only
     */
    public function purge(Request $request)
    {
        $result = $this->factoryService->purge();

        if ($request->wantsJson()) {
            return response()->json($result);
        }

        return redirect()->route('posts.index')
            ->with('success', $result['message']);
    }

    /**
     * Live Chart.js Analytics API
     */
    public function analytics()
    {
        $data = $this->factoryService->getAnalyticsData();
        return response()->json($data);
    }

    /**
     * Printable Report / PDF View
     */
    public function exportPrint()
    {
        $posts = Post::with('user')->latest()->get();
        $analytics = $this->factoryService->getAnalyticsData();

        return view('posts.print', compact('posts', 'analytics'));
    }
}
