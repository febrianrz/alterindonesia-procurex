<?php

namespace App\Services\MasterData;

use Illuminate\Http\Request;

interface MasterDataServiceInterface
{
    public function list();

    public function create(Request $request);

    public function detail(string $id);

    public function update(string $id, Request $request);

    public function delete(string $id);

    public function restore(string $id);
}
