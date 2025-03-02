<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = ['reference_no', 'customer_name', 'email', 'phone', 'problem_description', 'status'];
    
    public function replies()
    {        
        return $this->hasMany(Reply::class)->with('user');
    }
}
