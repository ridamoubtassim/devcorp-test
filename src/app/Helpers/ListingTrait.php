<?php


namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

trait ListingTrait
{
    /**
     * Display a listing of the resource.
     * @param string $model
     * @param string $resource
     * @param string $searchBy
     * @param Request $request
     *
     * @return AnonymousResourceCollection
     */
    public function listing(
        string  $model,
        string  $resource,
        string  $searchBy,
        Request $request): AnonymousResourceCollection
    {
        /** @var Model $model */
        /** @var JsonResource $resource */

        // prepare tools
        $with = $request->get('with', []);
        $search = $request->get('search', '');
        $sortBy = $request->get('sort_by');
        $order = $request->get('order', 'asc');
        $paginate = $request->get('paginate', false);

        // prepare query (with / search)
        $query = $model::query()->with($with)
            ->where($searchBy, 'like', "%$search%");

        // sort by / order
        $allowedSortBy = app($model)->getFillable();
        if (in_array($sortBy, $allowedSortBy) && in_array($order, ['asc', 'desc'])) {
            $query = $query->orderBy($sortBy, $order);
        }

        // all or paginate
        $collection = $paginate ? $query->paginate() : $query->get();

        // return
        return $resource::collection($collection);
    }
}
