<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpecialDate extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'facility_id',
        'date',
        'is_closed',
        'special_opening_time',
        'special_closing_time',
        'reason',
    ];

    protected $casts = [
        'date' => 'date',
        'special_opening_time' => 'datetime:H:i',
        'special_closing_time' => 'datetime:H:i',
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
}
