<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TripGallery extends Model
{
	protected $guarded = ['id'];

    protected $appends = ['imageUrl', 'thumbImageUrl', 'mediumImageUrl'];

    public function getImageUrlAttribute()
    {
        if (isset($this->attributes['image_name']) && !empty($this->attributes['image_name'])) {
            $image_url = url('/storage/trip-galleries');
        	return $image_url . '/' . $this->attributes['trip_id'] . '/' . $this->attributes['image_name'];
        }
        return config('constants.default_image_url');
    }

    public function getThumbImageUrlAttribute()
    {
        if (isset($this->attributes['image_name']) && !empty($this->attributes['image_name'])) {
            $image_url = url('/storage/trip-galleries');
        	return $image_url . '/' . $this->attributes['trip_id'] . '/thumb_' . $this->attributes['image_name'];
        }
        return config('constants.default_image_url');
    }

    public function getMediumImageUrlAttribute()
    {
        if (isset($this->attributes['image_name']) && !empty($this->attributes['image_name'])) {
            $image_url = url('/storage/trip-galleries');
            return $image_url . '/' . $this->attributes['trip_id'] . '/medium_' . $this->attributes['image_name'];
        }
        return config('constants.default_image_url');
    }
}
