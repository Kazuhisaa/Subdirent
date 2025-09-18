<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Unit;  
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Predefined booking slots
     */
    private $timeSlots = [
        "09:00 - 10:00 AM",
        "10:00 - 11:00 AM",
        "01:00 - 02:00 PM",
        "02:00 - 03:00 PM",
        "03:00 - 04:00 PM",
    ];

    /**
     * Get available slots for a unit + date
     */
    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'date'    => 'required|date',
            'unit_id' => 'required|integer|exists:units,id',
        ]);

        $booked = Booking::where('unit_id', $request->unit_id)
            ->where('date', $request->date)
            ->pluck('time_slot')
            ->toArray();

        $available = array_values(array_diff($this->timeSlots, $booked));

        return response()->json([
            'unit_id'        => $request->unit_id,
            'date'           => $request->date,
            'available_slots'=> $available,
            'booked_slots'   => $booked
        ]);
    }

    /**
     * Store a booking
     */
        public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'full_name' => 'required|string',
            'email' => 'required|email',
            'contact' => 'required|string',
            'date' => 'required|date',
            'time_slot' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $unit = Unit::findOrFail($request->unit_id);

        $exists = Booking::where([
            'unit_id' => $unit->id,
            'date' => $request->date,
            'time_slot' => $request->time_slot
        ])->exists();

        if ($exists) {
            return response()->json(['message' => 'Time slot already booked!'], 409);
        }

        $booking = Booking::create([
            'unit_id' => $unit->id,
            'title' => $unit->title,
            'price' => $unit->price,
            'full_name' => strip_tags($request->full_name),
            'email' => strip_tags($request->email),
            'contact' => strip_tags($request->contact),
            'date' => $request->date,
            'time_slot' => $request->time_slot,
            'notes' => strip_tags($request->notes ?? ''),
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Booking confirmed',
            'booking' => $booking
        ], 201);
    }


    /**
     * Get all bookings
     */
    public function showAllBooking()
    {
        $bookings = Booking::with('unit')->get();
        return response()->json($bookings);
    }

    public function index()
    {
        $bookings = Booking::with('unit')->latest()->get();

        return view('admin.bookings', compact('bookings'));
    }


    
}
