<?php
namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

interface RestApiContract {
    /**
     * @param Request $request
     * @return Model
     */
    public function index(Request $request) : Model;

    /**
     * @param Request $request
     * @return Model
     */
    public function store(Request $request) : Model;

    /**
     * @param Request $request
     * @param $id
     * @return Model
     */
    public function update(Request $request, $id) : Model;

    /**
     * @param $id
     * @return Model
     */
    public function show($id) : Model;

    /**
     * @param $id
     * @return Model
     */
    public function destroy($id) : Model;
}
