<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'folio',
        'num_dinners',
        'order_status_id',
        'user_id',
        'table_id',
        'total_amount',
        'payment_type_id',
        'payment_type_name',
    ];

    protected $appends = ['formatted_date', 'formatted_time', 'selectAll'];

    public function getFormattedDateAttribute()
    {
        return $this->created_at ? $this->created_at->format('Y-m-d') : null;
    }

    public function getFormattedTimeAttribute()
    {
        return $this->created_at ? $this->created_at->format('H:i:s A') : null;
    }

    public function getSelectAllAttribute() {
        return $this->attributes['selectAll'] ?? false;
    }

    public static function generateUniqueFolio()
    {
        return DB::transaction(function () {
            // Bloquea la consulta para evitar colisiones
            $lastFolio = DB::table('orders')
                ->lockForUpdate()
                ->latest('id')
                ->value('folio');

            // Si no hay registros, inicia desde "ORD-0001"
            $number = $lastFolio ? (int) substr($lastFolio, 4) + 1 : 1;

            return 'ORD-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        });
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('folio', 'ILIKE', "%$search%")
            ->orWhere('total_amount', 'LIKE', "%$search%")
            ->orWhere('created_at', 'LIKE', "%$search%")
            ->orWhereHas('table', function ($query) use ($search) {
                $query->where('name', 'ILIKE', "%$search%");
            })
            ->orWhereHas('orderStatus', function ($query) use ($search) {
                $query->where('name', 'ILIKE', "%$search%");
            });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }
}
