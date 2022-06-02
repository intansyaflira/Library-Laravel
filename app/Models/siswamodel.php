<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class siswamodel extends Model
{
    protected $table = 'siswa';
    protected $primarykey = 'id_siswa';
    public $fillable = ['nama_siswa','tanggal_lahir','gender','alamat','id_kelas', 'image'];
    public $timestamps = false;

    use HasFactory;
}
