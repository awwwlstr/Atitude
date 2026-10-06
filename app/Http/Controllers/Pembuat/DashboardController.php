<?php

namespace App\Http\Controllers\Pembuat;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $creator = Auth::user();

        $materials = Material::where('creator_id', $creator->id);

        $stats = [
            'total' => (clone $materials)->count(),
            'draft' => (clone $materials)->where('status', 'draft')->count(),
            'pending' => (clone $materials)->where('status', 'pending')->count(),
            'approved' => (clone $materials)->whereIn('status', ['approved', 'published'])->count(),
            'revision' => (clone $materials)->where('status', 'revision')->count(),
        ];

        $recentMaterials = Material::where('creator_id', $creator->id)
            ->with(['category'])
            ->latest('updated_at')
            ->take(6)
            ->get();

        $revisionMaterials = Material::where('creator_id', $creator->id)
            ->where('status', 'revision')
            ->with(['category'])
            ->latest('updated_at')
            ->get();

        return view('pembuat.dashboard', compact('stats', 'recentMaterials', 'revisionMaterials'));
    }
}
