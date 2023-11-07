<?php

namespace App\Http\Controllers\Api\User;

use App\Models\Workschedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    //store work schedule time
    public function store(Request $request, $user_id)
    {
        $request->validate([
            'day' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'status' => '',
        ]);


        //validate day
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        if (!in_array($request->day, $days)) {
            return response()->json([
                'status' => 400,
                'message' => 'Invalid day',
            ]);
        }

        //check if schedule already exist
        $schedule = Workschedule::where('user_id', $user_id)->where('day', $request->day)->first();
        if ($schedule) {
            return response()->json([
                'status' => 400,
                'message' => 'Schedule already exist',
            ]);
        }

        $schedule = new Workschedule();
        $schedule->user_id = $user_id;
        $schedule->day = $request->day;
        $schedule->start_time = $request->start_time;
        $schedule->end_time = $request->end_time;
        $schedule->save();

        return response()->json([
            'status' => 200,
            'message' => 'Schedule created successfully',
            'schedule' => $schedule,
        ]);
    }

    //update work schedule time
    public function update(Request $request, $user_id, $schedule_id)
    {
        $request->validate([
            //'day' => 'required',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'status' => 'nullable',
        ]);

        //validate day
        //$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        //if (!in_array($request->day, $days)) {
        //    return response()->json([
        //        'status' => 400,
        //        'message' => 'Invalid day',
        //    ]);
        //}

        //check if schedule already exist
        //$schedule = Workschedule::where('user_id', $user_id)->where('day', $request->day)->first();
        //if ($schedule) {
        //    return response()->json([
        //        'status' => 400,
        //        'message' => 'Schedule already exist',
        //    ]);
        //}

        $schedule = Workschedule::find($schedule_id);
        //$schedule->user_id = $user_id;
        //$schedule->day = $request->day;
        $schedule->start_time = $request->start_time;
        $schedule->end_time = $request->end_time;
        $schedule->status = $request->status;
        $schedule->save();

        return response()->json([
            'status' => 200,
            'message' => 'Schedule updated successfully',
            'schedule' => $schedule,
        ]);
    }
}
