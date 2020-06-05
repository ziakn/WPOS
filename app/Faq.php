<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Support\Filterable;

class Faq extends Model
{

    protected $fillable = [
    	'question', 'answer', 'show_on_pricing'
    ];
}
