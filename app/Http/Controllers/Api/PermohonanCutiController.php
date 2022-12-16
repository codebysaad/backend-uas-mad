<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\PermohonanCuti;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PermohonanCutiController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index(){
        //get attendance
        $id = Auth::user()->id;
        $permohonanCuti = PermohonanCuti::join('jeniscuti','permohonan_cutis.jns_cuti','=','jeniscuti.id')
        ->where('permohonan_cutis.id_user','=',$id)
        ->orderBy('created_at','DESC')
        ->get();

        return new PostResource(true, 'List Permohonan Cuti', $permohonanCuti);
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
            'alasan' => 'required',
            'jns_cuti' => 'required',
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required',
        ]);
        
        //if validations fail
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        //create post attendance
        $attendance = PermohonanCuti::create([
            'id_user'    => $user->id,
            'jns_cuti' => $request->jns_cuti,
            'alasan'      => $request->alasan,
            'tgl_awal'     => $request->tgl_awal,
            'tgl_akhir'      => $request->tgl_akhir,
            'status'       => "pending",
            'tgl_status'      => null,
        ]);

        if($attendance) {
            //return response
            return new PostResource(true, 'Permohonan cuti successful', $attendance);
        } else {
            return new PostResource(false, 'Permohonan cuti unsuccessful', null);
        }
    }

    /**
     * show
     *
     * @param  mixed $post
     * @return void
     */
    public function show(PermohonanCuti $permohonanCuti){
        return new PostResource(true, 'Permohonan cuti founded!', $permohonanCuti);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $post
     * @return void
     */
    public function update(Request $request, PermohonanCuti $permohonanCuti){
        $validator = Validator::make($request->all(), [
            'alasan' => 'required',
            'jns_cuti' => 'required',
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required',
            'status'      => 'required',
            'tgl_status'       => 'required',
        ]);
        //if validator fail
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $permohonanCuti->update([
            'jns_cuti' => $request->jns_cuti,
            'alasan'      => $request->alasan,
            'tgl_awal'     => $request->tgl_awal,
            'tgl_akhir'      => $request->tgl_akhir,
            'status'  => $request->status,
            'tgl_status'    => $request->tgl_status,
        ]);

        return new PostResource(true, 'Cuti Updated!', $permohonanCuti);
    }

    /**
     * destroy
     *
     * @param  mixed $post
     * @return void
     */
    public function destroy(PermohonanCuti $permohonanCuti){
        //delete post
        $permohonanCuti->delete();

        //return response
        return new PostResource(true, 'Cuti Deleted!', null);
    }
}
