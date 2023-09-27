<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publication;
use App\Models\User;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Publication::where('active', true);

        if ($request->has('user_id')) {
            $user_id = $request->input('user_id');
            $query->where('user_id', $user_id);
        }

        $users = User::all();
        $publications = $query->orderBy('created_at', 'desc')->get();

        return view('home', compact('publications', 'users'));
    }
}
