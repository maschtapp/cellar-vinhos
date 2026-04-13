<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Ticket extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
        'category_id',
        'created_by'
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
