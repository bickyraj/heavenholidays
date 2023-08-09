<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
	protected $guard = ['id'];

	protected $appends = ['imageUrl', 'thumbImageUrl', 'link'];

	public function getImageUrlAttribute()
	{
	    if (isset($this->attributes['image_name']) && !empty($this->attributes['image_name'])) {
	        $image_url = url('/storage/regions');
	    	return $image_url . '/' . $this->attributes['id'] . '/' . $this->attributes['image_name'];
        }
	    return asset('assets/front/') . config('constants.default_hero_banner');
	}

	public function getThumbImageUrlAttribute()
	{
	    if (isset($this->attributes['image_name']) && !empty($this->attributes['image_name'])) {
	        $image_url = url('/storage/regions');
	    	return $image_url . '/' . $this->attributes['id'] . '/thumb_' . $this->attributes['image_name'];
	    }
	    return config('constants.default_image_url');
	}

	public function destinations()
	{
	    return $this->belongsToMany(Destination::class)->withTimestamps();
	}

	public function activities()
	{
	    return $this->belongsToMany(Activity::class)->withTimestamps();
	}

	public function getLinkAttribute()
	{
	    return route('front.regions.show', ['slug' => $this->attributes['slug']]);
	}

	/**
	 * Get all of the seo.
	 */
	public function seo()
	{
	    return $this->morphOne('App\Seo', 'seoable');
	}
}
