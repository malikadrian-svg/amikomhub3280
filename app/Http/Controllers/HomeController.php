<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Organization;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('events')->active()->get();

        $query = Event::with('category')
            ->withAvg('approvedReviews', 'rating')
            ->withCount('approvedReviews')
            ->whereIn('status', ['approved', 'active', 'published'])
            ->where('start_date', '>=', now())
            ->orderBy('start_date', 'asc');

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $events = $query->get();

        $organizations = Organization::where('status', 'approved')->latest()->get();

        return view('welcome', compact('events', 'categories', 'organizations'));
    }
}