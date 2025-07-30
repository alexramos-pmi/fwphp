<?php

namespace App\Adapters\Validations;

use App\Http\Request;
use App\Support\Validate;
use App\Adapters\Contracts\ValidateAdapter;

class DefaultValidationAdapter implements ValidateAdapter
{
    public function run(Request $request, $id = null): Request
    {
        $validate = new Validate($request->all(), $id);
        $validate->require('default', 'Default');

        return $request;
    }
}
