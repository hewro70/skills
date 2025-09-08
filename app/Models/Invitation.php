<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_time',
        'reply',
        'source_user_id',
        'destination_user_id',
        'message', 
    ];

    protected $casts = [
        'date_time' => 'datetime',
    ];

    public function sourceUser(){ return $this->belongsTo(User::class, 'source_user_id'); }
    public function destinationUser(){ return $this->belongsTo(User::class, 'destination_user_id'); }
}
