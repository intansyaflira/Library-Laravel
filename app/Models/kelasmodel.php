<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kelasmodel extends Model
{
    use HasFactory;
    protected $table = 'kelas';
    protected $primarykey = 'id_kelas';
    public $timestamps = false;
    protected $fillable = ['nama_kelas','kelompok'];
}
