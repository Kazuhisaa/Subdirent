<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    //
    
   public function getByDate(){

   }

    public function store(Request $request){

        $request->validate([
            'property_id' => 'required|exists:units,id',
            'booking_date' => 'required|date',
            'booking_time' => 'required',
            'full_name' =>'required',
            'email' => 'required',
            'phone_number' =>'required',
            'visit_type' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $exists = Booking::where([
            'property_id' => $request->property_id,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time
        ])->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Time slot already booked!'
            ], 409);
        }

        $booking = Booking::create([
            'property_id' => $request->property_id,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'full_name' => strip_tags($request->full_name),
            'email' => strip_tags($request->email),
            'phone_number' => $request->phone_number,
            'visit_type' => striptags($request->visit_type ?? ''),
            'notes' => strip_tags($request->notes ?? '')
        ]);

        return response()->json([
          'message' => 'booking confirmed',
          'booking' =>  $booking
        ],201);
    }

}
