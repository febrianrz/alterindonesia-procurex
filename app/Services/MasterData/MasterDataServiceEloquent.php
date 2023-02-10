<?php


namespace App\Services\MasterData;

use App\Helpers\Filters\FilterDate;
use App\Http\Resources\ModuleResource;
use App\Http\Resources\AnonymousCollection;
use App\Libraries\Auth;
use App\Models\Module;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use PHPUnit\Util\Filter;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class MasterDataServiceEloquent implements MasterDataServiceInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * @var mixed|string
     */
    protected mixed $resource;

    /**
     * @var int
     */
    protected $perPage = 15;

    /**
     * @var array
     */
    protected array $result;

    /**
     * @var array Allowed Filter Search
     */
    protected array $allowedFilter = [];

    /**
     * @var array include relation
     */
    protected array $allowedIncludes = [];


    protected array $allowedSorts = [];

    /**
     * MasterDataServiceEloquent constructor.
     * @param Model $model
     * @param string $resource
     */
    public function __construct(
        Model $model,
        $resource = JsonResource::class,
        $allowedFilter = [],
        $allowedIncludes = [],
        $allowedSorts = []
    )
    {
        $this->model = $model;
        $this->resource = $resource;
        $this->allowedFilter = $allowedFilter;
        $this->allowedIncludes = $allowedIncludes;
        $this->allowedSorts = $allowedSorts;
        $this->result = [
            "status"    => true,
            "code"      => JsonResponse::HTTP_OK,
            "message"   => __("master_data.get"),
            "data"      => [],
            "resource"  => $this->resource
        ];
    }

    /**
     * @return array
     */
    public function list(): array
    {
        $request = app(Request::class);
        $this->result["data"] = QueryBuilder::for($this->model)
            ->allowedFields('id', ...$this->model->getFillable())
            ->allowedFilters(
                AllowedFilter::custom('created_at', new FilterDate()),
                AllowedFilter::custom('updated_at', new FilterDate()),
//                AllowedFilter::trashed(),
                ...$this->model->getFillable(),
                ...$this->allowedFilter,
            )
//            ->allowedFilters(
//                'name',
//                'icon',
//                AllowedFilter::exact('status'),
//                'path',
//                'is_show_on_dashboard',
//                'order_no',
//                'menus.name'
//            )
            ->defaultSort($this->model->getKeyName())
            ->allowedSorts(
                ...$this->allowedSorts,
                ...$this->model->getFillable()
            )
            ->allowedIncludes($this->allowedIncludes)
            ->paginate($request->query('perPage')??15);

        return $this->result;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function create(Request $request): array
    {
        // generate new data
        $newData = $this->generateNewData($request);

        // create data
        $data = $this->model->create($newData);

        // set success result
        $this->result["message"] = __("master_data.created");
        $this->result["data"] = new $this->resource($data);

        // return result
        return $this->result;
    }

    /**
     * @param string $id
     * @return array
     */
    public function detail(string $id): array
    {
        // find data by id
        $data = $this->find($id);

        // check data existence
        if (!is_null($data)) {
            // set success result
            $this->result["data"] = new $this->resource($data);
        } else {
            // set failed result
            $this->result["status"] = false;
            $this->result["code"] = JsonResponse::HTTP_NOT_FOUND;
            $this->result["message"] = __("master_data.not_found");
        }

        // return result
        return $this->result;
    }

    /**
     * @param string $id
     * @param Request $request
     * @return array
     */
    public function update(string $id, Request $request): array
    {
        // find data by id
        $data = $this->find($id);

        // check data existence
        if (!is_null($data)) {
            // set data
            $this->setUpdatedData($data, $request);

            // update data
            $data->save();

            // set success result
            $this->result["message"] = __("master_data.updated");
            $this->result["data"] = new $this->resource($data);
        } else {
            // set failed result
            $this->result["status"] = false;
            $this->result["code"] = JsonResponse::HTTP_NOT_FOUND;
            $this->result["message"] = __("master_data.not_found");
        }

        // return result
        return $this->result;
    }

    /**
     * @param string $id
     * @return array
     */
    public function delete(string $id): array
    {
        // find data by id
        $data = $this->find($id);

        // check data existence
        if (!is_null($data)) {
            // delete data
            $data->delete();

            // set success result
            $this->result["message"] = __("master_data.deleted");
        } else {
            // set failed result
            $this->result["status"] = false;
            $this->result["code"] = JsonResponse::HTTP_NOT_FOUND;
            $this->result["message"] = __("master_data.not_found");
        }

        // return result
        return $this->result;
    }

    /**
     * @param string $id
     * @return array
     */
    public function restore(string $id): array
    {
        // find module by id
        $module = $this->model->onlyTrashed()->where("id", "=", $id)->first();

        // check module existence
        if (!is_null($module)) {
            // restore data module
            $module->restore();

            // set success result
            $this->result["message"] = __("master_data.restored");
        } else {
            // set failed result
            $this->result["status"] = false;
            $this->result["code"] = JsonResponse::HTTP_NOT_FOUND;
            $this->result["message"] = __("master_data.not_found");
        }

        // return result
        return $this->result;
    }

    /**
     * @param string $id
     * @return Model | null
     */
    protected function find(string $id): ?Model
    {
        return $this->model->where("id", "=", $id)->first();
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function generateNewData(Request $request): array
    {
        return [];
    }

    /**
     * @param Request $request
     * @return Model
     */
    protected function setUpdatedData(Model $model, Request $request): Model
    {
        return $this->model;
    }
}
