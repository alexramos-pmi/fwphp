<?php

namespace App\Adapters\Contracts;

use App\Http\Request;

interface ValidateAdapter
{
    public function run(Request $request, $id = null): Request;
}

