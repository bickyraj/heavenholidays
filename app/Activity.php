<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
	protected $guard = ['id'];

	protected $appends = ['imageUrl', 'thumbImageUrl', 'link'];

	public function getImageUrlAttribute()
	{
	    if (isset($this->attributes['image_name']) && !empty($this->attributes['image_name'])) {
	        $image_url = url('/storage/activities');
	    	return $image_url . '/' . $this->attributes['id'] . '/' . $this->attributes['image_name'];
	    }
        return asset('assets/front/') . config('constants.default_hero_banner');
    }
    
    public function getMediumImageUrlAttribute()
	{
	    if (isset($this->attributes['image_name']) && !empty($this->attributes['image_name'])) {
	        $image_url = url('/storage/activities');
	    	return $image_url . '/' . $this->attributes['id'] . '/medium_' . $this->attributes['image_name'];
	    }
	    return config('constants.default_image_url');
	}

	public function getThumbImageUrlAttribute()
	{
	    if (isset($this->attributes['image_name']) && !empty($this->attributes['image_name'])) {
	        $image_url = url('/storage/activities');
	    	return $image_url . '/' . $this->attributes['id'] . '/thumb_' . $this->attributes['image_name'];
	    }
	    return config('constants.default_image_url');
	}

	public function destinations()
	{
	    return $this->belongsToMany(Destination::class)->withTimestamps();
	}

	public function getLinkAttribute()
	{
	    return route('front.activities.show', ['slug' => $this->attributes['slug']]);
	}

	/**
	 * Get all of the seo.
	 */
	public function seo()
	{
	    return $this->morphOne('App\Seo', 'seoable');
    }

    public function trips()
    {
        return $this->belongsToMany(Trip::class, 'activity_trip', 'activity_id', 'trip_id');
    }

    public function parent()
    {
        return $this->hasOne('App\Activity', 'id', 'parent_id');
    }

    public function children()
    {
        return $this->hasMany('App\Activity', 'parent_id', 'id');
    }

    // public function destinations()
    // {
    //     return $this->belongsToMany(Destination::class, 'activity_destination', 'activity_id', 'id');
    // }
}
