<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Jobs\PrintShippingLabel;
use App\Mail\OrderStatusUpdated;
use App\Jobs\SendOrderStatusUpdatedEmail;

class OrderController extends Controller
{
    public function index()
    {
        return Order::paginate();
    }

    public function store(Request $request)
    {
        $response = [];
        try {
            $order = Order::generateOrder($request->all());

            $response = [
                'success' => true,
                'order' => $order
            ];
            return response()->json($response, 201);
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            return response()->json($response, 400);
        }
    }

    public function show(Order $order)
    {
        //a mapper can be added for simplicity
        $response = [
            'user_id' => $order->user->getId(),
            'order_date' => $order->order_date,
            'total_amount' => $order->total_amount,
            'created_at' => $order->created_at,
            'updated_at' => $order->updated_at,
            'status' => $order->status,
            'id' => $order->getId(),
            'shipment' => $this->mapShippings($order->shipments)
        ];
        return response()->json($response, 200);
    }

    public function destroy(Order $order)
    {
        try {
            if($order->canBeCancelled()) {
                $order->markAsCancelled();

            $response = [
                'success' => true,
                'message' => 'Order Cancelled successfully'
            ];
            
            return response()->json($response, 204);

            } else {
                throw new \Exception("The order has been {$order->getStatus()}, it's not possible to cancel it", 404);
            }

        } catch (\Exception $e) {

            $response = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            return response()->json($response, $e->getCode());
        }
    }

    public function printLabel(Order $order)
    {
        if(!$order->canBeSent()) {
            return response()->json("The order status is {$order->getStatus()}", 404);
        }

        $order->markAsProcessing();

        foreach($order->shipments as $shipment) {
            $shipment->markAsProcessing();
        }

        SendOrderStatusUpdatedEmail::dispatch($order);

        PrintShippingLabel::dispatch($order);

    }

    protected function mapShippings($shippings): array
    {
        $data = [];
        foreach($shippings as $shipping) {
            $data = [
                'id' => $shipping->getId(),
                'status' => $shipping->status,
                'addresss' => $shipping->address,
            ];
        }
        return $data;
    }
}
