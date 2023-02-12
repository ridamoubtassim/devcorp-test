<?php

namespace App\Http\Controllers;

use App\Helpers\ListingTrait;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    use ListingTrait;

    /**
     * UserController Constructor
     */
    public function __construct()
    {
        $this->middleware('can:manage-users', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * -> search   : string (search by user name)
     * -> sort_by  : string
     * -> order    : string [ask|desk]
     * -> paginate : boolean
     *
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $request['with'] = [];
        return $this->listing(
            User::class,
            UserResource::class,
            'name',
            $request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return UserResource
     */
    public function store(UserRequest $request): UserResource
    {
        /** @var User $user */
        $user = User::query()->create($request->validated());
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * -> with_projects : boolean
     * @param User $user
     * @return UserResource
     */
    public function show(Request $request, User $user): UserResource
    {
        if ($request->get('with_projects', false)) {
            $user->loadMissing('projects');
        }
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param User $user
     * @return UserResource
     */
    public function update(UserRequest $request, User $user): UserResource
    {
        $user->update($request->validated());
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return response()->json(['message' => 'Successfully deleted']);
    }
}
