<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bukumodel extends Model
{
    protected $table = 'buku';
    protected $primarykey = 'id_buku';
    public $fillable = ['nama_buku','pengarang','deskripsi', 'image'];
    public $timestamps = false;
    use HasFactory;
}
