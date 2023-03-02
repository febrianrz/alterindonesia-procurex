<?php
namespace App\Services\User;

use App\Contracts\RestApiContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UserService implements RestApiContract {

    private mixed $model;

    public function __construct($model) {
        $this->model = new $model;
    }
    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function index(Request $request): Model
    {
        /** Jalankan query disini */
        return $this->model;
    }


    public function store(Request $request): Model
    {
        // TODO: Implement store() method.
    }

    public function update(Request $request, $id): Model
    {
        // TODO: Implement update() method.
    }

    /**
     * @param $id
     * @return Model
     * @throws \Exception
     */
    public function show($id): Model
    {
        $row = $this->model->find($id);
        if(!$row) throw new \Exception("Not Found");
        return $row;
    }

    public function destroy($id): Model
    {
        // TODO: Implement destroy() method.
    }
}
