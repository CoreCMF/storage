<?php

namespace CoreCMF\Storage\Http\Models;

use Schema;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    public $table = 'storage_configs';

    protected $fillable = ['service', 'client_id', 'client_secret', 'redirect'];

}
