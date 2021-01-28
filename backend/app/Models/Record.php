<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'user_id',
        'amount',
        'date',
        'category',
    ];

    public function user(){
        return $this->hasOne('App\Model\User');
    }
}
