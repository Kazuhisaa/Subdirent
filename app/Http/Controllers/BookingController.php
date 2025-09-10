<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    //
    
    /**
     * Retrieve booked time slots for a given property and date.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
   public function getByDate(Request $request){
           
     $request->validate([
       'booking_date' => 'required|date',
       'property_id' => 'required|integer|exists:units,id', 
     ]);
   
      $bookSlots = Booking::where([
        'booking_date' => $request->booking_date,
         'property_id' =>$request->property_id
      ])->pluck('booking_time');
     
      return response()->json([
         'property_id' => $request->property_id,
         'booking_date' => $request->booking_date,
         'slot' => $bookSlotes
    ]);
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


    public function testApi(){
      return response()->json(['message'=>'success'],201); 
    }
}
