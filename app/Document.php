<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
	protected $guarded = ['id'];

    protected $appends = ['fileUrl'];

    public function getFileUrlAttribute()
    {
        if (isset($this->attributes['file']) && !empty($this->attributes['file'])) {
            $image_url = url('/storage/documents');
        	return $image_url . '/' . $this->attributes['id'] . '/' . $this->attributes['file'];
        }
        return config('constants.default_image_url');
    }
}
