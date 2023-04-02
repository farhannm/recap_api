<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Pekerjaan;
use Illuminate\Http\Request;
use App\Models\DetailPekerjaan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json([
            'code' => 200,
            'message' => 'Berhasil mengakses',
            'jumlah_karyawan' => User::count(),
            'data' => User::all()
        ]);
    }

    public function countKaryawan()
    {  

        $data = User::select(DB::raw('COUNT(users.id) as jumlah_karyawan'))
            ->first();
 
        if ($data == null) {
            return response()->json([
                'code' => 200,
                'message' => 'Tidak ada data task hari ini',
            ]);
        } else {
            return response()->json([
                'code' => 200,
                'message' => 'Berhasil mengakses.',
                'data' => $data
            ]);
        }
    }

    public function getCurrentMonth($id)
    {   
        $data = User::select('users.*')
            ->where('id', $id)
            ->from('users')
            ->first();
        
        $detail = DetailPekerjaan::select('users.id', 'users.nama', 'pekerjaan.bulan', DB::raw('sum(detail_pekerjaan.jam_kerja) as jam_kerja'))
            ->join('users', 'users.id', '=', 'detail_pekerjaan.id_user')
            ->join('pekerjaan', 'pekerjaan.id', '=', 'detail_pekerjaan.id_pekerjaan')
            ->where('detail_pekerjaan.id_user', $data->id)
            ->where('bulan', $this->convertMonth(date('n')))
            ->groupBy('detail_pekerjaan.id_user')
            ->first();
 
        if ($detail == null) {
            return response()->json([
                'code' => 200,
                'message' => 'Tidak ada data.',
            ]);
        } else {
            return response()->json([
                'code' => 200,
                'message' => 'Berhasil mengakses.',
                'data' => $detail
            ]);
        }
    }

    public function getSelectedTotalJam($id, $id_pk)
    {   
        $user = User::select('users.*')
            ->where('id', $id)
            ->from('users')
            ->first();

        $pk = Pekerjaan::select('pekerjaan.*')
            ->where('id', $id_pk)
            ->from('pekerjaan')
            ->first();
        
        $detail = DetailPekerjaan::select('users.id', 'users.nama', 'pekerjaan.bulan', DB::raw('sum(detail_pekerjaan.jam_kerja) as jam_kerja'))
            ->join('users', 'users.id', '=', 'detail_pekerjaan.id_user')
            ->join('pekerjaan', 'pekerjaan.id', '=', 'detail_pekerjaan.id_pekerjaan')
            ->where('detail_pekerjaan.id_user', $user->id)
            ->where('pekerjaan.id', $pk->id)
            ->groupBy('detail_pekerjaan.id_user')
            ->first();
 
        if ($detail == null) {
            return response()->json([
                'code' => 200,
                'message' => 'Tidak ada data.',
            ]);
        } else {
            return response()->json([
                'code' => 200,
                'message' => 'Berhasil mengakses.',
                'data' => $detail
            ]);
        }
    }

    public function getTodayTask($id)
    {   
        $data = User::select('users.*')
            ->where('id', $id)
            ->from('users')
            ->first();

        // $detail = DetailPekerjaan::select('users.id', 'users.nama', 'pekerjaan.bulan', 'detail_pekerjaan.*')
        //     ->join('users', 'users.id', '=', 'detail_pekerjaan.id_user')
        //     ->join('pekerjaan', 'pekerjaan.id', '=', 'detail_pekerjaan.id_pekerjaan')
        //     ->where('detail_pekerjaan.id_user', $data->id)
        //     ->where('tgl_kerja', '>=', Carbon::today())
        //     ->groupBy('detail_pekerjaan.id_user')
        //     ->first();

        $detail = DetailPekerjaan::select('users.id', 'users.nama', 'pekerjaan.bulan', 'detail_pekerjaan.*', DB::raw('COUNT(detail_pekerjaan.id) as hari_ini'))
            ->join('users', 'users.id', '=', 'detail_pekerjaan.id_user')
            ->join('pekerjaan', 'pekerjaan.id', '=', 'detail_pekerjaan.id_pekerjaan')
            ->where('detail_pekerjaan.id_user', $data->id)
            ->where('tgl_kerja', '>=', Carbon::today())
            ->groupBy('detail_pekerjaan.id')
            ->get();
 
        if ($detail == null) {
            return response()->json([
                'code' => 200,
                'message' => 'Tidak ada data task hari ini',
            ]);
        } else {
            return response()->json([
                'code' => 200,
                'message' => 'Berhasil mengakses.',
                'data' => $detail
            ]);
        }
    }

    public function countTodayTask($id)
    {   
        $data = User::select('users.*')
            ->where('id', $id)
            ->from('users')
            ->first();

        // $detail = DetailPekerjaan::select('users.id', 'users.nama', 'pekerjaan.bulan', 'detail_pekerjaan.*')
        //     ->join('users', 'users.id', '=', 'detail_pekerjaan.id_user')
        //     ->join('pekerjaan', 'pekerjaan.id', '=', 'detail_pekerjaan.id_pekerjaan')
        //     ->where('detail_pekerjaan.id_user', $data->id)
        //     ->where('tgl_kerja', '>=', Carbon::today())
        //     ->groupBy('detail_pekerjaan.id_user')
        //     ->first();

        $detail = DetailPekerjaan::select('users.id', DB::raw('COUNT(detail_pekerjaan.id) as hari_ini'))
            ->join('users', 'users.id', '=', 'detail_pekerjaan.id_user')
            ->join('pekerjaan', 'pekerjaan.id', '=', 'detail_pekerjaan.id_pekerjaan')
            ->where('detail_pekerjaan.id_user', $data->id)
            ->where('tgl_kerja', '>=', Carbon::today())
            ->groupBy('detail_pekerjaan.id_user')
            ->first();
 
        if ($detail == null) {
            return response()->json([
                'code' => 200,
                'message' => 'Tidak ada data task hari ini',
            ]);
        } else {
            return response()->json([
                'code' => 200,
                'message' => 'Berhasil mengakses.',
                'data' => $detail
            ]);
        }
    }


    public function userTaskByMonth($id, $id_pk)
    {   
        $user = User::select('users.*')
            ->where('id', $id)
            ->from('users')
            ->first();

        $pk =  Pekerjaan::select('pekerjaan.*')
        ->where('id', $id_pk)
        ->from('pekerjaan')
        ->first();

        $detail = DetailPekerjaan::select('users.id', DB::raw('detail_pekerjaan.*'))
            ->join('pekerjaan', 'pekerjaan.id', '=', 'detail_pekerjaan.id_pekerjaan')
            ->join('users', 'users.id', '=', 'detail_pekerjaan.id_user')
            ->where('users.id', $user->id)
            ->where('pekerjaan.id', $pk->id)
            ->get();
 
        if ($detail == null) {
            return response()->json([
                'code' => 200,
                'message' => 'Tidak ada data task bulanan.',
            ]);
        } else {
            return response()->json([
                'code' => 200,
                'message' => 'Berhasil mengakses.',
                'data' => $detail
            ]);
        }
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        $data = User::where('id',$id)->get();
        return response()->json($data);
    }


    public function edit(User $user)
    {
        //
    }

    public function upload(Request $request, $id)
    {
        $this->validate($request, [
            'image' => 'required'
        ]);

        $image = $request->file('image')->getClientOriginalName();
        $request->file('image')->move('upload', $image);

        $data = [
            'image' => url('upload/' . $image),
        ];

        $user = User::where('id', $id)->update($data);
        

        if(User::where('id', $request->id)->firstOrFail()){
            $user = User::where('id', $id)->first();
            
            $result = [
                'code' => 200,
                'message' => 'Data Berhasil Ditambahkan',
                'data' => $user
            ];
        }else{
            $result = [
                'code' => 200,
                'message' => 'Data Tidak Bisa Ditambahkan',
                'data' => []
            ];
        }

        return response()->json($result, 200);

    
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nama' => 'unique:users',
            'email' => 'unique:users',
            'password' => 'min:8',
            'telp' => 'unique:users'
        ]);
        $user = User::where('id', $id)->update(array_merge($request->all(),[
            'password' => Hash::make($request->input('password'))
        ]));

        if($user){
            return $this->successEdit("Data berhasil diubah");
        }else{
            return $this->error('Gagal merubah data');
        }
    }

 
    public function destroy($id)
    {
        $delete = User::where('id', $id)->delete();
        return response()->json("Berhasil menghapus $delete");
    }

    public function search($nama)
    {
        return User::where("nama", "like", "%".$nama."%")->get();
    }


    public function successShow($data)
    {
        return response()->json([
            'code' => 200,
            'message' => 'Berhasil mengakses',
            'data' => $data
        ]);
    }

    public function successEdit($message = "Data Berhasil Dirubah")
    {
        return response()->json([
            'code' => 200,
            'message' => $message,
        ]);
    }

    public function error($message){
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
