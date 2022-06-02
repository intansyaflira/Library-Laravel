<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class detail_peminjaman_buku_model extends Model
{
    protected $table = 'detail_peminjaman_buku';
    protected $primaryKey = 'id_detail_peminjaman_buku';
    public $fillable = ['id_peminjaman_buku','id_buku','qty'];
    public $timestamps = false;
    
    public function buku() {
        return $this->belongsTo('App\Models\bukumodel','id_buku','id_buku');
    }
}
