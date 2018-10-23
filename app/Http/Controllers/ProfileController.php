<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Company;
use App\User;

class ProfileController extends Controller
{
    protected $request;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function myProfile()
    {
        $user = Auth::user();
        $companies = Company::orderBy('name')->get();
        $data = collect(['user' => $user, 'companies' => $companies]);
        return view('profile', ['data' => $data]);
    }

    public function userUpdate()
    {
        // Loop through each key and check against user, if it's different update it with updated user
        $user = Auth::user();
        $updated_user = collect($this->request->all());
        $updated_fields = $updated_user->filter(function($value, $key) use ($user) {
            return $value != $user[$key];
        })->toArray();
        
        $updated = false;
        if (count($updated_fields) > 0) {
            // update user records with updated fields
            $updated = $user->update($updated_fields);
        }

        if ($updated) {
			// Success
			return response()->json(['status' => true, 'message' => 'Updated Successfully!', 'payload' => []]);
		}
	
		// Fail
		return response()->json(['status' => false, 'message' => 'An error occured updating profile', 'payload' => []]);

    }
}
