<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class ScheduledEventController extends Controller
{
    public function getEventsCalender(Request $request)
    {
        $user = auth()->user();
        if(isset($user)) {
            $view = view('homePage');
        }
        else {
            abort(403, 'You don\'t have access to this page. Please contact admin');
        }
        return $view;
    }

    
}