<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacilityEquipment extends Model
{
    protected $fillable = [
        'facility_id',
        'name',
        'quantity',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
