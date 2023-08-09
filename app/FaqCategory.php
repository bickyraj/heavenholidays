<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FaqCategory extends Model
{
	protected $guarded = ['id'];

	public function faqs()
	{
		return $this->hasMany(Faq::class);
	}
}
