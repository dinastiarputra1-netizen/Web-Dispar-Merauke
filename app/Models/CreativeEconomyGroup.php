<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreativeEconomyGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'group_name',
        'classification',
        'leader_source',
        'subsector',
        'district',
    ];
}
