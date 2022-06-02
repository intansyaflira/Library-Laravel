<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class peminjaman_buku_model extends Model
{
    protected $table = 'peminjaman_buku';
    protected $primaryKey = 'id_peminjaman_buku';
    public $fillable = ['id_siswa','tanggal_pinjam','tanggal_kembali'];
    public $timestamps = false;

    
    public function siswa() {
        return $this->belongsTo('App\Models\siswamodel','id_siswa','id_siswa');
    }
}
