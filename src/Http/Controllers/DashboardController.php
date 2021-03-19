<?php

declare(strict_types=1);

namespace Canvas\Http\Controllers;

use Canvas\Canvas;
use Canvas\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

final class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $posts = Post::query()
                     ->select('id')
                     ->published()
                     ->latest()
                     ->when(request()->query('scope', 'user') === 'all', function (Builder $query) {
                         return $query;
                     }, function (Builder $query) {
                         return $query->where('user_id', request()->user('canvas')->id);
                     })
                     ->with(['views' => function (HasMany $views) {
                         return $views->whereBetween('created_at', [
                             today()->subDays(30)->startOfDay()->toDateTimeString(),
                             today()->endOfDay()->toDateTimeString(),
                         ]);
                     }])
                     ->with(['visits' => function (HasMany $visits) {
                         return $visits->whereBetween('created_at', [
                             today()->subDays(30)->startOfDay()->toDateTimeString(),
                             today()->endOfDay()->toDateTimeString(),
                         ]);
                     }])
                     ->withCount(['views' => function (Builder $query) {
                         return $query->whereBetween('created_at', [
                             today()->subDays(30)->startOfDay()->toDateTimeString(),
                             today()->endOfDay()->toDateTimeString(),
                         ]);
                     }])
                     ->withCount(['visits' => function (Builder $query) {
                         return $query->whereBetween('created_at', [
                             today()->subDays(30)->startOfDay()->toDateTimeString(),
                             today()->endOfDay()->toDateTimeString(),
                         ]);
                     }])
                     ->get();

        return response()->json([
            'views' => $posts->sum('views_count'),
            'visits' => $posts->sum('visits_count'),
            'graph' => [
                'views' => Canvas::calculateTotalForDays($posts->pluck('views')->flatten())->toJson(),
                'visits' => Canvas::calculateTotalForDays($posts->pluck('visits')->flatten())->toJson(),
            ],
        ]);
    }
}
