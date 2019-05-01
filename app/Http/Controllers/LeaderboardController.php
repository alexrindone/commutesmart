<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Trip;
use App\User;
use App\Company;
use App\Challenge;
use Carbon\Carbon;
class LeaderboardController extends Controller
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
    public function individual($slug)
    {
        $challenge = Challenge::where('slug', $slug)->first();
        if ($challenge) {
            // sort by modes?
            $modes = ['Bus/Train', 'Bicycle', 'Moped', 'Multi-Modal', 'Walk/Run', 'Skateboard/Rollerblades'];
            $challenge = Challenge::first();
            $now =  date("Y-m-d");
            // get all users with trips
            $users = User::whereHas('trips', function($query) use ($now){
                $query->where('date', '<=', $now);
            })->with('trips')->with('company')->whereHas('company')->withCount('trips')->get();
            $data = collect(['users' => $users, 'modes' => $modes, 'challenge' => $challenge]);
            return view('individual-leaderboard', ['data' => $data]);
        }
        return redirect('home');
    }

    public function companiesLeaderboard($slug)
    {
        $challenge = Challenge::where('slug', $slug)->first();

        if ($challenge) {
            // Telework, Carpool, Vanpool added for B2B challenge
            // sort by modes?
            $modes = ['Bus/Train', 'Bicycle', 'Moped', 'Multi-Modal', 'Walk/Run', 'Skateboard/Rollerblades', 'Telework', 'Carpool', 'Vanpool'];
            $sizes = ['Micro', 'Small', 'Medium', 'Large', 'Major'];
            $today = date('Y-m-d'); // get today's date
            // get all users with trips -- old way
            // $companies = Company::with('users')->whereHas('users.trips', function($query) use ($challenge, $today){
            //     return $query->where('challenge_id', '=' , $challenge->id)
            //             ->where('date', '>=', $challenge->start_date)->where('date', '=', $today);
            //     // dd($query);
            // })->with('users.trips')->get();
            $companies = Company::whereHas('users.trips', function($query) use ($challenge, $today){
                return $query->where('challenge_id', $challenge->id)
                            ->where('date', '<=', $today)
                            ->where('date', '>=', $challenge->start_date);
            })->with(array('users.trips' => function($query) use ($challenge, $today) {
                return $query->where('challenge_id', $challenge->id)
                            ->where('date', '<=', $today)
                            ->where('date', '>=', $challenge->start_date);
            }))->get();
            $data = collect(['companies' => $companies, 'modes' => $modes, 'sizes' => $sizes, 'challenge' => $challenge]);
            return view('companies-leaderboard', ['data' => $data]);
        }
        return redirect('home');
    }

    public function individualCompanyLeaderboard($company_id, $slug)
    {
        $company = Company::findOrFail($company_id);
        $challenge = Challenge::where('slug', $slug)->first(); 
        if ($company && $challenge) {
            // sort by modes?
            $modes = ['Bus/Train', 'Bicycle', 'Moped', 'Multi-Modal', 'Walk/Run', 'Skateboard/Rollerblades'];
            $now =  date("Y-m-d");
            // get all users with trips
            $users = User::whereHas('trips', function($query) use ($challenge){
                $query->where('challenge_id', $challenge->id);
            })->with('trips')->with('company')->withCount('trips')->where('company_id', $company_id)->get();
            $data = collect(['users' => $users, 'modes' => $modes, 'challenge' => $challenge]);
            return view('individual-leaderboard', ['data' => $data]);
        }
        return redirect('home');
    }
}
