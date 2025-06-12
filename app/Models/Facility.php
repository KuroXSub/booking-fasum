<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Facility extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'image',
        'available_days',
        'opening_time',
        'closing_time',
        'max_booking_hours',
        'is_active',
    ];

    protected $casts = [
        'available_days' => 'array',
        'opening_time' => 'datetime:H:i',
        'closing_time' => 'datetime:H:i',
    ];

    protected $appends = ['image_path'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function specialDates()
    {
        return $this->hasMany(SpecialDate::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function equipment()
    {
        return $this->hasMany(FacilityEquipment::class);
    }

    public function getImagePathAttribute()
    {
        if (!$this->image) {
            return null;
        }
        
        return asset('storage/' . $this->image);
    }

    public function isAvailableOnDay($dayOfWeek)
    {
        return in_array($dayOfWeek, $this->available_days ?? []);
    }

    public function getOpeningTimeAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }

    public function getClosingTimeAttribute($value)
    {
        return Carbon::parse($value)->format('H:i');
    }
}
