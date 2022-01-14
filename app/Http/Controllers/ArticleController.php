<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\ResponseApiController;

/**
* @OA\Info(title="API", version="1.0")
*
* @OA\Server(url="http://localhost:8000")
*/

class ArticleController extends ResponseApiController
{

    /**
    * @OA\Get(
    *     path="/api/article",
    *     tags={"artículos"},
    *     summary="Mostrar articulos",
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar todos los artículos."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */

    public function index()
    {
        $articles = Article::all();

        $message = $this->sendResponse($articles, 'Articulos listados correctamente');

        return $message;
    }

    /**
     * @OA\Post(
     * path="/api/article",
     * summary="Agregar Artículo",
     * description="catalog_id, name",
     * operationId="id",
     * tags={"artículos"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Agrega Artículo",
     *    @OA\JsonContent(
     *       required={"catalog_id","name"},
     *       @OA\Property(property="catalog_id", type="integer", format="text", example="1"),
     *       @OA\Property(property="name", type="string", format="text", example="Artículo 1"),
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Error en los datos",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Hubo un error al crear el articulo")
     *        )
     *     )
     * )
     */
    

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

    /**
    * @OA\Get(
    *     path="/api/article/{id}",
    *     tags={"artículos"},
    *     summary="Mostrar articulo por id",
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="ID del articulo",
    *         required=true,
    *         example="1",
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Mostrar detalle de los artículos."
    *     ),
    *     @OA\Response(
    *         response="default",
    *         description="Ha ocurrido un error."
    *     )
    * )
    */

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

    /**
     * @OA\Put(
     * path="/api/article/{id}",
     * summary="Actualizar Artículo",
     * description="actualizar artículo",
     * tags={"artículos"},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del articulo",
     *         required=true,
     *         example="1",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     * @OA\RequestBody(
     *   required=true,
     *   description="Actualiza Artículo",
     * @OA\JsonContent(
     *      required={"catalog_id","name"},
     *      @OA\Property(property="catalog_id", type="integer", format="text", example="1"),
     *      @OA\Property(property="name", type="string", format="text", example="Artículo Update"),
     *  ),
     * ),
     * @OA\Response(
     *   response=422,
     *  description="Error en los datos",
     * ),
     * )
     * 
     */

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

    /**
     * @OA\Delete(
     * path="/api/article/{id}",
     * summary="Eliminar Artículo",
     * description="elimina articulo",
     * tags={"artículos"},
     *     @OA\Parameter(
     *        name="id",
     *       in="path",
     *      description="ID del articulo",
     *     required=true,
     *    example="1",
     *   @OA\Schema(
     *     type="integer",
     *   format="int64"
     *  )
     * ),
     * @OA\Response(
     *  response=200,
     * description="Artículo eliminado correctamente",
     * ),
     * @OA\Response(
     * response="default",
     * description="Ha ocurrido un error."
     * )
     * )
     */


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
