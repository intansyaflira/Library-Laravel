<?php

namespace App\Http\Controllers;
use App\Models\peminjaman_buku_model;
use App\Models\detail_peminjaman_buku_model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Peminjaman_bukuController extends Controller
{
    public function show(){
        $data_peminjaman = DB::table('peminjaman_buku')
            ->join('siswa', 'siswa.id_siswa', '=' , 'peminjaman_buku.id_siswa')
            ->join('kelas', 'kelas.id_kelas', '=' , 'siswa.id_kelas')
            ->select('peminjaman_buku.id_peminjaman_buku', 'peminjaman_buku.id_siswa', 'siswa.nama_siswa', 'peminjaman_buku.tanggal_pinjam', 'peminjaman_buku.tanggal_kembali', 'kelas.nama_kelas', 'kelas.kelompok')
            ->get();
        return Response()->json($data_peminjaman);
    }
    
    public function detail($id)
    {
        if(peminjaman_buku_model::where('id_peminjaman_buku', $id)->exists()){
            $data_peminjaman = peminjaman_buku_model::join('siswa', 'siswa.id_siswa', 'peminjaman_buku.id_siswa') ->where('peminjaman_buku.id_peminjaman_buku', '=', $id)->get();
            return Response()->json($data_peminjaman);
        }
        else{
            return Response()->json(['message' => 'Tidak ditemukan']);
        }
    }

    public function destroy($id)
    {
        $hapus = peminjaman_buku_model::where('id_peminjaman_buku', $id)->delete();

        if($hapus) {
            return Response()->json(['status' => 1]);
        }

        else {
            return Response()->json(['status' => 0]);
        }
    }

    public function update($id, Request $request) {         
        $validator=Validator::make($request->all(),         
        [   
            'id_siswa'=>'required',
            'tanggal_pinjam'=>'required',
            'tanggal_kembali'=>'required'        
        ]); 

        if($validator->fails()) {             
            return Response()->json($validator->errors());         
        } 

        $ubah = peminjaman_buku_model::where('id_peminjaman_buku', $id)->update([             
            'id_siswa' =>$request->id_siswa,
            'tanggal_pinjam' =>$request->tanggal_pinjam,
            'tanggal_kembali' =>$request->tanggal_kembali
        ]); 

        if($ubah) {             
            return Response()->json(['status' => 1]);         
        }         
        else {             
            return Response()->json(['status' => 0]);         
        }     
    }

    public function store(Request $req)
    {
        $validator= Validator::make($req->all(), [
            'id_siswa'=>'required',
            'tanggal_pinjam'=>'required',
            'tanggal_kembali'=>'required',
            'detail' => 'required'
        ]);
        if($validator->fails()) {
            return Response()->json($validator->errors());
        }
        $peminjaman = new peminjaman_buku_model();
        $peminjaman->id_siswa = $req->id_siswa;
        $peminjaman->tanggal_pinjam = $req->tanggal_pinjam;
        $peminjaman->tanggal_kembali = $req->tanggal_kembali;
        $peminjaman->save();

        //insert detail peminjaman
        for($i = 0; $i < count($req->detail); $i++){
            $detail_peminjaman = new detail_peminjaman_buku_model();
            $detail_peminjaman->id_peminjaman_buku = $peminjaman->id_peminjaman_buku;
            $detail_peminjaman->id_buku = $req->detail[$i]['id_buku'];
            $detail_peminjaman->qty = $req->detail[$i]['qty'];
            $detail_peminjaman->save();
        }

        if($peminjaman && $detail_peminjaman){
            return response()->json([
                'status' => 1,
                'message' => 'Succes add data!',
                'data' => $peminjaman
            ]);
        } else {
            return Response() -> json([
                'status' => 0,
                'message' => 'Failed!'
            ]);
        }
    }
    public function tambahItem(Request $req, $id_peminjaman_buku)
    {
        $validator= Validator::make($req->all(), [
            'id_buku' => 'required',
            'qty' => 'required'
        ]);
        if($validator->fails()) {
            return Response()->json($validator->errors());
        }
        $save = detail_peminjaman_buku_model::create([
            'id_peminjaman_buku' => $req->id_peminjaman_buku,
            'id_buku' => $req->id_buku,
            'qty' => $req->qty
        ]);
        if($save) {
            return Response ()->json(['status'=>1]);
        } else {
            return Response ()->json(['status'=>0]);
        }
    }

}
