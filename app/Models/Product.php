<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'size', 'image', 'type', 'unit_price'];

    // Define the enum values for the 'type' field
    public const TYPES = ['Electronics', 'Clothing', 'Books', 'Furniture', 'Others'];

    // Use an accessor to retrieve the type label
    public function getTypeLabelAttribute()
    {
        return self::TYPES[$this->type];
    }
}
