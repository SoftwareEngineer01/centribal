<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Controllers\ResponseApiController;
use App\Http\Resources\OrderResource;

class OrderController extends ResponseApiController
{

    /**
     * @OA\Get(
     *     path="/api/order",
     *     tags={"ordenes"},
     *     summary="Mostrar ordenes",
     *     @OA\Response(
     *         response=200,
     *         description="Mostrar todas las ordenes."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */
    
    public function index()
    {
        $orders = Order::orderBy('date_order', 'desc')->get();
        $data = OrderResource::collection($orders);

        $message = $this->sendResponse($data, 'Pedidos listados correctamente');

        return $message;
    }

    /**
     * @OA\Post(
     * path="/api/order",
     * summary="Agregar orden",
     * description="price, date_order, date_delivery, customer_id, article_id",
     * operationId="id",
     * tags={"ordenes"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Agrega orden",
     *    @OA\JsonContent(
     *       required={"price","date_order","date_delivery","customer_id","article_id"},
     *       @OA\Property(property="price", type="string", format="text", example="50000"),
     *       @OA\Property(property="date_order", type="string", format="text", example="2022-02-17 09:15:42"),
     *       @OA\Property(property="date_delivery", type="string", format="text", example="2022-02-17 09:15:42"),
     *       @OA\Property(property="customer_id", type="integer", format="text", example="1"),
     *       @OA\Property(property="article_id", type="integer", format="array", example="[1,2,3]"),
     *    ),
     * ),
     * @OA\Response(
     *         response=200,
     *         description="Agrega orden."
     *     ),
     * @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */

    public function store(Request $request) {
        
        $message = null;

        try {

            $order = new Order;
            $order->price = $request->get('price');
            $order->date_order = $request->get('date_order');
            $order->date_delivery = $request->get('date_delivery');
            $order->customer_id = $request->get('customer_id');
            $order->save();

            // Artículos del pedido
            $articles_id = [];
            
            foreach ($request->get('article_id') as $article_id) {
                array_push($articles_id, [
                    'article_id' => $article_id,
                ]);                
            }

            // Relación entre pedido y artículos - Inserta datos en  article_order
            $order->articles()->attach($articles_id);

            $message = $this->sendResponse($order, 'Pedido creado correctamente');

        }catch(\Throwable $th) {
            $message = $this->sendError($th->getMessage(), 'Hubo un error al crear el pedido');
        }

        return $message;
    }

    /**
     * @OA\Get(
     *     path="/api/order/{id}",
     *     tags={"ordenes"},
     *     summary="Mostrar orden",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del pedido",
     *         required=true,
     *         example=1,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mostrar un pedido."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */

    public function show($id) {

        $order = Order::find($id);   
        $data = new OrderResource($order);

        if(!$order) {
            $message = $this->sendError('Pedido no encontrado', 'Hubo un error al buscar el pedido');
        }else {
            $message = $this->sendResponse($data, 'Pedido encontrado');
        }

        return $message;
    }

    /**
     * @OA\Put(
     * path="/api/order/{id}",
     * summary="Actualizar orden",
     * description="price, date_order, date_delivery, customer_id, article_id",
     * tags={"ordenes"},
     * @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del pedido",
     *         required=true,
     *         example=1,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     * @OA\RequestBody(
     *    required=true,
     *    description="Actualiza pedido",
     *    @OA\JsonContent(
     *       required={"price","date_order","date_delivery","customer_id","article_id"},
     *       @OA\Property(property="price", type="string", format="text", example="6000"),
     *       @OA\Property(property="date_order", type="string", format="text", example="2022-02-17 09:15:42"),
     *       @OA\Property(property="date_delivery", type="string", format="text", example="2022-02-17 09:15:42"),
     *       @OA\Property(property="customer_id", type="integer", format="text", example="1"),
     *       @OA\Property(property="article_id", type="integer", format="array", example="[2,3]"),
     *    ),
     * ),
     * @OA\Response(
     *         response=200,
     *         description="Pedido actualizado correctamente."
     *     ),
     * @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */

    
    public function update(Request $request, $id) {
        
        $message = null;

        try {

            $order = Order::find($id);
            $order->price = $request->get('price');
            $order->date_order = $request->get('date_order');
            $order->date_delivery = $request->get('date_delivery');
            $order->customer_id = $request->get('customer_id');
            $order->save();

            // Artículos del pedido
            $articles_id = [];
            
            foreach ($request->get('article_id') as $article_id) {
                array_push($articles_id, [
                    'article_id' => $article_id,
                ]);                
            }

            // Relación entre pedido y artículos - Inserta datos en  article_order
            $order->articles()->sync($articles_id);

            $message = $this->sendResponse($order, 'Pedido actualizado correctamente');

        }catch(\Throwable $th) {
            $message = $this->sendError($th->getMessage(), 'Hubo un error al actualizar el pedido');
        }

        return $message;
    }

    /**
     * @OA\Delete(
     *     path="/api/order/{id}",
     *     tags={"ordenes"},
     *     summary="Eliminar orden",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del pedido",
     *         required=true,
     *         example=8,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pedido eliminado correctamente."
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Ha ocurrido un error."
     *     )
     * )
     */


    public function destroy($id) {
        
        $message = null;

        try {

            $order = Order::find($id);
            $order->delete();

            $message = $this->sendResponse($order, 'Pedido eliminado correctamente');

        }catch(\Throwable $th) {
            $message = $this->sendError($th->getMessage(), 'Hubo un error al eliminar el pedido');
        }

        return $message;
    }

}
