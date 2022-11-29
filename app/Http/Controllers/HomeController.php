<?php

namespace App\Http\Controllers;

use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        
        if(!empty($request['rating1']) || !empty($request['rating2']) || !empty($request['rating3']) || !empty($request['rating4']) || !empty($request['rating5']) ) {
            if(isset($request['rating1'])) {
                $record = Record::where('user_id', Auth::id())->where('rating', '1')->get();
            }
            if(isset($request['rating2'])) {
                $record = Record::where('user_id', Auth::id())->where('rating', '2')->get();
            }
            if(isset($request['rating3'])) {
                $record = Record::where('user_id', Auth::id())->where('rating', '3')->get();
            }
            if(isset($request['rating4'])) {
                $record = Record::Where('user_id', Auth::id())->where('rating', '4')->get();
            }
            if(isset($request['rating5'])) {
                $record = Record::where('user_id', Auth::id())->where('rating', '5')->get();
            }
        }else {
            $record = Record::all()->where('user_id', Auth::id());
        }
        
        return view('home', [
            'records' => $record
        ]);
    }
}