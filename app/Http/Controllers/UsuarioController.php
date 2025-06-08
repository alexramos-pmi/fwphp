<?php

namespace App\Http\Controllers;

use App\Core\ImageUploader;
use App\Http\Request;
use App\Models\Usuario;
use App\Foundation\Http;
use App\Support\Validate;
use Exception;

class UsuarioController
{
    public function index()
    {
        $usuarios = Usuario::orderBy('name')->get();

        $list = [];

        if(Usuario::count())
        {
            foreach($usuarios as $usuario)
            {
                $list[] = [
                    'id' => $usuario->id,
                    'name' => $usuario->name,
                    'email' => $usuario->email,
                    'level' => $usuario->level,
                    'cover' => $usuario->cover,
                    'path' => url('images/' . $usuario->cover),
                    'level_name' => $usuario->level_name
                ];
            }
        }

        inertia('Usuario/Index', compact('list'));
    }

    public function store(Request $request)
    {
        try
        {
            $validate = new Validate($request->all(), 0);
            $validate->setModel(new Usuario());

            $validate->require('name', 'Nome');
            $validate->unique('name', 'Nome');
            $validate->max('name', 'Nome', 100);
            $validate->require('email', 'Email');
            $validate->unique('email', 'E-mail');
            $validate->max('email', 'E-mail', 100);
            $validate->require('password', 'Senha');
            $validate->require('level', 'Nível');

            if($validate->getErrors())
            {
                throw new Exception($validate->getErrors());
            }

            $request->set('password', bcrypt($request->input('password')));

            if($request->file('file'))
            {
                $uploader = (new ImageUploader())
                ->setFile($request->file('file'))
                ->setDimensions(500, 500)
                ->keepAspectRatio(true);

                $result = $uploader->upload();
                $request->set('cover', $result['filename']);
            }

            $usuario = Usuario::create($request->all());
            $nome = $usuario->name;

            return response()->json(['success' => "Usuário {$nome} gravado com sucesso"], Http::OK);
        }
        catch(Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], Http::BAD_REQUEST);
        }
    }

    public function update(Request $request, $id)
    {
        $request->remove('_method');

        try
        {
            $validate = new Validate($request->all(), $id);
            $validate->setModel(new Usuario());

            $validate->require('name', 'Nome');
            $validate->unique('name', 'Nome');
            $validate->max('name', 'Nome', 100);
            $validate->require('email', 'Email');
            $validate->unique('email', 'E-mail');
            $validate->max('email', 'E-mail', 100);
            $validate->require('level', 'Nível');

            if($validate->getErrors())
            {
                throw new Exception($validate->getErrors());
            }

            if(!empty($request->input('password')))
            {
                $request->set('password', bcrypt($request->input('password')));
            }
            else
            {
                $request->remove('password');
            }
            
            //Consulta usuário
            $usuario = Usuario::find($id);

            //Verifica se existe arquivo
            if($request->file('file'))
            {
                $uploader = (new ImageUploader())
                ->setFile($request->file('file'))
                ->setDimensions(500, 500)
                ->keepAspectRatio(true) //Corta e ajusta a imagem
                ->unlink($usuario->cover); //Exclui a foto antiga, caso exista

                $result = $uploader->upload();
                $request->set('cover', $result['filename']);
            }
            elseif(!$request->file('file') && $usuario->cover)
            {
                $request->set('cover', $usuario->cover);
            }
            else
            {
                $request->set('cover', null);
            }

            //Captura o nome do usuário
            $nome = $usuario->name;
            $usuario->fill($request->all())->save();

            return response()->json(['success' => "Usuário {$nome} atualizado com sucesso"], Http::OK);
        }
        catch(Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], Http::BAD_REQUEST);
        }
    }

    public function destroy($id)
    {
        try
        {
            $usuario = Usuario::find($id);
            $nome = $usuario->name;

            if($usuario->level === 2 && Usuario::where('level', 2)->count() <= 1)
            {
                throw new Exception("O sistema deve ter pelo menos 1 administrador");
            }

            $usuario->delete();

            return response()->json(['success' => "Usuário {$nome} excluído com sucesso"], Http::OK);
        }
        catch(Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], Http::BAD_REQUEST);
        }
    }
}