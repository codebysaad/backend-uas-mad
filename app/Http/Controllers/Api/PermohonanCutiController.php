<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\PermohonanCuti;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        ->orderBy('permohonan_cutis.created_at','DESC')
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

        //create post cuti
        $cuti = PermohonanCuti::create([
            'id_user'    => $user->id,
            'jns_cuti' => $request->jns_cuti,
            'alasan'      => $request->alasan,
            'tgl_awal'     => $request->tgl_awal,
            'tgl_akhir'      => $request->tgl_akhir,
            'status'       => false,
            'tgl_status'      => null,
        ]);

        if($cuti) {
            //return response
            return new PostResource(true, 'Permohonan cuti successful', $cuti);
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
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id'     => 'required',
            'alasan' => 'required',
            'jns_cuti' => 'required',
            'tgl_awal' => 'required',
            'tgl_akhir' => 'required',
            'status'      => 'required',
        ]);
        //if validator fail
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $date = Carbon::now()->format('Y-m-d');

        $updated = PermohonanCuti::where('id',$request->id)
        ->update([
            'jns_cuti' => $request->jns_cuti,
            'alasan'      => $request->alasan,
            'tgl_awal'     => $request->tgl_awal,
            'tgl_akhir'      => $request->tgl_akhir,
            'status'  => $request->status,
            'tgl_status'    => $date,
        ]);

        if($updated) {
            return new PostResource(true, 'Cuti Updated!', $updated);
        } else {
            return new PostResource(false, 'Cuti Gagal Updated!', null);
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
        $delete = PermohonanCuti::where('id',$request->id)
        ->delete();

        //return response
        if($delete) {
            return new PostResource(true, 'Permohonan Cuti Deleted!', $delete);
        } else {
            return new PostResource(false, 'Permohonan Cuti Gagal Deleted!', null);
        }
    }
}
