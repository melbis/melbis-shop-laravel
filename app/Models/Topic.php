<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $table = 'ms_topic';
    public $timestamps = false;

    // Store
    public function goods()
    {
        return $this->belongsToMany(Store::class, 'ms_topic_store', 'topic_id', 'store_id');
    }
}