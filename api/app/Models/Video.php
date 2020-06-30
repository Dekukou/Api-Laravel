<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use Notifiable;

    protected $table = 'video';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'duration', 'source', 'view', 'enable', 'user_id'
    ];
}
