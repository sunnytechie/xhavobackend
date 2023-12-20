<?php

namespace App\Http\Controllers\Api\Stash;

use App\Models\Bank;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BankController extends Controller
{
    public function index($user_id)
    {
        //get user
        $user = User::find($user_id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $banks = Bank::where('user_id', $user_id)->get();

        return response()->json([
            'status' => true,
            'message' => 'Bank accounts retrieved successfully',
            'data' => $banks
        ], 200);
    }
    
    public function store(Request $request)
    {
        //validate request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'bank_name' => 'required|string',
            'account_name' => 'required|string',
            'account_number' => 'required|string',
        ]);

        //get user
        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        $bank = new Bank();
        $bank->user_id = $request->user_id;
        $bank->bank_name = $request->bank_name;
        $bank->account_name = $request->account_name;
        $bank->account_number = $request->account_number;
        $bank->save();

        return response()->json([
            'status' => true,
            'message' => 'Bank account saved successfully',
            'data' => $bank
        ], 200);

    }

    public function delete($id)
    {
        //get bank
        $bank = Bank::find($id);
        if (!$bank) {
            return response()->json([
                'status' => false,
                'message' => 'Bank account not found'
            ], 404);
        }

        $bank->delete();

        return response()->json([
            'status' => true,
            'message' => 'Bank account deleted successfully'
        ], 200);
    }
}
