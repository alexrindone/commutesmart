<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Company;
use App\Challenge;
use App\User;
use File;

class AdminController extends Controller
{
    protected $request;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, Response $response)
    {
        $this->middleware('auth');
        $this->request = $request;
        $this->response = $response;

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function companies()
    {
        // get all companies from database
        $companies = Company::orderBy('name')->get();
        $data = collect(['companies' => $companies]);
        return view('companies', ['data' => $data]);
    }

    public function listUsers()
    {
        $users = User::with('company')->orderBy('name')->get();
        $data = collect(['users' => $users]);
        return view('users', ['data' => $data]);
    }

    public function updateCaptains()
    {
        $users = $this->request->all();
        // need to loop through and update each captain
        $updated = collect($users)->each(function ($user) {
            // get user model
            $update_user = User::findOrFail($user['id']);
            $update_user->is_captain = (boolean) $user['is_captain'];
            $saved = $update_user->save();
            return $saved;
        });
        
        if (isset($updated) && count($updated) > 0) {
			// Success
			return response()->json(['status' => true, 'message' => 'Updated Successfully!']);
		}
	
		// Fail
		return response()->json(['status' => false, 'message' => 'An error occured updating users.', 'payload' => []]);
    }

    public function addCompany()
    {
        $form = $this->request->all();
        $created = Company::create($form);
        if ($created) {
			// Success
			return response()->json(['status' => true, 'message' => 'Added Successfully!', 'payload' => $created->toArray()]);
		}
	
		// Fail
		return response()->json(['status' => false, 'message' => 'An error occured adding the company', 'payload' => []]);
    }

    public function editCompany($id)
    {
        // Loop through each key and check against user, if it's different update it with updated user
        $company = Company::findOrFail($id);
        $updated_company = collect($this->request->all());
        $updated_fields = $updated_company->filter(function($value, $key) use ($company) {
            return $value != $company[$key];
        })->toArray();
        
        $updated = false;
        if (count($updated_fields) > 0) {
            // update user records with updated fields
            $updated = $company->update($updated_fields);
        }

        if ($updated) {
			// Success
			return response()->json(['status' => true, 'message' => 'Updated Successfully!', 'payload' => []]);
		}
	
		// Fail
		return response()->json(['status' => false, 'message' => 'An error occured adding the company', 'payload' => []]);

    }

    public function deleteCompany($company_id)
    {
        // find the company
        $company = Company::findOrFail($company_id);
        // delete the company
        $deleted = $company->delete();
        if ($deleted) {
			// Success
			return response()->json(['status' => true, 'message' => 'Deleted Successfully!', 'payload' => $company_id]);
		}
	
		// Fail
		return response()->json(['status' => false, 'message' => 'An error occured deleting the company', 'payload' => []]);
    }

    public function challenges()
    {
        // get all companies from database
        $challenges = Challenge::get();
        $data = collect(['challenges' => $challenges]);
        return view('challenges', ['data' => $data]);
    }

    public function addChallenge()
    {
        $form = $this->request->all();
        $created = Challenge::create($form);
        if ($created) {
			// Success
			return response()->json(['status' => true, 'message' => 'Added Successfully!', 'payload' => $created->toArray()]);
		}
	
		// Fail
		return response()->json(['status' => false, 'message' => 'An error occured adding the company', 'payload' => []]);
    }

    public function editChallenge($id)
    {
        // Loop through each key and check against user, if it's different update it with updated user
        $challenge = Challenge::findOrFail($id);
        $updated_challenge = collect($this->request->all());
        $updated_fields = $updated_challenge->filter(function($value, $key) use ($challenge) {
            return $value != $challenge[$key];
        })->toArray();
        
        $updated = false;
        if (count($updated_fields) > 0) {
            // update user records with updated fields
            $updated = $challenge->update($updated_fields);
        }

        if ($updated) {
			// Success
			return response()->json(['status' => true, 'message' => 'Updated Successfully!', 'payload' => []]);
		}
	
		// Fail
		return response()->json(['status' => false, 'message' => 'An error occured updating the challenge', 'payload' => []]);

    }

    public function deleteChallenge($challenge_id)
    {
        // find the company
        $challenge = Challenge::findOrFail($challenge_id);
        // delete the company
        $deleted = $challenge->delete();
        if ($deleted) {
			// Success
			return response()->json(['status' => true, 'message' => 'Deleted Successfully!', 'payload' => $challenge_id]);
		}
	
		// Fail
		return response()->json(['status' => false, 'message' => 'An error occured deleting the company', 'payload' => []]);
    }

    public function exportUserNameAddress() {
        // get user with name, address and trip count, need to make this dynamic by passing in specific challenge id
        $challenge = Challenge::where('id', 1)->first();
        $now =  date("Y-m-d");
        $users = User::whereHas('trips', function($query) use ($now, $challenge){
            $query->WhereDate('date', '<', $now);
        })->withCount('trips')->with('company:id,name')->get();
        $users = $users->transform(function($user){
            // add modes used to excel
            $modes = collect($user->trips)->pluck('mode')->unique()->implode(' - ');
            $name = explode(" ", $user['name']);
            if (is_array($name)) {
                $first = $name[0];
                $last = array_key_exists(1, $name) ? $name[1] : 'N/A';
            } else {
                $first = $user['name'];
            }
            return [
                'first' => $first,
                'last'  => isset($last) ? $last : 'N/A',
                'full'  => $user['name'],
                'email' => $user['email'],
                'street' => $user['street'],
                'city' => $user['city'],
                'state' => $user['state'],
                'zip' => $user['zip'],
                'company' => $user['company'] = $user['company']['name'],
                'trips_count' => $user['trips_count'],
                'modes' => $modes
            ];
        })->toArray();
        // set path for saving csv
        $path = storage_path(time() . '_userData.csv');
        $fp = fopen($path, 'wb');
        $i = 0;
        foreach ( $users as $user ) {
            if ($i == 0) {
                // make headers
                fputcsv($fp, ['First', 'Last', 'Full Name', 'Email', 'Street', 'City', 'State','Zip', 'Company', 'Trips', 'Modes']);
            }
            // put in user
            fputcsv($fp, $user);
            $i++;
        }
        fclose($fp);
        // download and delete file from server
	    return response()->download($path)->deleteFileAfterSend();
    }
}