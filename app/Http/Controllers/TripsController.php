<?php

namespace App\Http\Controllers;

use Mail;
use App\Mail\LogMail;
use App\Mail\AdminLogMail;
use App\Mail\LevelOneMail;
use App\Mail\LevelTwoMail;
use App\Mail\LevelThreeMail;
use App\Mail\LevelFourMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Trip;
use App\User;
use App\Challenge;

class TripsController extends Controller
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
    public function trips()
    {
        // $modes = ['Bus/Train', 'Bicycle', 'Moped', 'Multi-Modal', 'Walk/Run', 'Skateboard/Rollerblades', 'Telework', 'Carpool', 'Vanpool'];
        $modes = ['Bus/Train', 'Bicycle', 'Moped', 'Multi-Modal', 'Walk/Run', 'Skateboard/Rollerblades'];
        // get all user trips, maybe we should limit how many? It might not matter
        $trips = Trip::where('user_id', Auth::user()->id)->with('challenge')->orderBy('date')->get();
        // get challenges for form dropdown
        $challenges = Challenge::where('start_date', '<=', date('Y-m-d'))->where('end_date', '>=', date('Y-m-d'))->get();
        $user = Auth::user();
        $data = collect(['user' => $user, 'trips' => $trips, 'challenges' => $challenges, 'modes' => $modes]);
        return view('trips', ['data' => $data]);
    }

    protected function sendEmail($status)
    {
        $emails = [
            'level_one'   => new LevelOneMail(Auth::user()),
            'level_two'   => new LevelTwoMail(Auth::user()),
            'level_three' => new LevelThreeMail(Auth::user()),
            'level_four'  => new LevelFourMail(Auth::user())
        ];
        
        return Mail::to(Auth::user()->email)->bcc('arugg@commutesmartseacoast.org')->send($emails[$status]);
    }

    public function addTrip()
    {
        $form = $this->request->all();

        // set user id
        $form['user_id'] = Auth::user()->id;
        $tripDates = $form['dates'];
        // loop through each date
        $tripsToAdd = collect($tripDates)->map(function($date) use ($form) {
            return [
                'date' => $date,
                'mode' => $form['mode'],
                'miles' => $form['miles'],
                'challenge_id' => $form['challenge_id'],
                'user_id' => $form['user_id']
            ];
        })->toArray();
        $created = Trip::insert($tripsToAdd);
        if ($created) {
            // check if it's their first set of trips, if it is send a thank you for logging their first trip
            // if ($form['first_trips']) {
                // Mail::to(Auth::user()->email)->send(new LogMail(Auth::user()));
                // Mail::to('arugg@commutesmartseacoast.org')->send(new AdminLogMail(Auth::user()));
            // }

            // send email based on user status
            // if (isset($form['trips_total'])) {
            //     // old trip count to set status
            //     $trip_count = $form['trips_total'];
            //     // new trip count to set new status
            //     $new_trip_count = $trip_count + count($form['dates']);
            //     // calculate old user status
            //     if ($trip_count < 16) {
            //         $user_status = 'level_one';
            //     } elseif($trip_count >= 16 && $trip_count < 31) {
            //         $user_status = 'level_two';
            //     } elseif($trip_count >= 31 && $trip_count < 46){
            //         $user_status = 'level_three';
            //     } elseif($trip_count >= 46) {
            //         $user_status = 'level_four';
            //     } else {
            //         // Do nothing
            //     }
            //     // calculate new user status
            //     if ($new_trip_count < 16) {
            //         $new_user_status = 'level_one';
            //     } elseif($new_trip_count >= 16 && $new_trip_count < 31) {
            //         $new_user_status = 'level_two';
            //     } elseif($new_trip_count >= 31 && $new_trip_count < 46){
            //         $new_user_status = 'level_three';
            //     } elseif($new_trip_count >= 46) {
            //         $new_user_status = 'level_four';
            //     } else {
            //         // Do nothing
            //     }
            //     // check if new or old status matches, if it doesn't send email
            //     if ($user_status != $new_user_status) {
            //         $status = $new_user_status;
            //         // send email
            //         $sent = $this->sendEmail($status);
            //     }
            // }

            // get all trips
            $trips = Trip::where('user_id', Auth::user()->id)->with('challenge')->orderBy('date')->get();
			// Success
			return response()->json(['status' => true, 'message' => 'Trip(s) Added Successfully!', 'payload' => $trips]);
		}
	
		// Fail
		return response()->json(['status' => false, 'message' => 'An error occured adding the company', 'payload' => []]);
    }

    public function editTrip($id)
    {
        // Loop through each key and check against user, if it's different update it with updated user
        $trip = Trip::findOrFail($id);
        $updated_trip = collect($this->request->all());
        $updated_fields = $updated_trip->filter(function($value, $key) use ($trip) {
            return $value != $trip[$key];
        })->toArray();
        
        $updated = false;
        if (count($updated_fields) > 0) {
            // update user records with updated fields
            $updated = $trip->update($updated_fields);
        }

        if ($updated) {
			// Success
			return response()->json(['status' => true, 'message' => 'Updated Successfully!', 'payload' => []]);
		}
	
		// Fail
		return response()->json(['status' => false, 'message' => 'An error occured updating the challenge', 'payload' => []]);

    }

    public function deleteTrip($trip_id)
    {
        // find the trip
        $trip = Trip::where('id', $trip_id)->where('user_id', Auth::user()->id)->first();
        // delete the trip
        $deleted = $trip->delete();
        if ($deleted) {
			// Success
			return response()->json(['status' => true, 'message' => 'Deleted Successfully!', 'payload' => $trip_id]);
		}
	
		// Fail
		return response()->json(['status' => false, 'message' => 'An error occured deleting the trip', 'payload' => []]);
    }
}
