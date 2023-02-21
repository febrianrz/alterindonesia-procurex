<?php


namespace App\Services\MasterData\ServiceManagement;

use App\Http\Resources\ServiceResource;
use App\Libraries\Auth;
use App\Models\Service;
use App\Services\MasterData\MasterDataServiceEloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ServiceEloquent extends MasterDataServiceEloquent
{
    /**
     * MenuServiceEloquent constructor.
     *
     * @param Service $model
     * @param string $resource
     */
    public function __construct(Service $model, string $resource = ServiceResource::class)
    {
        parent::__construct($model, $resource);

        // set spatie query builder params
    }

    /**
     * @param string $id
     * @param array $relationship
     * @return array
     */
    public function detail(string $id, array $relationship = []): array
    {
        // set relationship
        $with = [];

        return parent::detail($id, $with);
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function generateNewData(Request $request): array
    {
        // return new data
        return [
            "name"          => $request->name,
            "url"           => $request->url,
            "is_active"     => $request->is_active,
            "created_by"    => Auth::user()->id
        ];
    }

    /**
     * @param Model $model
     * @param Request $request
     * @return Model
     */
    protected function setUpdatedData(Model $model, Request $request): Model
    {
        // set data
        $model->name = $request->name;
        $model->url = $request->url;
        $model->is_active = $request->is_active;
        $model->updated_by = Auth::user()->id;

        return $model;
    }
}
