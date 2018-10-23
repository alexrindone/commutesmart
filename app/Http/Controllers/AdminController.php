<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Company;
use App\Challenge;

class AdminController extends Controller
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
    public function companies()
    {
        // get all companies from database
        $companies = Company::orderBy('name')->get();
        $data = collect(['companies' => $companies]);
        return view('companies', ['data' => $data]);
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
}