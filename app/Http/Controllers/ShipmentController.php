<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Shipment;
use Illuminate\Http\Request;
use App\Jobs\SendOrderStatusUpdatedEmail;

class ShipmentController extends Controller
{

    public function store(Request $request)
    {
        try{
            $shipping = Shipment::create($request->all());
            $response = [
                'success' => true,
                'id' => $shipping->getId()
            ];
            return response()->json($response, 201);

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 400);
        }
    }

    public function changeStatus(Request $request, Shipment $shipment)
    {
        try {
            
            if (empty($request)) {
                throw new Exception('Missing body data', 404 );
            }

            $shipment->update(['status' => $request->input('status')]);

            $shipment->order->update([
                'status' => $request->input('status')
            ]);
            
            $response = [
                'success' => true,
                'message' => 'Shipment updated successfully'
            ]; 

            SendOrderStatusUpdatedEmail::dispatch($shipment->order);

            return response()->json($response);

            
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage(),
            ];

            return response()->json($response, 400);
        }
    }

}
