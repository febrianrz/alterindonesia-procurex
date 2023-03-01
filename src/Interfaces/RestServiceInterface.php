<?php

namespace Alterindonesia\Procurex\Interfaces;

use Illuminate\Http\Request;

interface RestServiceInterface
{
    public function list(Request $request);

    public function create(Request $request);

    public function detail(string $id, array $relationship = []);

    public function update(string $id, Request $request);

    public function delete(string $id);

    public function restore(string $id);
}
