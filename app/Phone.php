<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    /**
     * The table that are mass assignable.
     *
     * @var array
     */
    protected $table = "phone";

    /* START : relation with user table */
    public function user(){
    	return $this->belongsTo('App\User');
    }
    /* END : relation with user table */
}
