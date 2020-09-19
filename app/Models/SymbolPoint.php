<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SymbolPoint extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function symbol()
    {
        return $this->hasMany('App\Models\Symbol');
    }
}
