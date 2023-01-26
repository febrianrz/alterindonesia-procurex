<?php


namespace App\Services\MasterData\ModuleManagement;

use App\Http\Resources\MenuResource;
use App\Libraries\Auth;
use App\Models\Menu;
use App\Services\MasterData\MasterDataServiceEloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class MenuServiceEloquent extends MasterDataServiceEloquent
{
    /**
     * MenuServiceEloquent constructor.
     *
     * @param Menu $model
     * @param string $resource
     */
    public function __construct(Menu $model, $resource = MenuResource::class)
    {
        parent::__construct($model, $resource);
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function generateNewData(Request $request): array
    {
        // return new data
        return [
            "module_id"     => $request->module_id,
            "name"          => $request->name,
            "icon"          => $request->icon,
            "status"        => $request->status,
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
        $model->module_id = $request->module_id;
        $model->name = $request->name;
        $model->icon = $request->icon;
        $model->status = $request->status;
        $model->updated_by = Auth::user()->id;

        return $model;
    }
}
