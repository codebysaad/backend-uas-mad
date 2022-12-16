<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermohonanCuti;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        $permohonanCuti = PermohonanCuti::where('id_user','=',$id)
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
            'alasan'      => $request->alasan,
            'tgl_awal'     => $request->tgl_awal,
            'tgl_akhir'      => $request->tgl_akhir,
            'status'       => "pending",
            'tgl_status'      => null,
        ]);

        if($attendance) {
            //return response
            return new PostResource(true, 'Attendance successful', $attendance);
        } else {
            return new PostResource(false, 'Attendance unsuccessful', null);
        }
    }

    /**
     * show
     *
     * @param  mixed $post
     * @return void
     */
    public function show(PermohonanCuti $permohonanCuti){
        return new PostResource(true, 'Permohonan founded!', $permohonanCuti);
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
            'status'      => 'required',
            'tgl_status'       => 'required',
        ]);
        //if validator fail
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $permohonanCuti->update([
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
