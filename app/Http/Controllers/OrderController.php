<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Controllers\ResponseApiController;
use App\Http\Resources\OrderResource;

class OrderController extends ResponseApiController
{
    
    public function index()
    {
        $orders = Order::all();

        $message = $this->sendResponse($orders, 'Pedidos listados correctamente');

        return $message;
    }

    public function store(Request $request) {
        
        $message = null;

        try {

            $order = new Order;
            $order->price = $request->get('price');
            $order->date_order = $request->get('date_order');
            $order->date_delivery = $request->get('date_delivery');
            $order->customer_id = $request->get('customer_id');
            $order->save();

            // Recupera el último id registrado de la notificación
            $order_id = $order->id;   

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

}