<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\bukumodel;
use App\Models\peminjaman_buku_model;
use App\Models\detail_peminjaman_buku_model;
use App\Models\pengembalian_buku_model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class Bukucontroller extends Controller
{
    public function getbook(){
        $buku = bukumodel::get();
        return response()->json($buku);
    }

    // public function show()
    // {
    //     return bukumodel::all();
    // }

    public function show(){
        $data = DB::table('peminjaman_buku')
            ->join('siswa', 'siswa.id_siswa', '=' , 'peminjaman_buku.id_siswa')
            ->join('kelas', 'kelas.id_kelas', '=' , 'siswa.id_kelas')
            ->select('peminjaman_buku.id_peminjaman_buku', 'peminjaman_buku.id_siswa', 'siswa.nama_siswa', 'peminjaman_buku.tanggal_pinjam', 'peminjaman_buku.tanggal_kembali', 'kelas.nama_kelas', 'kelas.kelompok')
            ->whereNotIn('id_peminjaman_buku', function($query){
                $query -> select('id_peminjaman_buku')
                ->from('pengembalian_buku');
            })
            ->orderBy('id_peminjaman_buku')
            ->get();

            $result = [];
            for($i = 0; $i <count($data); $i++){
                $result[$i]['id_peminjaman_buku'] = $data[$i] -> id_peminjaman_buku;
                $result[$i]['nama_siswa'] = $data[$i] -> nama_siswa;
                $result[$i]['nama_kelas'] = $data[$i] -> nama_kelas;
                $result[$i]['kelompok'] = $data[$i] -> kelompok;
                $result[$i]['tanggal_pinjam'] = $data[$i] -> tanggal_pinjam;
                $result[$i]['tanggal_kembali'] = $data[$i] -> tanggal_kembali;

                $status = '';
                $current_date = Carbon::parse(date('Y-m-d'));
                $return_date = $data[$i] -> tanggal_kembali;
                if(strtotime($current_date) > strtotime($return_date)){
                    $status = 'Late';
                } else {
                    $status = 'On Schedule';
                }
                $result[$i]['status'] = $status;
            }
        return Response()->json($result);
    }

    public function detail($id)
    {
        if(bukumodel::where('id_buku', $id)->exists()){
            $data_buku= bukumodel::select('nama_buku', 'pengarang', 'deskripsi')->where('id_buku', '=', $id)->get();
            return Response()->json($data_buku);
        }
        else{
            return Response()->json(['message' => 'Tidak ditemukan']);
        }
    }

    public function destroy($id)
    {
        $hapus = bukumodel::where('id_buku', $id)->delete();

        if($hapus) {
            return response()->json([
                'status' => 1,
                'message' => 'Success delete  data!',
                'data' => $hapus       
            ]);    
        }

        else {
            return Response()->json(['status' => 0]);
        }
    }
    
    public function update($id, Request $request) {         
        $validator=Validator::make($request->all(),         
        [   
            'nama_buku'=>'required',
            'pengarang'=>'required',
            'deskripsi'=>'required'       
        ]); 

        if($validator->fails()) {             
            return Response()->json($validator->errors());         
        } 

        $ubah = bukumodel::where('id_buku', $id)->update([             
            'nama_buku' =>$request->nama_buku,
            'pengarang' =>$request->pengarang,
            'deskripsi' =>$request->deskripsi
        ]); 

        if($ubah) {             
            return response()->json([
                'status' => 1,
                'message' => 'Success update new data!',
                'data' => $ubah        
            ]);      
        } else {             
            return Response()->json(['status' => 0]);         
        }     
    }

    public function store(Request $req)
    {
        $validator= Validator::make($req->all(), [
            'nama_buku'=>'required',
            'pengarang'=>'required',
            'deskripsi'=>'required'
        ]);
        if($validator->fails()) {
            return Response()->json($validator->errors());
        }

        //input transaksi dulu
        $borrow = new peminjaman_buku_model();
        $borrow->id_siswa = $request->id_siswa;
        $borrow->tanggal_pinjam = date("Y-m-d");
        $borrow->tanggal_kembali = $request->tanggal_kembali;
        $borrow->save();

        //$book->id_peminjaman_buku

        //insert detail peminjaman
        for($i = 0; $i < count($request->detail); $i++){
            $borrow_detail = new detail_peminjaman_buku_model();
            $borrow_detail->id_peminjaman_buku = $borrow->id_peminjaman_buku;
            $borrow_detail->id_buku = $request->detail[$i]['id_buku'];
            $borrow_detail->qty = $request->detail[$i]['qty'];
            $borrow_detail->save();
        }

        if($borrow && $borrow_detail){
            return Response() -> json([
                'status' => 1,
                'message' => 'Success!'
            ]);
        } 
        else{
            return Response() -> json([
                'status' => 0,
                'message' => 'Failed!'
            ]);
        }
    }
    public function upload_cover_buku(Request $request, $id_buku){
        $validator=Validator::make($request->all(),
        [
            'cover_buku' => 'required|mimes:jpeg,png,jpg|max:2048',
        ]);
        if($validator->fails()) {
            return Response()->json($validator->errors());
        }

        //define nama file yang akan di upload
        $imageName = time().'.'.$request->cover_buku->extension();

        // proses upload
        $request->cover_buku->move(public_path('images'), $imageName);
        // $path = $request->cover_buku->storeAs('images', 'filename.jpg');

        $update=DB::table('buku')
            ->where('id_buku', '=', $id)
            ->update([
                'image' => $imageName
        ]);

        $data_buku = bukumodel::where('id_buku', '=', $id_buku)-> get();
        if($update){
            return Response() -> json([
                'status' => 1,
                'message' => 'Success upload book cover!',
                'data' => $data_buku
            ]);
        } else 
        {
            return Response() -> json([
                'status' => 0,
                'message' => 'Failed upload book cover!'
            ]);
        }
    }
}
