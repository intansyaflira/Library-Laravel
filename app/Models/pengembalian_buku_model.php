<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pengembalian_buku_model extends Model
{
    protected $table = 'pengembalian_buku';
    protected $primarykey = 'id_pengembalian_buku';
    public $fillable = ['id_peminjaman_buku','tanggal_pengembalian','denda'];
    public $timestamps = false;
    use HasFactory;
}
