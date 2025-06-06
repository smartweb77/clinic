<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model {

    protected $fillable = array('name', 'email','subject','message', 'seen', 'date');

    public $timestamps = false;

    public static function allItems()
    {
        return Message::orderBy('id','DESC')->paginate(10);
    }
}
