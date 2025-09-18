<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/booking/book-slot",
     *     summary="Retrieve booked time slots for a given property and date",
     *     tags={"Booking"},
     *     @OA\Parameter(
     *         name="property_id",
     *         in="query",
     *         required=true,
     *         description="ID of the property",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="booking_date",
     *         in="query",
     *         required=true,
     *         description="Booking date in YYYY-MM-DD format",
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of booked time slots",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="property_id", type="integer", example=1),
     *             @OA\Property(property="booking_date", type="string", example="2025-09-12"),
     *             @OA\Property(
     *                 property="slot",
     *                 type="array",
     *                 @OA\Items(type="string", example="10:00")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function getByDate(Request $request)
    {
        $request->validate([
            'booking_date' => 'required|date',
            'property_id' => 'required|integer|exists:units,id',
        ]);

        $bookSlots = Booking::where([
            'booking_date' => $request->booking_date,
            'property_id' => $request->property_id
        ])->pluck('booking_time');

        return response()->json([
            'property_id' => $request->property_id,
            'booking_date' => $request->booking_date,
            'slot' => $bookSlots
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/booking/schedule",
     *     summary="Create a new booking",
     *     tags={"Booking"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="property_id", type="integer", example=1),
     *             @OA\Property(property="booking_date", type="string", format="date", example="2025-09-12"),
     *             @OA\Property(property="booking_time", type="string", example="10:00"),
     *             @OA\Property(property="full_name", type="string", example="Juan Dela Cruz"),
     *             @OA\Property(property="email", type="string", example="juan@example.com"),
     *             @OA\Property(property="phone_number", type="string", example="09123456789"),
     *             @OA\Property(property="visit_type", type="string", example="Inspection"),
     *             @OA\Property(property="notes", type="string", example="Bring ID")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Booking confirmed",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="booking confirmed"),
     *             @OA\Property(property="booking", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Time slot already booked"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:units,id',
            'booking_date' => 'required|date',
            'booking_time' => 'required',
            'full_name' => 'required',
            'email' => 'required',
            'phone_number' => 'required',
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
            'visit_type' => strip_tags($request->visit_type ?? ''),
            'notes' => strip_tags($request->notes ?? '')
        ]);

        return response()->json([
            'message' => 'booking confirmed',
            'booking' =>  $booking
        ], 201);
    }

/**
     * @OA\Get(
     *     path="/api/booking",
     *     summary="Get all bookings",
     *     tags={"Booking"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response with list of bookings"
     *     )
     * )
     */
    public function showAllBooking(){
        $bookings = Booking::all();
        return response()->json($bookings);
    }
  
  }
