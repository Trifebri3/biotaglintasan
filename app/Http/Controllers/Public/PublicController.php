<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\GreenAction;
use App\Models\School;
use App\Models\Tree;
use App\Services\Tree\TreeService;
use Inertia\Inertia;
use Inertia\Response;

class PublicController extends Controller
{
    public function __construct(
        protected TreeService $treeService
    ) {}

    public function welcome(): Response
    {
        $totalTrees = Tree::count();
        $adoptedTrees = Tree::where('status', 'adopted')->count();
        $totalSchools = School::where('is_active', true)->count();
        $totalActions = GreenAction::count();

        $featuredTrees = Tree::with('location')
            ->whereIn('status', ['verified', 'adopted', 'monitored'])
            ->limit(4)
            ->get();

        return Inertia::render('Public/Welcome', [
            'stats' => [
                'total_trees' => $totalTrees,
                'adopted_trees' => $adoptedTrees,
                'schools_count' => $totalSchools,
                'actions_count' => $totalActions,
            ],
            'featured_trees' => $featuredTrees,
        ]);
    }

    public function map(): Response
    {
        $trees = $this->treeService->getPublicTrees();

        return Inertia::render('Public/TreeMap', [
            'trees' => $trees,
        ]);
    }

    public function education(): Response
    {
        return Inertia::render('Public/EducationCenter');
    }

    public function about(): Response
    {
        return Inertia::render('Public/About');
    }
}
