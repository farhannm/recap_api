<?php

namespace App\Http\Controllers;

use App\Models\DetailPekerjaan;
use Carbon\Carbon;
use App\Models\Pekerjaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PekerjaanController extends Controller
{
    
    public function index()
    {
        $data = Pekerjaan::select('pekerjaan.*')
            ->from('pekerjaan')
            ->get();
 
        if ($data->isEmpty()) {
            return response()->json([
                'code' => 200,
                'message' => 'Tidak ada data pekerjaan saat ini',
            ]);
        } else {
            return response()->json([
                'code' => 200,
                'message' => 'Berhasil mengakses.',
                'data' => $data
            ]);
        }
    }

    public function currentMonth()
    {
        $data = Pekerjaan::select('pekerjaan.*')
            ->from('pekerjaan')
            ->where('bulan', $this->convertMonth(date('n')))
            ->first();
 
        if ($data == null) {
            return response()->json([
                'code' => 200,
                'message' => 'Tidak ada data pekerjaan saat ini',
            ]);
        } else {
            return response()->json([
                'code' => 200,
                'message' => 'Berhasil mengakses.',
                'data' => $data
            ]);
        }
    }

    public function detail($id)
    {
        $data = Pekerjaan::select('pekerjaan.*')
            ->where('id', $id)
            ->from('pekerjaan')
            ->first();
        
        $detail = DetailPekerjaan::select('users.nama', 'users.jabatan', DB::raw('sum(detail_pekerjaan.jam_kerja) as jam_kerja'))
            ->join('users', 'users.id', '=', 'detail_pekerjaan.id_user')
            ->where('id_pekerjaan', $data->id)
            ->groupBy('id_user')
            ->get();
 
        if ($detail->isEmpty()) {
            return response()->json([
                'code' => 200,
                'message' => 'Tidak ada data task bulan ini',
            ]);
        } else {
            return response()->json([
                'code' => 200,
                'message' => 'Berhasil mengakses.',
                'data' => $detail
            ]);
        }
    }

    public function getSelectedPekerjaan($id)
    {
        $data = Pekerjaan::select('bulan', 'start', 'end', 'total_jam')->where('id', $id)->first();
 
        if ($data == null) {
            return response()->json([
                'code' => 200,
                'message' => 'Tidak ada data bulan.',
            ]);
        } else {
            return response()->json([
                'code' => 200,
                'message' => 'Berhasil mengakses.',
                'data' => $data
            ]);
        }
    }
    

    public function create(Request $request)
    {
        $this->validate($request, [
            'bulan' => 'required',
            'start' => 'required',
            'end' => 'required',
            'jam_toleransi' => 'required',
            'total_jam' => 'required'
        ]);

        $data = [
            'bulan' => $request->input('bulan'),
            'start' => $request->input('start'),
            'end' => $request->input('end'),
            'jam_toleransi' => $request->input('jam_toleransi'),
            'total_jam' => $request->input('total_jam')
        ];

        $pekerjaan = Pekerjaan::create($data);
        if($pekerjaan){
            $token = User::where('id', 1)->first()->api_token;
            return response()->json([
                'code' => 200,
                'message' => 'data berhasil ditambah',
                'token' => $token,
                'data'=> $pekerjaan
            ]);
        }else{
            return $this->error("Gagal Menambahkan Data");
        }
    }


    public function store(Request $request)
    {
        //
    }


    public function show($idUser)
    {
        // $currentTime = Carbon::now();
        // // `dd($currentTime)`;
        // $currentTime->toDateTimeString();
        $data = Pekerjaan::where('id_user',$idUser)->get();
        
        if($data){
            return $this->successShow($data);
        }else{
            return $this->error('Gagal menampilkan data');
        }
    }

  
    public function edit(Pekerjaan $pekerjaan)
    {
        //
    }

  
    public function update(Request $request, $id)
    {
        Pekerjaan::where('id', $id)->update($request->all());
        return response()->json('Data Berhasil Diubah');
    }


    public function destroy(Pekerjaan $pekerjaan)
    {
        return response()->json("Berhasil menghapus $pekerjaan");
    }


    public function search($bulan)
    {
        return Pekerjaan::where("bulan", "like", "%".$bulan."%")->get();
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


    public function error($message = "Gagal"){
        return response()->json([
            'code' => 400,
            'message' => $message
        ], 400);

    }

    private function convertMonth($value)
    {
        $bulan = array (
            'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);

        $convert = $bulan[$value-1];
        return $convert;
    }
}
