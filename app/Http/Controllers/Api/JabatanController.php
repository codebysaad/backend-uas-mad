<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Models\Jabatan;
use Illuminate\Support\Facades\Validator;

class JabatanController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index(){
        //get list jabatan
        $jabatan = Jabatan::get();

        return new PostResource(true, 'Daftar Jabatan', $jabatan);
    }
    /**
     * store
     *
     * @param  mixed $request
     * @return void
     */
    public function store(Request $request){
        //validate rules input
        $validator = Validator::make($request->all(), [
            'nama_jabatan' => 'required',
            'tugas' => 'required',
        ]);
        
        //if validations fail
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $jabatan = Jabatan::create([
            'nama_jabatan'    => $request->nama_jabatan,
            'tugas'      => $request->tugas,
        ]);

        if($jabatan) {
            //return response
            return new PostResource(true, 'Jabatan berhasil ditambahkan', $jabatan);
        } else {
            return new PostResource(false, 'Jabatan gagal ditambahkan', null);
        }
    }

    /**
     * show
     *
     * @param  mixed $post
     * @return void
     */
    public function show(jabatan $jabatan){
        return new PostResource(true, 'Jenis Cuti founded!', $jabatan);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $post
     * @return void
     */
    public function update(Request $request){
        //input rules
        $validator = Validator::make($request->all(), [
            'id'              => 'required',
            'nama_jabatan'      => 'required',
            'tugas'       => 'required',
        ]);
        //if validator fail
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        //update post without image
        $updated = jabatan::where('id', $request->id)
        ->update([
            'nama_jabatan'    => $request->nama_jabatan,
            'tugas'      => $request->tugas,
        ]);

        if($updated) {
            return new PostResource(true, 'Jabatan Berhasil di Updated!', $updated);
        } else {
            return new PostResource(false, 'Jabatan Gagal Updated!', null);
        }
    }

    /**
     * destroy
     *
     * @param  mixed $post
     * @return void
     */
    public function destroy(Request $request){
        //delete post
        // $delete = $jnsCuti->delete();
        $delete = jabatan::where('id', $request->id)->delete();

        //return response
        if($delete) {
            return new PostResource(true, 'Jabatan Deleted!', null);
        } else {
            return new PostResource(false, 'Jabatan Gagal Deleted!', null);
        }
    }
}
