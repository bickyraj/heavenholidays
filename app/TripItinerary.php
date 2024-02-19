<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TripItinerary extends Model
{
	protected $guarded = ['id'];
    protected $appends = ['imageUrl'];

    public function trip() {
        return $this->belongsTo(Trip::class);
    }

    public function getImageUrlAttribute()
    {
        if (isset($this->attributes['image_name']) && !empty($this->attributes['image_name'])) {
            $image_url = url('/storage/trips');
            return $image_url . '/' . $this->attributes['trip_id'] . '/itineraries/' . $this->attributes['image_name'];
        }
        return config('constants.default_large_image_url');
    }
}
