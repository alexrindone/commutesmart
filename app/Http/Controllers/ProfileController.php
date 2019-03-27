<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Company;
use App\User;
use App\File;

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

    public function myTeam()
    {
        // check if team captain
        // get company of team captain
        if (Auth::user()->is_captain) {
            $company_id = Auth::user()->company->id;
            $user = Auth::user();
            $users = User::select('name', 'email')->where('company_id', $company_id)->get()->toArray();
            $data = collect(['users' => $users, 'user' => $user]);
            return view('team', ['data' => $data]);
        }
        return redirect('home');
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

    public function exportMyTeam() {
        if (Auth::user()->is_captain) {
            // get user with name, address and trip count, need to make this dynamic by passing in specific challenge id
            $users = User::select('name', 'email')->where('company_id', Auth::user()->company_id)->get();
            $users = $users->transform(function($user){
                return [
                    'name'  => $user['name'],
                    'email' => $user['email']
                ];
            })->toArray();
            // set path for saving csv
            $path = storage_path(time() . Auth::user()->company_id . '_teamData.csv');
            $fp = fopen($path, 'wb');
            $i = 0;
            foreach ( $users as $user ) {
                if ($i == 0) {
                    // make headers
                    fputcsv($fp, ['Name', 'Email']);
                }
                // put in user
                fputcsv($fp, $user);
                $i++;
            }
            fclose($fp);
            // download and delete file from server
            return response()->download($path)->deleteFileAfterSend();
        }
        return redirect('home');
    }
}
