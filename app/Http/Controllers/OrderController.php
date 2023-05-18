<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\APIResourceOrder;
use App\Http\Controllers\Payment\PaymentController;
use App\Models\Schedule;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Order::all();
        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = Teacher::find($request->teacher_id);
        $schedule = Schedule::find($request->schedule_id);
        $user = auth()->user();

        $data = Order::create([
            'user_id' => 1,
            'teacher_id' => $product->id,
            'schedule_id' => $schedule->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'sucessfully created order',
            'data' => $data
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
