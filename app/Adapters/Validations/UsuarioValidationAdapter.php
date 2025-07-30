<?php

namespace App\Adapters\Validations;

use Exception;
use App\Http\Request;
use App\Support\Validate;
use App\Models\UsuarioModel;
use App\Adapters\Contracts\ValidateAdapter;

class UsuarioValidationAdapter implements ValidateAdapter
{
    public function run(Request $request, $id = 0): Request
    {
        $validate = new Validate($request->all(), $id);
        $validate->setModel(new UsuarioModel());

        $validate->require('name', 'Nome');
        $validate->unique('name', 'Nome');
        $validate->max('name', 'Nome', 100);
        $validate->require('email', 'Email');
        $validate->unique('email', 'E-mail');
        $validate->max('email', 'E-mail', 100);
        $validate->require('level', 'Nível');

        if($id <= 0)
        {
            $validate->require('password', 'Senha');
        }

        if($validate->getErrors())
        {
            throw new Exception($validate->getErrors());
        }

        if($id > 0)
        {
            if(!empty($request->input('password')))
            {
                $request->set('password', bcrypt($request->input('password')));
            }
            else
            {
                $request->remove('password');
            }
        }
        else 
        {
            $request->set('password', bcrypt($request->input('password')));
        }

        return $request;
    }
}
