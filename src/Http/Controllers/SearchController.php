<?php

declare(strict_types=1);

namespace Canvas\Http\Controllers;

use Canvas\Models\Post;
use Canvas\Models\Tag;
use Canvas\Models\Topic;
use Canvas\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;

final class SearchController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function posts(): JsonResponse
    {
        $updated = optional(Post::query()->latest()->first())->updated_at;

        $key = vsprintf('%s-%s-%s', [
            'posts',
            request()->user('canvas')->id,
            optional($updated)->timestamp ?? 0,
        ]);

        return Cache::remember($key, now()->addHours(4), function () {
            $posts = Post::query()
                         ->select('id', 'title')
                         ->when(request()->user('canvas')->isContributor, function (Builder $query) {
                             return $query->where('user_id', request()->user('canvas')->id);
                         }, function (Builder $query) {
                             return $query;
                         })
                         ->latest()
                         ->get()
                         ->map(function ($post) {
                             $post['name'] = $post->title;
                             $post['type'] = 'Post';
                             $post['route'] = 'edit-post';

                             return $post;
                         })
                         ->toArray();

            return response()->json($posts);
        });
    }

    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function tags(): JsonResponse
    {
        $updated = optional(Tag::query()->latest()->first())->updated_at;

        $key = vsprintf('%s-%s-%s', [
            'tags',
            request()->user('canvas')->id,
            optional($updated)->timestamp ?? 0,
        ]);

        return Cache::remember($key, now()->addHours(4), function () {
            $tags = Tag::query()
                       ->select('id', 'name')
                       ->latest()
                       ->get()
                       ->map(function ($tag) {
                           $tag['type'] = 'Tag';
                           $tag['route'] = 'edit-tag';

                           return $tag;
                       })
                       ->toArray();

            return response()->json($tags);
        });
    }

    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function topics(): JsonResponse
    {
        $updated = optional(Topic::query()->latest()->first())->updated_at;

        $key = vsprintf('%s-%s-%s', [
            'topics',
            request()->user('canvas')->id,
            optional($updated)->timestamp ?? 0,
        ]);

        return Cache::remember($key, now()->addHours(4), function () {
            $topics = Topic::query()
                           ->select('id', 'name')
                           ->latest()
                           ->get()
                           ->map(function ($topic) {
                               $topic['type'] = 'Topic';
                               $topic['route'] = 'edit-topic';

                               return $topic;
                           })
                           ->toArray();

            return response()->json($topics);
        });
    }

    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function users(): JsonResponse
    {
        $updated = optional(User::query()->latest()->first())->updated_at;

        $key = vsprintf('%s-%s-%s', [
            'users',
            request()->user('canvas')->id,
            optional($updated)->timestamp ?? 0,
        ]);

        return Cache::remember($key, now()->addHours(4), function () {
            $users = User::query()
                         ->select('id', 'name')
                         ->latest()
                         ->get()
                         ->map(function ($user) {
                             $user['type'] = 'User';
                             $user['route'] = 'edit-user';

                             return $user;
                         })
                         ->toArray();

            return response()->json($users);
        });
    }
}
