<?php

namespace App\Http\Controllers;

use App\Models\SystemVariable;
use App\Http\Requests\StoreSystemVariableRequest;
use App\Http\Requests\UpdateSystemVariableRequest;

class SystemVariableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreSystemVariableRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSystemVariableRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SystemVariable  $systemVariable
     * @return \Illuminate\Http\Response
     */
    public function show(SystemVariable $systemVariable)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SystemVariable  $systemVariable
     * @return \Illuminate\Http\Response
     */
    public function edit(SystemVariable $systemVariable)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSystemVariableRequest  $request
     * @param  \App\Models\SystemVariable  $systemVariable
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSystemVariableRequest $request, SystemVariable $systemVariable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SystemVariable  $systemVariable
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemVariable $systemVariable)
    {
        //
    }
}
