<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_id','receiver_id',
        'sender_skill_id','receiver_skill_id',
        'status','message_for_receiver'
    ];

public function conversation()  { return $this->belongsTo(\App\Models\Conversation::class); }
public function sender()        { return $this->belongsTo(\App\Models\User::class, 'sender_id'); }
public function receiver()      { return $this->belongsTo(\App\Models\User::class, 'receiver_id'); }
public function senderSkill()   { return $this->belongsTo(\App\Models\Skill::class, 'sender_skill_id'); }
public function receiverSkill() { return $this->belongsTo(\App\Models\Skill::class, 'receiver_skill_id'); }

}
