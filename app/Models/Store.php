<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = 'ms_store';
    public $timestamps = false; 

    // Images
    public function images()
    {        
        return $this->hasMany(FilesStore::class, 'elem_id', 'id')
                    ->where('kind_key', 'kDefault')
                    ->orderBy('pos', 'asc');
    }

    // Topics
    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'ms_topic_store', 'store_id', 'topic_id');
    }
}