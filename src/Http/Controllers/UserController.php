<?php

declare(strict_types=1);

namespace Canvas\Http\Controllers;

use Canvas\Http\Requests\StoreUserRequest;
use Canvas\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controller;
use Ramsey\Uuid\Uuid;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $sortAscending = request()->query('sort', 'desc') === 'asc';
        $filterByRole = request()->query('role');

        return response()->json(
            User::query()
                ->select('id', 'name', 'email', 'avatar', 'role')
                ->when($filterByRole, function (Builder $query) {
                    return $query->where('role', request()->query('role'));
                }, function (Builder $query) {
                    return $query;
                })
                ->when($sortAscending, function (Builder $query) {
                    return $query->oldest();
                }, function (Builder $query) {
                    return $query->latest();
                })
                ->withCount('posts')
                ->paginate()
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return response()->json(User::query()->make([
            'id' => Uuid::uuid4()->toString(),
        ]));
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $id)
    {
        abort_unless(Uuid::isValid($id), 400);

        $user = User::query()->withCount('posts')->findOrFail($id);

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Canvas\Http\Requests\StoreUserRequest  $request
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request, string $id)
    {
        abort_unless(Uuid::isValid($id), 400);

        $user = User::query()->updateOrCreate(['id' => $id], $request->validated());

        return response()->json($user->refresh());
    }

    /**
     * Display the specified relationship.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function posts(string $id)
    {
        abort_unless(Uuid::isValid($id), 400);

        $user = User::query()->with('posts')->findOrFail($id);

        return response()->json($user->posts()->withCount('views')->paginate());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        abort_unless(Uuid::isValid($id), 400);

        // Prevent a user from deleting their own account
        if (request()->user('canvas')->id === $id) {
            return response()->json(null, 403);
        }

        $user = User::query()->findOrFail($id);

        $user->delete();

        return response()->json(null, 204);
    }
}
