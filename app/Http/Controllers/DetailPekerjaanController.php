<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pekerjaan;
use Illuminate\Http\Request;
use App\Models\DetailPekerjaan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DetailPekerjaanController extends Controller
{

    public function index()
    {
        $data = DetailPekerjaan::all();
        return response()->json($data);
    }


    public function create(Request $request, $id)
    {
        $this->validate($request, [
            'id_user' => 'required',
            'id_pekerjaan' => 'required',
            'nama_pekerjaan' => 'required',
            'desc_pekerjaan' => 'required',
            'jam_kerja' => 'required',
            'tgl_kerja' => 'required',
            'tipe' => 'required',
            
        ]);
        

        $data = [
            'id_user' => $request->input('id_user'),
            'id_pekerjaan' => $request->input('id_pekerjaan'),
            'nama_pekerjaan' => $request->input('nama_pekerjaan'),
            'desc_pekerjaan' => $request->input('desc_pekerjaan'),
            'jam_kerja' => $request->input('jam_kerja'),
            'tgl_kerja' => $request->input('tgl_kerja'),
            'tipe' => $request->input('tipe')
        ];

        $detailPekerjaan = DetailPekerjaan::where('id_user', $id)->create($data);

        if($detailPekerjaan){
            return $this->successAdd($detailPekerjaan);
        }else{
            return $this->error("Gagal Menambahkan Data");
        }

    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $data = DetailPekerjaan::where('id',$id)->get();
        return response()->json([
            'code' => 400,
            'message' => 'Berhasil Mengakses',
            'data' => $data
        ]);
    }


    public function edit(DetailPekerjaan $detailPekerjaan)
    {
        //
    }

    public function updateTask(Request $request, DetailPekerjaan $detail)
    {
        //define validation rules
        $validator = Validator::make($request->all(), [
            'title'     => 'required',
            'content'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //check if image is not empty
        if ($request->hasFile('image')) {

            //upload image
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            //delete old image
            Storage::delete('public/posts/'.$detail->image);

            //update post with new image
            $detail->update([
                'image'     => $image->hashName(),
                'title'     => $request->title,
                'content'   => $request->content,
            ]);

        } else {

            //update post without image
            $detail->update([
                'title'     => $request->title,
                'content'   => $request->content,
            ]);
        }

        //return response
        return new DetailPekerjaan([true, 'Data Post Berhasil Diubah!', $detail]);
    }

    public function update(Request $request, $id)
    {
        // $user = User::select('users.*')
        //     ->where('id', $id)
        //     ->from('users')
        //     ->first();
        //define validation rules
        $validator = Validator::make($request->all(), [
            'nama_pekerjaan' => 'required',
            'desc_pekerjaan' => 'required',
            'jam_kerja' => 'required',
            'tgl_kerja' => 'required',
            'tipe' => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $image = $request->file('bukti_pekerjaan');

        // $image = $request->file('bukti_pekerjaan')->getClientOriginalName();
        // $request->file('bukti_pekerjaan')->move('upload', $image);

        $data = [
            'nama_pekerjaan' => $request->nama_pekerjaan,
            'desc_pekerjaan' => $request->desc_pekerjaan,
            'jam_kerja' => $request->jam_kerja,
            'tgl_kerja' => $request->tgl_kerja,
            'tipe' => $request->tipe,
            // 'bukti_pekerjaan' => url('upload/' . $image),
        ];

        $user = DetailPekerjaan::where('id', $id)->update($data);
        

        if(DetailPekerjaan::where('id', $request->id)->firstOrFail()){
            $user = DetailPekerjaan::where('id', $id)->first();
            
            $result = [
                'code' => 200,
                'message' => 'Data Berhasil Diubah',
                'data' => $user
            ];
        }else{
            $result = [
                'code' => 400,
                'message' => 'Data Gagal Diubah',
                'data' => []
            ];
        }

        return response()->json($result);
    }


    public function destroy($id)
    {
        $user = User::select('users.*')
            ->where('id', $id)
            ->from('users')
            ->first();

        $pk = DetailPekerjaan::select('detail_pekerjaan.*')
            ->where('id', $id)
            ->from('detail_pekerjaan')
            ->first();
        
        $detail = DetailPekerjaan::select('detail_pekerjaan.*')
            ->join('users', 'users.id', '=', 'detail_pekerjaan.id_user')
            ->join('pekerjaan', 'pekerjaan.id', '=', 'detail_pekerjaan.id_pekerjaan')
            ->where('detail_pekerjaan.id', $pk->id)
            ->delete();
 
            return response()->json([
                'code' => 0,
                'message' => 'Berhasil menghapus.',
                'data' => $detail
            ]);
    }


    public function search($namaPekerjaan)
    {
        if($namaPekerjaan){
            return DetailPekerjaan::where("nama_pekerjaan", "like", "%".$namaPekerjaan."%")->get();
        }else{
            return $this->error("Data Tidak Ditemukan");
        }
    }


    public function successAdd($data, $message = "Data Berhasil Ditambah"){
        return response()->json([
            'code' => 200,
            'message' => $message,
            'data' => $data
        ]);
    }

    public function successShow($data){
        return response()->json([
            'code' => 200,
            'data' => $data
        ]);
    }

    public function successEdit($data, $message = "Data Berhasil Dirubah"){
        return response()->json([
            'code' => 200,
            'message' => $message,
            'data' => $data
        ]);
    }


    public function error($message){
        return response()->json([
            'code' => 400,
            'message' => $message
        ], 400);
    }
}
