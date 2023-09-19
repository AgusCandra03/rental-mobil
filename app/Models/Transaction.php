<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    
    protected $fillable = ['tgl_mulai', 'tgl_selesai', 'id_mobil', 'status'];

    public function cars()
    {
        return $this->belongsTo('App\Models\Car', 'id_mobil');
    }
}
