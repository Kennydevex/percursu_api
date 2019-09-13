<?php

namespace App\Http\Controllers\Percursu;

use Charge;
use ChargeCollection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Percursu\ChargeRequest;


class ChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $charges = Charge::all();
        return new ChargeCollection($charges);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChargeRequest $request)
    {
        $charge = Charge::create(
            [
                'name' => $request->name,
                'description' => $request->description,
            ]
        );

        return response()->json(
            ['msg' => 'Registo efetuado com secesso'],
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Percursu\Charge  $charge
     * @return \Illuminate\Http\Response
     */
    public function show(Charge $charge)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Percursu\Charge  $charge
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Charge $charge)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Percursu\Charge $charge
     * @return \Illuminate\Http\Response
     */
    public function destroy(Charge $charge)
    {
        //
    }
}
