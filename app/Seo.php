<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    protected $appends = ['socialImageUrl'];

	/**
	 * Get the owning seoable model.
	 */
	public function seoable()
	{
	    return $this->morphTo();
	}

	public function getSocialImageUrlAttribute()
	{
	    if (isset($this->attributes['social_image']) && !empty($this->attributes['social_image'])) {
	        $image_url = url('/storage/seos');
	    	return $image_url . '/' . $this->attributes['id'] . '/' . $this->attributes['social_image'];
	    }
	    return '';
	}
}
