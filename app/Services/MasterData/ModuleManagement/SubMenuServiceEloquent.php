<?php


namespace App\Services\MasterData\ModuleManagement;

use App\Http\Resources\SubMenuResource;
use App\Libraries\Auth;
use App\Models\SubMenu;
use App\Services\MasterData\MasterDataServiceEloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class SubMenuServiceEloquent extends MasterDataServiceEloquent
{
    /**
     * SubMenuServiceEloquent constructor.
     *
     * @param SubMenu $model
     * @param string $resource
     */
    public function __construct(SubMenu $model, $resource = SubMenuResource::class)
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
            "menu_id"       => $request->menu_id,
            "name"          => $request->name,
            "icon"          => $request->icon,
            "status"        => $request->status,
            "created_by"    => Auth::user()->id,
            "path"          => $request->path,
            "order_no"      => $request->order_no
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
        $model->menu_id = $request->menu_id;
        $model->name = $request->name;
        $model->icon = $request->icon;
        $model->status = $request->status;
        $model->updated_by = Auth::user()->id;
        $model->path = $request->path;
        $model->order_no = $request->order_no;

        return $model;
    }
}
