<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamMemberController extends Controller
{
    public function index()
    {
        $members = TeamMember::orderBy('sort_order')->get();

        return view('admin.team.index', ['members' => $members]);
    }

    public function create()
    {
        return view('admin.team.form', ['member' => new TeamMember(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['sort_order'] = (int) TeamMember::max('sort_order') + 1;
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('team', 'public');
        }

        TeamMember::create($data);

        return redirect()->route('admin.team.index')->with('ok', 'Team member added.');
    }

    public function edit(TeamMember $team)
    {
        return view('admin.team.form', ['member' => $team]);
    }

    public function update(Request $request, TeamMember $team)
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('photo')) {
            if ($team->photo) {
                Storage::disk('public')->delete($team->photo);
            }
            $data['photo'] = $request->file('photo')->store('team', 'public');
        } elseif ($request->boolean('remove_photo') && $team->photo) {
            Storage::disk('public')->delete($team->photo);
            $data['photo'] = null;
        }

        $team->update($data);

        return redirect()->route('admin.team.index')->with('ok', 'Team member updated.');
    }

    public function destroy(TeamMember $team)
    {
        if ($team->photo) {
            Storage::disk('public')->delete($team->photo);
        }
        $team->delete();

        return back()->with('ok', 'Team member removed.');
    }

    public function toggle(TeamMember $team)
    {
        $team->update(['is_active' => ! $team->is_active]);

        return response()->json(['is_active' => $team->is_active]);
    }

    public function reorder(Request $request)
    {
        $data = $request->validate(['order' => ['required', 'array'], 'order.*' => ['integer']]);
        foreach ($data['order'] as $position => $id) {
            TeamMember::where('id', $id)->update(['sort_order' => $position]);
        }

        return response()->json(['ok' => true]);
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'name'              => ['required', 'string', 'max:190'],
            'role'              => ['nullable', 'string', 'max:190'],
            'bio'               => ['nullable', 'string', 'max:2000'],
            'socials'           => ['nullable', 'array'],
            'socials.linkedin'  => ['nullable', 'string', 'max:255'],
            'socials.x'         => ['nullable', 'string', 'max:255'],
            'socials.github'    => ['nullable', 'string', 'max:255'],
            'socials.website'   => ['nullable', 'string', 'max:255'],
        ]);

        // Drop empty social links
        if (! empty($data['socials'])) {
            $data['socials'] = array_filter($data['socials'], fn ($v) => trim((string) $v) !== '');
            if (empty($data['socials'])) {
                $data['socials'] = null;
            }
        }

        return $data;
    }
}
