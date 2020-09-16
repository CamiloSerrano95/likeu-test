<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['client_id', 'subject', 'date_time', 'status'];
}
