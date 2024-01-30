<?php

namespace Shtatva\DynamicCrud\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableField extends Model
{
    use HasFactory;

    protected $guarded = ['updated_at', 'created_at'];
}
