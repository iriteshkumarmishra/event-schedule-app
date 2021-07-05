<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Event;
use App\Schedule;

class EventApiController extends Controller
{
    public function createEvent(Request $request)
    {
        $validatedRequest = $request->validate([
            'name'          =>      'required|string|max:190',
            'description'   =>      'required|string',
            'startTime'     =>      'required|date_format:H:i|before:endTime',
            'endTime'       =>      'required|date_format:H:i|after:startTime',
            'dayOfTheWeek'  =>      'required|array|in:0,1,2,3,4,5,6',
        ]);

        $event = new Event();
        $event->name = $validatedRequest['name'];
        $event->description = $validatedRequest['description'];
        $event->start_time = $validatedRequest['startTime'];
        $event->end_time = $validatedRequest['endTime'];
        $event->day_of_the_week = $validatedRequest['dayOfTheWeek'];
        $event->created_by = $request->user()->id;
        $event->save();
        
        // now create schedules for next 90 days for this event
        $allSchedulesDate = [];
        $now = Carbon::now();
        foreach($validatedRequest['dayOfTheWeek'] as $eachDay) {
            // for today schedule date. example : user is scheduling today so if the meeting start time is not passed then create the event schedule for today as well.
            $time = explode(':', $event->start_time);
            if($now->dayOfWeek == $eachDay && $now <= Carbon::create($now->year, $now->month, $now->day, $time[0], $time[1])) {
                $allSchedulesDate[] = $now;
            }
            $dayCounter = abs(Carbon::create($now->year, $now->month, Carbon::now()->next($eachDay)->day)->day - $now->day);
            while($dayCounter <= 90) {
                $allSchedulesDate[] = Carbon::now()->add($dayCounter, 'days');
                $dayCounter += 7;
            }
        }
        $rows = [];
        foreach($allSchedulesDate as $eachDate) {
            $scheduleObj = new Schedule();
            $scheduleObj->event_id = $event->id;
            $scheduleObj->scheduled_at = $eachDate;

            $rows[] = $scheduleObj;
        }
        $event->schedules()->saveMany($rows);

        return response()->json(['id'=> $event->id, 'message'=> "Event created successfully"]);
    }

    public function getCalenderData(Request $request)
    {        
        $allSchedules = [];
        $events = Event::where('created_by', $request->user()->id)->get();
        foreach($events as $event) {
            $eventData = [
                'id'            =>  $event->id,
                'name'          =>  $event->name,
                'description'   =>  $event->description,
                'startTime'     =>  Carbon::parse($event->start_time)->format('H:i'),
                'endTime'       =>  Carbon::parse($event->end_time)->format('H:i'),
            ];
            $scheduleData = [];
            $schedules = $event->schedules;
            foreach($schedules as $schedule) {
                $scheduleData[] = $schedule->scheduled_at;
            }
            $allSchedules[] = [
                'event'     =>  $eventData,
                'schedules' =>  $scheduleData
            ];
        }

        // now prepare calender data
        $response = [];
        foreach($allSchedules as $eachSchedule) {
            $eventId = $eachSchedule['event']['id'];
            $name = $eachSchedule['event']['name'];
            $things = $eachSchedule['event']['name'] . ' (' . $eachSchedule['event']['startTime'] . ' - ' . $eachSchedule['event']['endTime'] . ')';
            $months = [];
            $days = [];
            foreach($eachSchedule['schedules'] as $scheduleDate) {
                $response[] = [
                    'id'        =>  $eventId . Str::random(7),      // this is for vue-for key for uniqueness
                    'name'      =>  $name,
                    'months'    =>  [$this->toString($scheduleDate->month)],
                    'days'      =>  [$this->toString($scheduleDate->day)],
                    'things'    =>  $things,
                ];
            }
        }

        return response()->json($response);
    }

    private function toString($number)
    {
        $string = (string)$number;
        if($number < 10) {
            $string = '0' . $string;
        }
        return $string;
    }
}