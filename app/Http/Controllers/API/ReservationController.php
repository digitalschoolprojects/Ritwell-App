<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reservations as Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class ReservationController extends Controller
{

    public function index()
    {
        $reservations = Reservation::all();

        return response()->json([
            'reservations' => $reservations
        ], 200);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'trainer_id' => 'required',
            'start_time' => 'required|date_format:Y-m-d H:i:s',
            'end_time' => 'required|date_format:Y-m-d H:i:s|after:start_time',
        ]);

        $trainerId = $validatedData['trainer_id'];
        $startTime = $validatedData['start_time'];
        $endTime = $validatedData['end_time'];

        $existingReservation = Reservation::where(function ($query) use ($startTime, $endTime) {
            $query->whereBetween('start_time', [$startTime, $endTime])
                ->orWhereBetween('end_time', [$startTime, $endTime]);
        })->first();

        if ($existingReservation) {
            return response()->json([
                'message' => 'This time slot is already booked.',
                'existing_reservation' => $existingReservation
            ], 422);
        }

        $reservation = new Reservation();
        $reservation->user_id = Auth::user()->id;
        $reservation->trainer_id = $trainerId;
        $reservation->start_time = $startTime;
        $reservation->end_time = $endTime;
        $reservation->save();

        return response()->json([
            'message' => 'Reservation created successfully',
            'reservation' => $reservation
        ], 201);
    }



    public function update(Request $request, $id)
    {
        $reservation = Reservation::find($id);
        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found.'], 404);
        }

        $validatedData = $request->validate([
            'trainer_id' => 'sometimes|required',
            'start_time' => 'sometimes|required|date_format:Y-m-d H:i:s',
            'end_time' => 'sometimes|required|date_format:Y-m-d H:i:s|after:start_time',
        ]);

        if (array_key_exists('trainer_id', $validatedData)) {
            $reservation->trainer_id = $validatedData['trainer_id'];
        }

        if (array_key_exists('start_time', $validatedData)) {
            $startTime = $validatedData['start_time'];
            $existingReservation = Reservation::where(function ($query) use ($startTime, $reservation) {
                $query->whereBetween('start_time', [$startTime, $reservation->end_time])
                    ->orWhereBetween('end_time', [$startTime, $reservation->end_time]);
            })->where('id', '!=', $id)->first();

            if ($existingReservation) {
                return response()->json([
                    'message' => 'This time slot is already booked.',
                    'existing_reservation' => $existingReservation
                ], 422);
            }

            $reservation->start_time = $validatedData['start_time'];
        }

        if (array_key_exists('end_time', $validatedData)) {
            $endTime = $validatedData['end_time'];
            $existingReservation = Reservation::where(function ($query) use ($endTime, $reservation) {
                $query->whereBetween('start_time', [$reservation->start_time, $endTime])
                    ->orWhereBetween('end_time', [$reservation->start_time, $endTime]);
            })->where('id', '!=', $id)->first();

            if ($existingReservation) {
                return response()->json([
                    'message' => 'This time slot is already booked.',
                    'existing_reservation' => $existingReservation
                ], 422);
            }

            $reservation->end_time = $validatedData['end_time'];
        }

        $reservation->save();

        return response()->json([
            'message' => 'Reservation updated successfully',
            'reservation' => $reservation
        ], 200);
    }




    public function show($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        return response()->json(['reservation' => $reservation], 200);
    }

    public function destroy($id)
    {
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json([
                'message' => 'Reservation not found'
            ], 404);
        }

        $reservation->delete();

        return response()->json([
            'message' => 'Reservation deleted successfully'
        ], 200);
    }
}
