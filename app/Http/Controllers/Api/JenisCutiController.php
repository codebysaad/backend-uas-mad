<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Models\JenisCuti;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class JenisCutiController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index(){
        //get jenis cuti
        $jnsCuti = JenisCuti::get();

        return new PostResource(true, 'List Jenis Cuti', $jnsCuti);
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
            'jenis_cuti' => 'required',
            'deskripsi' => 'required',
        ]);
        
        //if validations fail
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $jnsCuti = JenisCuti::create([
            'jenis_cuti'    => $request->jenis_cuti,
            'deskripsi'      => $request->deskripsi,
        ]);

        if($jnsCuti) {
            //return response
            return new PostResource(true, 'Jenis Cuti berhasil ditambahkan', $jnsCuti);
        } else {
            return new PostResource(false, 'Jenis Cuti gagal ditambahkan', null);
        }
    }

    /**
     * show
     *
     * @param  mixed $post
     * @return void
     */
    public function show(JenisCuti $jnsCuti){
        return new PostResource(true, 'Jenis Cuti founded!', $jnsCuti);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $post
     * @return void
     */
    public function update(Request $request, JenisCuti $jnsCuti){
        //get current user
        $user = Auth::user();
        //input rules
        $validator = Validator::make($request->all(), [
            'jenis_cuti'      => 'required',
            'deskripsi'       => 'required',
        ]);
        //if validator fail
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        //update post without image
        $jnsCuti->update([
            'jenis_cuti'    => $request->jenis_cuti,
            'deskripsi'      => $request->deskripsi,
        ]);

        return new PostResource(true, 'Jenis Cuti Updated!', $jnsCuti);
    }

    /**
     * destroy
     *
     * @param  mixed $post
     * @return void
     */
    public function destroy(JenisCuti $jnsCuti){
        //delete post
        $jnsCuti->delete();

        //return response
        return new PostResource(true, 'Jenis Cuti Deleted!', null);
    }
}
