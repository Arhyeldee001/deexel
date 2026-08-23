<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;

class TeamController extends Controller
{
    public function index()
    {
        $members = TeamMember::where('is_active', true)->orderBy('sort_order')->get();

        return view('frontend.pages.team', [
            'members' => $members,
            'title'   => 'Team',
        ]);
    }
}
