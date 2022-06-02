<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\siswamodel;

class Siswacontroller extends Controller
{
    public function show()
    {
        $data_siswa = siswamodel::join('kelas', 'kelas.id_kelas', 'siswa.id_kelas')->get();
        return Response()->json($data_siswa);
    }
    
    public function detail($id)
    {
        if(siswamodel::where('id_siswa', $id)->exists()){
            $data_siswa = siswamodel::join('kelas', 'kelas.id_kelas', 'siswa.id_kelas') ->where('siswa.id_siswa', '=', $id)->get();
            return Response()->json($data_siswa);
        }
        else{
            return Response()->json(['message' => 'Tidak ditemukan']);
        }
    }

    public function destroy($id)
    {
        $hapus = siswamodel::where('id_siswa', $id)->delete();

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
            'nama_siswa'=>'required',
            'tanggal_lahir'=>'required',
            'gender'=>'required',
            'alamat'=>'required',
            'id_kelas' =>'required'          
        ]); 

        if($validator->fails()) {             
            return Response()->json($validator->errors());         
        } 

        $ubah = siswamodel::where('id_siswa', $id)->update([             
            'nama_siswa' =>$request->nama_siswa,
            'tanggal_lahir' =>$request->tanggal_lahir,
            'gender' =>$request->gender,
            'alamat' =>$request->alamat,
            'id_kelas' =>$request->id_kelas,  
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
            'nama_siswa'=>'required',
            'tanggal_lahir'=>'required',
            'gender'=>'required',
            'alamat'=>'required',
            'id_kelas' =>'required'
        ]);
        if($validator->fails()) {
            return Response()->json($validator->errors());
        }
        $save = siswamodel::create([
            'nama_siswa' =>$req->nama_siswa,
            'tanggal_lahir' =>$req->tanggal_lahir,
            'gender' =>$req->gender,
            'alamat' =>$req->alamat,
            'id_kelas' =>$req->id_kelas,
        ]);
        if($save) {
            return response()->json([
                'status' => 1,
                'message' => 'Success create new data!',
                'data' => $save
            ]);
        } else {
            return Response ()->json(['status'=>0]);
        }
    
    }
    public function upload_foto_siswa(Request $request, $id_siswa){
        $validator=Validator::make($request->all(),
        [
            'foto_siswa' => 'required|mimes:jpeg,png,jpg|max:2048',
        ]);
        if($validator->fails()) {
            return Response()->json($validator->errors());
        }

        //define nama file yang akan di upload
        $imageName = time().'.'.$request->foto_siswa->extension();

        // proses upload
        $request->foto_siswa->move(public_path('images'), $imageName);
        // $path = $request->cover_buku->storeAs('images', 'filename.jpg');

        $update = siswamodel :: where('id_siswa',  $id_siswa)
        ->update([
            'image' => $imageName
        ]);

        $data_siswa = siswamodel::where('id_siswa', '=', $id_siswa)-> get();
        if($update){
            return Response() -> json([
                'status' => 1,
                'message' => 'Sukses upload foto siswa!',
                'data' => $data_siswa
            ]);
        } else 
        {
            return Response() -> json([
                'status' => 0,
                'message' => 'Gagal upload foto siswa!'
            ]);
        }
    }
}
