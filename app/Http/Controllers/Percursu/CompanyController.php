<?php

namespace App\Http\Controllers\Percursu;

use App\Http\Controllers\Controller;
use Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::all();
        return response()->Json(['Empresas' => $companies], 200);
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $company = Company::create([
            'name' => $request->name,
            'slogan' => $request->slogan,
            'presentation' => $request->presentation,
            'logo' => $request->logo,
            'cover' => $request->cover,
            'start' => $request->start,
            'status' => $request->status,
            'category_id' => $request->category,
            // 'user_id' => auth->user(),
        ]);
        return response()->Json(['msg' => "Operação efetuada com sucesso"], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Percursu\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

  
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Percursu\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Percursu\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        //
    }
}
