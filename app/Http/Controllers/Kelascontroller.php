<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\kelasmodel;
use Illuminate\Support\Facades\Validator;

class Kelascontroller extends Controller
{
    public function show()
    {
        return kelasModel::all();
    }

    public function detail($id)
    {
        if(kelasmodel::where('id_kelas', $id)->exists()){
            $data_kelas= kelasmodel::select('nama_kelas', 'kelompok')->where('id_kelas', '=', $id)->get();
            return Response()->json($data_kelas);
        }
        else{
            return Response()->json(['message' => 'Tidak ditemukan']);
        }
    }

    public function update($id, Request $request) {         
        $validator=Validator::make($request->all(),         
        [   
            'nama_kelas'=>'required',
            'kelompok'=>'required'        
        ]); 

        if($validator->fails()) {             
            return Response()->json($validator->errors());         
        } 

        $ubah = kelasmodel::where('id_kelas', $id)->update([             
            'nama_kelas' =>$request->nama_kelas,
            'kelompok' =>$request->kelompok  
        ]); 

        if($ubah) {             
            return response()->json([
                'status' => 1,
                'message' => 'Success update data!',
                'data' => $ubah
            ]);         
        }         
        else {             
            return Response()->json(['status' => 0]);         
        }     
    }

    public function destroy($id)
    {
        $hapus = kelasmodel::where('id_kelas', $id)->delete();

        if($hapus) {
            return response()->json([
                'status' => 1,
                'message' => 'Success hapus data!',
                'data' => $hapus
            ]);
        }

        else {
            return Response()->json(['status' => 0]);
        }
    }

    public function store(Request $req)
    {
        $validator= Validator::make($req->all(), [
            'nama_kelas'=>'required',
            'kelompok'=>'required'
        ]);
        if($validator->fails()) {
            return Response()->json($validator->errors());
        }
        $save = kelasModel::create([
            'nama_kelas' =>$req->nama_kelas,
            'kelompok' =>$req->kelompok
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
}
