<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Http\Resources\APIResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\APIUserResource;
use App\Http\Resources\APIResourceDetail;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\APIPaymentResource;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Traits\ImageUploadingTrait;

class APIController extends Controller
{

    use ImageUploadingTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = Teacher::all();
        return APIResource::collection($data->loadMissing('Category:id,name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = Teacher::create($request->all());

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data->addMediaFromRequest('image')->toMediaCollection('images');
        }


        return new APIResource($data->loadMissing('Category:id,name'));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Teacher::findOrFail($id);

        return new APIResourceDetail($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Teacher::findOrFail($id);

        return new APIResource($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = Teacher::findOrFail($id);
        $data->update($request->all());

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $data->clearMediaCollection('images');
            $data->addMediaFromRequest('image')->toMediaCollection('images');
        }
        if ($data) {
            return new APIResource($data->loadMissing('Category:id,name'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Teacher::findOrFail($id);
        if ($data) {
            $data->delete();
            return new APIResource($data->loadMissing('Category:id,name'));
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user->createToken('User Login')->plainTextToken;
    }

    public function user()
    {
        $user = User::all();
        return APIUserResource::collection($user);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
    }

    public function checkout(Request $request, $id)
    {

        $payment = new PaymentController();
        $channels = $payment->getPayment();

        $item = Teacher::findOrFail($id);
        return new APIPaymentResource([$channels, $item]);
    }
}
