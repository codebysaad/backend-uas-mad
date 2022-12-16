<?php

namespace App\Http\Controllers\Api;

use App\Models\Attendance;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index(){
        //get attendance
        $id = Auth::user()->id;
        $attendance = Attendance::where('id_user','=',$id)
        ->orderBy('created_at','DESC')
        ->get();

        return new PostResource(true, 'List Attendance', $attendance);
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
            // 'id_user' => 'required',
            'type' => 'required',
            // 'attend' => 'required',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'long' => 'required',
            'lat' => 'required',
            // 'date' => 'required',
        ]);
        
        //if validations fail
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        //upload photo
        $photo = $request->file('photo');
        $photo->storeAs('public/attendance', $photo->hashName());
        // $photoePath = asset('attendance' . $photo->hashName());
        $time = Carbon::now()->format('H:i:m');
        $date = Carbon::now()->format('Y-m-d');

        //create post attendance
        $attendance = Attendance::create([
            'id_user'    => $user->id,
            'type'      => $request->type,
            'attend'     => $time,
            'photo'   => $photo->hashName(),
            'long'      => $request->long,
            'lat'       => $request->lat,
            'date'      => $date,
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
    public function show(Attendance $attendance){
        return new PostResource(true, 'Attendance founded!', $attendance);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $post
     * @return void
     */
    public function update(Request $request, Attendance $attendance){
        //get current user
        $user = Auth::user();
        //input rules
        $validator = Validator::make($request->all(), [
            'photoOut'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'attOut'    => 'required',
            'long'      => 'required',
            'lat'       => 'required',
        ]);
        //if validator fail
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        //check if image is not empty
        if ($request->hasFile('photoOut')) {

            //upload image
            $photoOut = $request->file('photoOut');
            $photoOut->storeAs('public/attendance', $photoOut->hashName());

            //delete old image
            Storage::delete('public/attendance/'.$attendance->photoOut);

            //update post with new image
            $attendance->update([
                'photoOut'  => $photoOut->hashName(),
                'attOut'    => $request->attOut,
                'long'      => $request->long,
                'lat'       => $request->lat,
            ]);

        } else {

            //update post without image
            $attendance->update([
                'attOut'    => $request->attOut,
                'long'      => $request->long,
                'lat'      => $request->lat,
            ]);
        }

        return new PostResource(true, 'Attendance Updated!', $attendance);
    }

    /**
     * destroy
     *
     * @param  mixed $post
     * @return void
     */
    public function destroy(Attendance $attendance){
        //delete photo
        // Storage::delete('public/attendance/'.$attendance->photoIn);
        // Storage::delete('public/attendance/'.$attendance->photoOut);
        Storage::delete('public/attendance/'.$attendance->photo);

        //delete post
        $attendance->delete();

        //return response
        return new PostResource(true, 'Attendance Deleted!', null);
    }
}
