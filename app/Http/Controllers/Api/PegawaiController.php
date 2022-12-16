<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\PermohonanCuti;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PegawaiController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index(){
        //get attendance
        $id = Auth::user()->id;
        $pegawai = User::join('pegawais','users.id','=','pegawais.id_user')
        ->where('pegawais.id_user','=',$id)
        ->orderBy('created_at','DESC')
        ->get();

        return new PostResource(true, 'Data Pegawai', $pegawai);
    }
    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request){
        //get current auth
        $user = Auth::user();
        //validate rules input
        $validator = Validator::make($request->all(), [
            'id_user' => 'required',
            'nama_lengkap' => 'required',
            'alamat' => 'required',
            'tmpt_lahir' => 'required',
            'tgl_lahir' => 'required',
        ]);
        
        //if validations fail
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        //create post attendance
        $pegawai = Pegawai::create([
            'id_user'    => $user->id,
            'nama_lengkap' => $request->nama_lengkap,
            'alamat'      => $request->alamat,
            'tmpt_lahir'     => $request->tmpt_lahir,
            'tgl_lahir'      => $request->tgl_lahir,
        ]);

        if($pegawai) {
            //return response
            return new PostResource(true, 'Data Pegawai berhasil ditambahkan', $pegawai);
        } else {
            return new PostResource(false, 'Data Pegawai gagal ditambahkan', null);
        }
    }

    /**
     * show
     *
     * @param  mixed $post
     * @return void
     */
    public function show(Pegawai $pegawai){
        return new PostResource(true, 'Data Pegawai founded!', $pegawai);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $post
     * @return void
     */
    public function update(Request $request, PermohonanCuti $pegawai){
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required',
            'alamat' => 'required',
            'tmpt_lahir' => 'required',
            'tgl_lahir' => 'required',
        ]);
        //if validator fail
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $pegawai->update([
            'nama_lengkap' => $request->nama_lengkap,
            'alamat'      => $request->alamat,
            'tmpt_lahir'     => $request->tmpt_lahir,
            'tgl_lahir'      => $request->tgl_lahir,
        ]);

        return new PostResource(true, 'Data Pegawai Updated!', $pegawai);
    }

    /**
     * destroy
     *
     * @param  mixed $post
     * @return void
     */
    public function destroy(Pegawai $pegawai){
        //delete post
        $pegawai->delete();

        //return response
        return new PostResource(true, 'Data Pegawai Deleted!', null);
    }
}
