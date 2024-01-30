<?php

namespace Shtatva\DynamicCrud\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function tablefields()
    {
        return $this->hasMany(TableField::class);
    }

    public function model()
    {
        return $this->hasOne(DynamicModel::class);
    }

    public function controller()
    {
        return $this->hasOne(DynamicController::class);
    }

    public function module()
    {
        return $this->hasOne(Module::class);
    }
}
