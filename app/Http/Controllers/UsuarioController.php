<?php

namespace App\Http\Controllers;

use App\Adapters\Contracts\ImageUploadAdapter;
use App\Adapters\Contracts\ValidateAdapter;
use Exception;
use App\Core\Auth;
use App\Http\Request;
use App\Models\ElModel;
use App\Models\ErModel;
use App\Foundation\Http;
use App\Models\EtaModel;
use App\Models\UsuarioModel;

class UsuarioController
{
    public function __construct(
        private ImageUploadAdapter $imageUploadAdapter, 
        private ValidateAdapter $validateAdapter
    ){}

    public function index()
    {
        $id = Auth::user()->id;
        $level = Auth::user()->level;

        $usuarios = UsuarioModel::orderBy('name')->get();

        $list = [];

        if(UsuarioModel::count())
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
                    //Atributos adicionados
                    'level_name' => $usuario->level_name,
                ];
            }
        }

        inertia('Usuario/Index', compact('list'));
    }

    public function store(Request $request)
    {
        try
        {
            //Adaptador que realiza as validações do form
            $request = $this->validateAdapter->run($request);

            if($request->file('file'))
            {
                $uploader = $this->imageUploadAdapter->setFile($request->file('file'))
                ->setDimensions(500, 500)
                ->keepAspectRatio(true);

                $result = $uploader->upload();
                $request->set('cover', $result['filename']);
            }

            $usuario = UsuarioModel::create($request->all());
            $nome = $usuario->name;

            //Cria o log
            logger("Criou: {$nome}", "Criar");

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
            //Adaptador que realiza as validações do form
            $request = $this->validateAdapter->run($request, $id);
            
            //Consulta usuário
            $usuario = UsuarioModel::find($id);

            //Verifica se existe arquivo
            if($request->file('file'))
            {
                $cover = $usuario ? $usuario->cover : null;

                //Adaptador para upload de imagens
                $uploader = $this->imageUploadAdapter->setFile($request->file('file'))
                ->setDimensions(500, 500)
                ->keepAspectRatio(true) //Corta e ajusta a imagem
                ->unlink($cover); //Exclui a foto antiga, caso exista

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

            //Cria o log
            logger("Editou: {$nome}", "Usuário - Editar");

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
            $usuario = UsuarioModel::find($id);
            $cover = $usuario->cover;
            $nome = $usuario->name;

            if($usuario->level === 2 && UsuarioModel::where('level', 2)->count() <= 1)
            {
                throw new Exception("O sistema deve ter pelo menos 1 administrador");
            }

            $image = public_path("images/{$cover}");

            if(file_exists($image))
            {
                unlink($image);
            }

            $usuario->delete();

            //Cria o log
            logger("Excluiu: {$nome}", "Usuário - Excluir");

            return response()->json(['success' => "Usuário {$nome} excluído com sucesso"], Http::OK);
        }
        catch(Exception $e)
        {
            return response()->json(['error' => $e->getMessage()], Http::BAD_REQUEST);
        }
    }
}