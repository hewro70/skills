<?php
// app/Models/Review.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'ratings',           // tinyInteger 1..5
        'comment',
        'reply',
        'reply_created_at',
        'sender_id',
        'receved_id',        // (اسم العمود كما في الداتابيس)
    ];

    protected $casts = [
        'reply_created_at' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receved_id'); // مطابق لاسم العمود
    }
}
