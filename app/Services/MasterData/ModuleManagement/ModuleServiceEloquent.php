<?php


namespace App\Services\MasterData\ModuleManagement;

use App\Http\Resources\MenuResource;
use App\Http\Resources\ModuleResource;
use App\Libraries\Auth;
use App\Models\Menu;
use App\Models\Module;
use App\Services\MasterData\MasterDataServiceEloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModuleServiceEloquent extends MasterDataServiceEloquent
{
    /**
     * ModuleServiceEloquent constructor.
     *
     * @param Module $model
     * @param string $resource
     */
    public function __construct(Module $model, string $resource = ModuleResource::class)
    {
        parent::__construct($model, $resource);

        // set spatie query builder params
        $this->defaultAllowedFilter = ["menus.name"];
        $this->defaultAllowedIncludes = ["menus","menus.submenus"];
        $this->defaultAllowedSorts = ["menus.name","menus.order_no","menus.submenus.name","menus.submenus.order_no"];
    }

    /**
     * @param string $id
     * @param array $relationship
     * @return array
     */
    public function detail(string $id, array $relationship = []): array
    {
        // set relationship
        $with = ["menus",'menus.submenus'];

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
            "name"                  => $request->name,
            "icon"                  => $request->icon,
            "path"                  => $request->path,
            "is_show_on_dashboard"  => boolval($request->is_show_on_dashboard),
            "order_no"              => $request->order_no,
            "status"                => $request->status,
            "created_by"            => Auth::user()->id
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
        $model->icon = $request->icon;
        $model->path = $request->path;
        $model->is_show_on_dashboard = $request->is_show_on_dashboard;
        $model->order_no = $request->order_no;
        $model->status = $request->status;
        $model->updated_by = Auth::user()->id;

        return $model;
    }

    /**
     * @param string $modulePath
     * @return array
     */
    public function getMenuByModulePath(string $modulePath): array
    {
        // find module by module path
        $module = $this->model->where("path", "/".$modulePath)->first();
        if (is_null($module)) {
            // if module path doesn't exist, get main path
            $module = $this->model->where("path", '/')->first();
        }

        // check module data
        if (is_null($module)) {
            $this->result["status"] = false;
            $this->result["code"] = JsonResponse::HTTP_NOT_FOUND;
            $this->result["message"] = __("master_data.not_found");
        } else {
            // get menu by module id
            $menus = Menu::where("module_id", $module->id)
                ->where("status", Menu::STATUS_ACTIVE)
                ->orderBy("order_no", "ASC")
                ->get();

            // set data and resource
            $this->result["data"] = $menus;
            $this->result["resource"] = MenuResource::class;
        }

        return $this->result;
    }
}
