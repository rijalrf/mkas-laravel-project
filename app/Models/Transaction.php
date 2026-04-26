<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'amount',
        'description',
        'photo',
        'status',
        'admin_note',
        'admin_photo',
        'user_id',
        'category_id',
        'reference_number',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $prefix = $model->type === 'IN' ? 'MKASIN' : 'MKASOUT';
            $last = static::where('type', $model->type)->orderBy('id', 'desc')->first();
            $number = 1;
            if ($last && $last->reference_number) {
                $lastNumber = (int) substr($last->reference_number, strlen($prefix));
                $number = $lastNumber + 1;
            }
            $model->reference_number = $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
