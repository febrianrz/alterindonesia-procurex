<?php

namespace App\Services\Menu;

use Illuminate\Http\Request;

interface MenuServiceInterface
{
    public function list();

    public function create(Request $request);

    public function detail(string $id);

    public function update(string $id, Request $request);

    public function delete(string $id);

    public function restore(string $id);
}
