<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MentorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Mentor/DashboardMentor');
    }

    /**
     * Display a listing of the resource.
     */
    public function users()
    {
        return Inertia::render('Mentor/UsersMentor', [
            'users' => User::where('manager_id', auth()->id())->with('accounts')->with('tasks', function($query) {
                $query->where('completed', false);
            })->get()->map->only(['id', 'name', 'email', 'accounts', 'tasks', 'created_at']),
        ]);
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
        //
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
