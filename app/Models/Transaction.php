<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    protected $table="transactions";

    protected $fillable = [
        'user_id',
        'amount',
        'transaction_type',
        'transaction_status',
        'transaction_id',
     
    ];   
}
