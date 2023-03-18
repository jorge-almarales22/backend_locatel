<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function createAccount(Request $request)
    {
        try {

            $credentials = $request->only('name','value_initial');   

            $validator = Validator::make($credentials, [
                'name' => 'required|string',
                'value_initial' => 'required|numeric',
            ]);
    
            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'message' => 'The fields are not correct',
                    'errors' => $validator->errors()
                ],422);
            }

            $user = new User();
            $user->name = $request->name;
            $user->save();

            $account = new Account();
            $account->balance = $request->value_initial;
            $account->account_number = mt_rand(10000000, 99999999);
            $account->user_id = $user->id;
            $account->save();

            $account->save();

            return response()->json([
                'success' => true,
                'message' => 'Account created',
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Account not found!'], 404);
        }
    }

    public function consignMoney(Request $request)
    {
        try {

            $credentials = $request->only('value_to_consign','accountNumber');   

            $validator = Validator::make($credentials, [
                'value_to_consign' => 'required|numeric',
                'accountNumber' => 'required|numeric',
            ]);
    
            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'message' => 'The fields are not correct',
                    'errors' => $validator->errors()
                ],422);
            }

            $account = Account::FindOrFail($request->accountNumber);

            $account->balance += $request->value_to_consign;

            $account->save();

            return response()->json($account->balance, 200);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Account not found!'], 404);
        }
    }

    public function withdrawals(Request $request)
    {
        try {

            $credentials = $request->only('value_to_withdraw','accountNumber');   

            $validator = Validator::make($credentials, [
                'value_to_withdraw' => 'required|numeric',
                'accountNumber' => 'required|numeric',
            ]);
    
            if($validator->fails()){
                return response()->json([
                    'success' => false,
                    'message' => 'The fields are not correct',
                    'errors' => $validator->errors()
                ],422);
            }

            $account = Account::FindOrFail($request->accountNumber);

            if($request->value_to_withdraw > $account->balance) return response()->json(["error" => "The value to withdraw is greater than the amount you have in the account"], 500);

            $account->balance = $account->balance - $request->value_to_withdraw;

            $account->save();

            return response()->json($account->balance, 200);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Account not found!'], 404);
        }
    }

    public function balanceAccount($accountNumber)
    {
        try {

            if(!is_numeric($accountNumber)):
                return response()->json([
                    'success' => false,
                    'message' => 'The fields are not correct',
                    'errors' => "the account number must be numeric"
                ],422);
            endif;

            $account = Account::FindOrFail($accountNumber);

            return response()->json($account->balance, 200);

        } catch (\Exception $e) {
            return response()->json(['message'=>'Account not found!'], 404);
        }
        
    }
}
