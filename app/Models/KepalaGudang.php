<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KepalaGudang extends Model
{
      protected $fillable = [
        'id_users'
    ];

     public function user(){
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
