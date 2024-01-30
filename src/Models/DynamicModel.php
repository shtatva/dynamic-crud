<?php

namespace Shtatva\DynamicCrud\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicModel extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
