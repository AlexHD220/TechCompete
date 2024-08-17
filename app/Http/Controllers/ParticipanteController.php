<?php

namespace App\Http\Controllers;

use App\Models\Participante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ParticipanteController extends Controller
{
    public function __construct()
    {
        //$this->middleware('can:only-user')->except('index', 'show');
        $this->middleware('can:only-user');
        
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('participante/proxParticipante');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('participante/proxParticipante');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show(Participante $participante)
    {
        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Participante $participante)
    {
        return redirect('/');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Participante $participante)
    {
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Participante $participante)
    {
        return redirect('/');
    }
}
