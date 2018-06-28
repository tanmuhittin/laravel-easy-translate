<?php

namespace TanMuhittin\LaraTranslate\Entities;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
	protected $table = 'laratranslate_translations';
	public $timestamps = false;

    public function translatable()
    {
        return $this->morphTo();
    }
}