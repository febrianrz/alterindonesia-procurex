<?php

namespace App\Services\SubMenu;

use Illuminate\Http\Request;

interface SubMenuServiceInterface
{
    public function list();

    public function create(Request $request);

    public function detail(string $id);

    public function update(string $id, Request $request);

    public function delete(string $id);

    public function restore(string $id);
}
