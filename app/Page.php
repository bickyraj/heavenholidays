<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
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
            $image_url = url('/storage/pages');
        	return $image_url . '/' . $this->attributes['id'] . '/' . $this->attributes['image_name'];
        }
        return config('constants.default_hero_banner');
    }

    public function getThumbImageUrlAttribute()
    {
        if (isset($this->attributes['image_name']) && !empty($this->attributes['image_name'])) {
            $image_url = url('/storage/pages');
        	return $image_url . '/' . $this->attributes['id'] . '/thumb_' . $this->attributes['image_name'];
        }
        return config('constants.default_image_url');
    }

    /**
     * Get the post's menu_item.
     */
    public function menu_item()
    {
        return $this->morphOne('App\MenuItem', 'menu_itemable');
    }

    public function getLinkAttribute()
    {
        return route('front.pages.show', ['slug' => $this->attributes['slug']]);
    }

    /**
     * Get all of the seo.
     */
    public function seo()
    {
        return $this->morphOne('App\Seo', 'seoable');
    }
}
