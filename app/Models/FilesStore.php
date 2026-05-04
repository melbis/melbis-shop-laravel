<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class FilesStore extends Model
{
    protected $table = 'ms_files_store';
    public $timestamps = false;

    public function getUrlAttribute()
    {        
        if ( empty($this->upload_time) || $this->upload_time === '2000-01-01 00:00:00' ) 
        {
            return ''; 
        }

        $date = Carbon::parse($this->upload_time);
        $y = $date->format('Y');
        $m = $date->format('m');
        $d = $date->format('d');
        $h = $date->format('H');
        $n = $date->format('i'); 

        return "/files/{$y}/{$m}_{$d}/{$h}_{$n}/{$this->file_name}";
    }    
}