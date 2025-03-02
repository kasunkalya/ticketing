<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reply extends Model
{
    use HasFactory;
    protected $fillable = ['ticket_id', 'agent_id', 'message'];
    
    public function ticket()    {
        return $this->belongsTo(Ticket::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}
