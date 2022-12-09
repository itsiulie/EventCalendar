<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $events = array();
        $bookings = Event::all();
        foreach($bookings as $booking){
            $events[] = [
                'id' => $booking->id,
                'title' => $booking->title,
                'start' => $booking->start_date,
                'end' => $booking->end_date,
                'category' => Category::where('id', $booking->category_id)->get('name')->toArray(),
            ];
        }      
        return view('calendar.index', ['events' => $events, 'categories' => $categories]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|min:1',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $input = $request->all();
        Event::create($input);

        return redirect()->back()->with(['success' => 'Event added succesfully']);
    }

    public function update(Request $request, $id)
    {
        $event = Event::find($id);
        if(! $event) {
            return response()->json([
                'error' => 'Unable to locate the event'
            ], 404);
        }
        $event->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
        return response()->json('Event updated');
    }

    public function destroy($id)
    {
        $event = Event::find($id);
        if(! $event) {
            return response()->json([
                'error' => 'Unable to locate the event'
            ], 404);
        }
        $event->delete();
        return $id;
    }
}
