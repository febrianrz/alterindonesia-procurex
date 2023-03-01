<?php

namespace Alterindonesia\Procurex\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface RestServiceInterface
{
    public function list(Request $request);

    public function create(Request $request);

    public function detail(string $id, array $relationship = []);

    public function update(string $id, Request $request);

    public function delete(string $id, Request $request);

    public function restore(string $id);

    public function beforeStore(Model $model, Request $request);
    public function afterStore(Model $model, Request $request);

    public function beforeUpdate(Model $model, Request $request);
    public function afterUpdate(Model $model, Request $request);

    public function beforeDelete(Model $model, Request $request);
    public function afterDelete(Model $model, Request $request);
}
