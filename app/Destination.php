<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
	protected $guarded = ['id'];

    protected $appends = ['imageUrl', 'thumbImageUrl', 'link'];

    /**
     * Get all of the post's comments.
     */
    public function menu_items()
    {
        return $this->morphMany('App\MenuItem', 'menu_itemable');
    }

    public function getImageUrlAttribute()
    {
        if (isset($this->attributes['image_name']) && !empty($this->attributes['image_name'])) {
            $image_url = url('/storage/destinations');
        	return $image_url . '/' . $this->attributes['id'] . '/' . $this->attributes['image_name'];
        }
	    return asset('assets/front/') . config('constants.default_hero_banner');
    }

    public function getThumbImageUrlAttribute()
    {
        if (isset($this->attributes['image_name']) && !empty($this->attributes['image_name'])) {
            $image_url = url('/storage/destinations');
        	return $image_url . '/' . $this->attributes['id'] . '/thumb_' . $this->attributes['image_name'];
        }
        return config('constants.default_image_url');
    }

    public function getLinkAttribute()
    {
        return route('front.destinations.show', ['slug' => $this->attributes['slug']]);
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
        return $this->belongsToMany(Trip::class, 'destination_trip', 'destination_id', 'trip_id');
    }
}
