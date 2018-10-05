<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Trip;
use App\User;
use App\Company;
use App\Challenge;

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
    public function individual()
    {
        // sort by modes?
        $modes = ['Bus/Train', 'Bicycle', 'Moped', 'Multi-Modal', 'Walk/Run', 'Skateboard/Rollerblades'];
        $challenge = Challenge::where('id', 1)->first();
        // get all users with trips
        $users = User::whereHas('trips')->with('trips')->with('company')->get();
        $data = collect(['users' => $users, 'modes' => $modes, 'challenge' => $challenge]);
        return view('individual-leaderboard', ['data' => $data]);
    }

    public function companiesLeaderboard()
    {
        // sort by modes?
        $modes = ['Bus/Train', 'Bicycle', 'Moped', 'Multi-Modal', 'Walk/Run', 'Skateboard/Rollerblades'];
        $challenge = Challenge::where('id', 1)->first();
        // get all users with trips
        $companies = Company::whereHas('users')->with('users.trips')->get();
        $data = collect(['companies' => $companies, 'modes' => $modes, 'challenge' => $challenge]);
        return view('companies-leaderboard', ['data' => $data]);
    }
}
