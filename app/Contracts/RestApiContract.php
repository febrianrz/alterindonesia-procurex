<?php
namespace App\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

interface RestApiContract {
    /**
     * @param Request $request
     * @param Model $model
     * @return array
     */
    public function index(Request $request) : array;

    /**
     * @param Request $request
     * @param Model $model
     * @return array
     */
    public function store(Request $request, Model $model) : array;

    /**
     * @param Request $request
     * @param Model $model
     * @return array
     */
    public function update(Request $request, Model $model) : array;

    /**
     * @param Model $model
     * @return array
     */
    public function show(Model $model) : array;

    /**
     * @param Model $model
     * @return array
     */
    public function destroy(Model $model) : array;
}
