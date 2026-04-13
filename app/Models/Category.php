<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ticket;

class Category extends Model
{
    protected $fillable = [
        'name',
        'created_by'
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}