<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    public function numbers(){
        return $this->hasMany(Number::class);
    }
}
