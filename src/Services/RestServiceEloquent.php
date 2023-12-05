<?php


namespace Alterindonesia\Procurex\Services;

use Alterindonesia\Procurex\Filters\FilterDate;
use Alterindonesia\Procurex\Interfaces\RestServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;

class RestServiceEloquent implements RestServiceInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * @var string
     */
    protected string $resource;

    /**
     * @var int
     */
    protected int $perPage = 15;

    /**
     * @var array
     */
    protected array $result;

    /**
     * @var array Allowed Filter Search
     */
    protected array $defaultAllowedFilter = [];

    /**
     * @var array include relation
     */
    protected array $defaultAllowedIncludes = [];

    /**
     * @var array  Allowed Sort
     */
    protected array $defaultAllowedSorts = [];

    protected string $messageKey = 'rest_data';

    /**
     * MasterDataServiceEloquent constructor.
     * @param Model $model
     * @param string $resource
     */
    public function __construct(Model $model, string $resource = JsonResource::class)
    {
        // Initiation
        $this->model = $model;
        $this->resource = $resource;

        // set spatie query builder params
        $this->defaultAllowedFilter = [];
        $this->defaultAllowedIncludes = [];
        $this->defaultAllowedSorts = [];

        // set default result
        $this->result = [
            "status"    => true,
            "code"      => Response::HTTP_OK,
            "message"   => __("{$this->messageKey}.get"),
            "data"      => [],
            "resource"  => $this->resource
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function list(Request $request): array
    {
        $filters = $this->overrideAllowedFilters() ?? [
            AllowedFilter::custom('created_at', new FilterDate()),
            AllowedFilter::custom('updated_at', new FilterDate()),
            AllowedFilter::trashed(),
            ...$this->model->getFillable(),
            ...$this->defaultAllowedFilter,
        ];

        $this->result["data"] = QueryBuilder::for($this->model)
            ->allowedFields('id', ...$this->model->getFillable())
            ->allowedFilters([
                ...$filters,
                AllowedFilter::callback('action', static fn ($query, $value) => $query),
            ])
            ->defaultSort($this->model->getKeyName())
            ->allowedSorts(
                ...$this->defaultAllowedSorts,
                ...$this->model->getFillable()
            )
            ->allowedIncludes($this->defaultAllowedIncludes)
            ->paginate($request->query('perPage') ?? $this->perPage);

        return $this->result;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function create(Request $request): array
    {
        try {
//            DB::beginTransaction();
            // generate new data
            $newData = $this->generateNewData($request);

            // create data
            $this->model = $this->model->fill($newData);
            $this->beforeStore($this->model, $request);
            $this->model->save();
            $data = $this->model;
            $this->afterStore($data, $request);

            // set success result
            $this->result["message"] = __("{$this->messageKey}.created");
            $this->result["data"] = new $this->resource($data);

            // return result
//            DB::commit();
            return $this->result;
        } catch (\Exception $e) {
//            DB::rollBack($e);
            report($e);
            // set failed result
            $this->result["status"] = false;
            $this->result["code"] = Response::HTTP_INTERNAL_SERVER_ERROR;
            $this->result["message"] = $e;

            // return result
            return $this->result;
        }
    }

    /**
     * @param string $id
     * @return array
     */
    public function detail(string $id, array $relationship = []): array
    {
        // find data by id
        $data = $this->model->with($relationship)
            ->where($this->model->getKeyName(), "=", $id)
            ->first();

        // check data existence
        if ($data) {
            // set success result
            $this->result["data"] = $data;
            $this->result["resource"] = $this->resource;
        } else {
            // set failed result
            $this->result["status"] = false;
            $this->result["code"] = Response::HTTP_NOT_FOUND;
            $this->result["message"] = __("{$this->messageKey}.not_found");
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
        try {

//            DB::beginTransaction();
            // find data by id
            $data = $this->find($id);

            // check data existence
            if (!is_null($data)) {
                // set data
                $this->setUpdatedData($data, $request);
                $this->beforeUpdate($data, $request);

                // update data
                $data->save();
                $this->afterUpdate($data, $request);

                // set success result
                $this->result["message"] = __("{$this->messageKey}.updated");
                $this->result["data"] = new $this->resource($data);
            } else {
                // set failed result
                $this->result["status"] = false;
                $this->result["code"] = Response::HTTP_NOT_FOUND;
                $this->result["message"] = __("{$this->messageKey}.not_found");
            }

//            DB::commit();
            // return result
            return $this->result;
        } catch (\Exception $e) {
//            DB::rollBack($e);
            report($e);
            // set failed result
            $this->result["status"] = false;
            $this->result["code"] = Response::HTTP_INTERNAL_SERVER_ERROR;
            $this->result["message"] = $e;

            // return result
            return $this->result;
        }
    }

    /**
     * @param string $id
     * @return array
     */
    public function delete(string $id, Request $request): array
    {
        // find data by id
        $data = $this->find($id);

        // check data existence
        if (!is_null($data)) {
            $this->beforeDelete($data, $request);
            // delete data
            $data->delete();
            $this->afterDelete($data, $request);

            // set success result
            $this->result["message"] = __("{$this->messageKey}.deleted");
        } else {
            // set failed result
            $this->result["status"] = false;
            $this->result["code"] = Response::HTTP_NOT_FOUND;
            $this->result["message"] = __("{$this->messageKey}.not_found");
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
            $this->result["message"] = __("{$this->messageKey}.restored");
        } else {
            // set failed result
            $this->result["status"] = false;
            $this->result["code"] = Response::HTTP_NOT_FOUND;
            $this->result["message"] = __("{$this->messageKey}.not_found");
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
     * @param Model $model
     * @param Request $request
     * @return Model
     */
    protected function setUpdatedData(Model $model, Request $request): Model
    {
        return $this->model;
    }

    public function beforeStore(Model $model, Request $request){}
    public function afterStore(Model $model, Request $request){}

    public function beforeUpdate(Model $model, Request $request){}
    public function afterUpdate(Model $model, Request $request){}

    public function beforeDelete(Model $model, Request $request){}
    public function afterDelete(Model $model, Request $request){}

    protected function overrideAllowedFilters(): ?array
    {
        return null;
    }
}
