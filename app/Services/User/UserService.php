<?php
namespace App\Services\User;

use App\Contracts\RestApiContract;
use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Yajra\DataTables\Facades\DataTables;

class UserService implements RestApiContract {

    /**
     * @param Request $request
     * @param Model $model
     * @return array
     * @throws \Exception
     */
    public function index(Request $request, Model $model): array
    {
        $resource = UserResource::collection($model->paginate())->toArray($request);
        return DataTables::of(source: $resource)->toArray();
    }

    /**
     * @param Request $request
     * @param Model $model
     * @return array
     */
    public function store(Request $request, Model $model): array
    {
        // TODO: Implement store() method.
    }

    /**
     * @param Request $request
     * @param Model $model
     * @return array
     */
    public function update(Request $request, Model $model): array
    {
        // TODO: Implement update() method.
    }

    /**
     * @param Model $model
     * @return array
     */
    public function show(Model $model): array
    {

    }

    /**
     * @param Model $model
     * @param JsonResource $resource
     * @return array
     */
    public function destroy(Model $model): array
    {
        // TODO: Implement destroy() method.
    }
}
