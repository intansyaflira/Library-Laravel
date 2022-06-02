<?php

namespace App\Http\Controllers;

use App\Models\pengembalian_buku_model;
use App\Models\peminjaman_buku_model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Pengembalian_bukuController extends Controller
{
    // public function show()
    // {
    //     $data_pengembalian = pengembalian_buku_model::join('peminjaman_buku', 'peminjaman_buku.id_peminjaman_buku', 'pengembalian_buku.id_peminjaman_buku')->get();
    //     return Response()->json($data_pengembalian);
    // }
    
    // public function detail($id)
    // {
    //     if(pengembalian_buku_model::where('id_pengembalian_buku', $id)->exists()){
    //         $data_pengembalian = pengembalian_buku_model::join('peminjaman_buku', 'peminjaman_buku.id_peminjaman_buku', 'pengembalian_buku.id_peminjaman_buku') ->where('pengembalian_buku.id_pengembalian_buku', '=', $id)->get();
    //         return Response()->json($data_pengembalian);
    //     }
    //     else{
    //         return Response()->json(['message' => 'Tidak ditemukan']);
    //     }
    // }

    // public function update($id, Request $request) {         
    //     $validator=Validator::make($request->all(),         
    //     [   
    //         'id_peminjaman_buku'=>'required',
    //         'tanggal_kembali'=>'required',
    //         'denda'=>'required'        
    //     ]); 

    //     if($validator->fails()) {             
    //         return Response()->json($validator->errors());         
    //     } 

    //     $ubah = pengembalian_buku_model::where('id_pengembalian_buku', $id)->update([             
    //         'id_peminjaman_buku' =>$request->id_peminjaman_buku,
    //         'tanggal_kembali' =>$request->tanggal_kembali,
    //         'denda' =>$request->denda
    //     ]); 

    //     if($ubah) {             
    //         return Response()->json(['status' => 1]);         
    //     }         
    //     else {             
    //         return Response()->json(['status' => 0]);         
    //     }     
    // }

    // public function destroy($id)
    // {
    //     $hapus = pengembalian_buku_model::where('id_pengembalian_buku', $id)->delete();

    //     if($hapus) {
    //         return Response()->json(['status' => 1]);
    //     }

    //     else {
    //         return Response()->json(['status' => 0]);
    //     }
    // }

    public function mengembalikanBuku(Request $req)
    {
        $validator = Validator::make($req->all(),[
            'id_peminjaman_buku'=>'required'
        ]);
        if($validator->fails()){
            return Response()->json($validator->errors());
        }
        $cek_kembali=pengembalian_buku_model::where('id_peminjaman_buku',$req->id_peminjaman_buku);
        if($cek_kembali->count() == 0){
            $dt_kembali = peminjaman_buku_model::where('id_peminjaman_buku',$req->id_peminjaman_buku)->first();
            $tanggal_sekarang = Carbon::now()->format('Y-m-d');
            $tanggal_kembali = new Carbon($dt_kembali->tanggal_kembali);
            $dendaperhari = 1500;

            if(strtotime($tanggal_sekarang) > strtotime($tanggal_kembali)){
                $jumlah_hari = $tanggal_kembali->diff($tanggal_sekarang)->days;
                $denda = $jumlah_hari*$dendaperhari;
            } //else {
            //     $denda = 0;
            // }

            $save = pengembalian_buku_model::create([
                'id_peminjaman_buku'    => $req->id_peminjaman_buku,
                'tanggal_pengembalian'  => $tanggal_sekarang,
                'denda'                 => $denda,
            ]);
            if($save){
                $data['status'] = 1;
                $data['message'] = 'Berhasil dikembalikan';
            } else {
                $data['status'] = 0;
                $data['message'] = 'Pengembalian gagal';
            }
            } else {
            $data = ['status'=>0,'message'=>'Sudah pernah dikembalikan'];
        }
            return response()->json($data);
    }

}
