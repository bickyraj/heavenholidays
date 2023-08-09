<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
	public function menu_items()
	{
		return $this->hasMany(MenuItem::class);
	}
}
