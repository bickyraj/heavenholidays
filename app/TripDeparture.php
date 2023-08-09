<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TripDeparture extends Model
{
    protected $guarded = ['id'];
	// status = [1 = guarenteed, 2 = limited]
	protected $appends = ['statusInfo'];

	public function trip()
	{
		return $this->belongsTo(Trip::class);
	}

	public function getStatusInfoAttribute()
	{
		if ($this->status == 1) {
			return "Guarenteed";
		} else {
			return "Limited";
		}
	}
}
