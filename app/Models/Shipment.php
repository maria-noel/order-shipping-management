<?php

namespace App\Models;

use App\Http\Requests\ShipmentRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    use HasFactory;

    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    
    protected $fillable = [
        'order_id',
        'address',
        'shipping_method',
        'shipment_date',
        'status'
    ];

    public function getId(): int
    {
        return $this->id;
    }

    public function markAsProcessing()
    {
        $this->status = self::STATUS_PROCESSING;
        $this->save();
    }
    
    public function setStatus(string $status)
    {
        $this->$status = $status;
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

}
