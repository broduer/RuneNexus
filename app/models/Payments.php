<?php
use Illuminate\Database\Eloquent\Model as Model;

class Payments extends Model {

    public $timestamps    = false;
    public $incrementing  = true;
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'user_id',
        'username',
        'ip_address',
        'sku',
        'item_name',
        'email',
        'status',
        'paid',
        'quantity',
        'currency',
        'capture_id',
        'transaction_id',
        'date_paid',
    ];

    public static function getChartData($dates) {
        $query = Payments::select(["date_paid", "paid"])
            ->where("date_paid", ">=", $dates['start'])
            ->orderby("date_paid", "ASC")
            ->get();

        foreach ($query as $payment) {
            $date = date($dates['format'], $payment->date_paid);
            $dates['chart'][$date] = number_format($dates['chart'][$date] + $payment->paid, 2);
        }
        
        return array_values($dates['chart']);
    }
}