<?php

namespace App\Models;

use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    CONST STATUS_PENDING = 'Pendiente';
    CONST STATUS_SENT = 'Enviado';
    CONST STATUS_PAID = 'Pagado';

    protected $fillable = [
        'folio',
        'num_dinners',
        'status',
        'user_id',
        'table_id',
        'total_amount',
    ];

    protected $appends = ['formatted_date', 'formatted_time'];

    public function getFormattedDateAttribute()
    {
        return $this->created_at ? $this->created_at->format('Y-m-d') : null;
    }

    public function getFormattedTimeAttribute()
    {
        return $this->created_at ? $this->created_at->format('H:i:s A') : null;
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
}
