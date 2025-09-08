<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PremiumRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','provider','txid','email','note','reference','status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
