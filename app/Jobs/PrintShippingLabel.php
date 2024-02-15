<?php

namespace App\Jobs;

use Dompdf\Dompdf;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PrintShippingLabel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        $html = view('pdf.shipping_label')
        ->with([
            'order' => $this->order,
            'orderDetail' => $this->order->orderDetails,
            'user' => $this->order->user,
            'shipping' => $this->order->shipments
        ]);

        $dompdf = new Dompdf();

        $dompdf->loadHtml($html);

        $dompdf->render();

        return $dompdf->stream('example.pdf');
    }
}
