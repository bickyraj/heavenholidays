<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TripInfo extends Model
{
	protected $guarded = ['id'];

	public function trip()
	{
		return $this->belongsTo(Trip::class);
	}
}
