<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\ResponseApiController;

class ArticleController extends ResponseApiController
{
    public function index()
    {
        $articles = Article::all();

        $message = $this->sendResponse($articles, 'Articulos listados correctamente');

        return $message;
    }
    

    public function store(Request $request) {
       
        $request->validate([
            'catalog_id'  => 'required',
            'name' => 'required',
        ]);        

        $message = null;

        try {

            $article = new Article;
            $article->catalog_id = $request->get('catalog_id');
            $article->name = $request->get('name');            
            $article->save();

            $message = $this->sendResponse($article, 'Articulo creado correctamente');

        }catch(\Throwable $th) {
            $message = $this->sendError($th->getMessage(), 'Hubo un error al crear el articulo');
        }

        return $message;
    }


    public function show($id)
    {
        $article = Article::find($id);

        if(!$article) {
            $message = $this->sendError('Advertencia', 'Articulo no encontrado');
        }else {
            $message = $this->sendResponse($article, 'Articulo encontrado');
        }

        return $message;
    }


    public function update(Request $request, $id) {

        $request->validate([
            'catalog_id'  => 'required',
            'name' => 'required',
        ]);

        $message = null;

        try {

            $article = Article::find($id);
            $article->catalog_id = $request->get('catalog_id');
            $article->name = $request->get('name');
            $article->save();

            $message = $this->sendResponse($article, 'Articulo actualizado correctamente');

        }catch(\Throwable $th) {
            $message = $this->sendError($th->getMessage(), 'Hubo un error al actualizar el articulo');
        }

        return $message;
    }


    public function destroy($id) {
            
        $message = null;

        try {

            $article = Article::find($id);
            $article->delete();

            $message = $this->sendResponse($article, 'Articulo eliminado correctamente');

        }catch(\Throwable $th) {
            $message = $this->sendError($th->getMessage(), 'Hubo un error al eliminar el articulo');
        }

        return $message;
    }


   
}
