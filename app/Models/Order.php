<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Shipment;
use App\Models\OrderDetail;
use App\Repositories\OrderRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'order_date',
        'user_id',
        'total_amount', 
        'status'
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function markAsShipped()
    {
        $this->status = self::STATUS_SHIPPED;
        $this->save();
    }

    public function markAsDelivered()
    {
        $this->status = self::STATUS_DELIVERED;
        $this->save();
    }

    public function markAsProcessing()
    {
        $this->status = self::STATUS_PROCESSING;
        $this->save();
    }

    public function markAsCancelled()
    { 
        $this->status = self::STATUS_CANCELLED;
        $this->save();
    }

    public function canBeCancelled(): bool
    {
        if ($this->status === self::STATUS_PENDING) {
            return true;
        }
        return false;
    }

    public function canBeSent(): bool
    {
        if ($this->status === self::STATUS_PENDING) {
            return true;
        }
        return false;
    }

    public static function generateOrder(array $orderData)
    {
        try{
            $orderRepository = new OrderRepository();
            $order = $orderRepository->saveOrder($orderData['order']);

            foreach ($orderData['orderDetails'] as $detailData) {
                OrderDetail::create([
                    'order_id' => $order->getId(),
                    'product_id' => $detailData['product_id'],
                    'quantity' => $detailData['quantity'],
                    'unit_price' => $detailData['unit_price'],
                    'subtotal' => $detailData['subtotal']
                ]);
            }

            return $order;

        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }

    public static function showOrder(int $orderId): Order
    {  
        return static::with('orderDetails')->findOrFail($orderId);
    }

    public function getStatus()
    {
        return $this->status;
    }
    
    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function shipments():HasMany
    {
        return $this->hasMany(Shipment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
;