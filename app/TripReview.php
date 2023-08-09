<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TripReview extends Model
{
	protected $guarded= ['id'];

	protected $appends = ['imageUrl', 'thumbImageUrl'];

	public function trip()
	{
		return $this->belongsTo('App\Trip');
	}

	public function getImageUrlAttribute()
	{
	    if (isset($this->attributes['image_name']) && !empty($this->attributes['image_name'])) {
	        $image_url = url('/storage/trip-reviews');
	    	return $image_url . '/' . $this->attributes['id'] . '/' . $this->attributes['image_name'];
	    }
	    return config('constants.default_image_url');
	}

	public function getThumbImageUrlAttribute()
	{
	    if (isset($this->attributes['image_name']) && !empty($this->attributes['image_name'])) {
	        $image_url = url('/storage/trip-reviews');
	    	return $image_url . '/' . $this->attributes['id'] . '/thumb_' . $this->attributes['image_name'];
	    }
	    return config('constants.default_image_url');
	}

	public function scopePublished($query)
	{
	    return $query->where('status', 1);
	}
}
