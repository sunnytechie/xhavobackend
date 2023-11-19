<?php

namespace App\Http\Controllers\Api\Report;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    //store report
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'merchant_id' => 'required',
            'report' => 'required',
        ]);

        $report = new Report();
        $report->user_id = $request->user_id;
        $report->merchant_id = $request->merchant_id;
        $report->report = $request->report;
        $report->save();

        return response()->json([
            'status' => true,
            'message' => 'Report submitted successfully',
            'data' => $report,
        ]);
    }
}
