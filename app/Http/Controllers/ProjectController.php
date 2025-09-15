<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\Board;

class ProjectController extends Controller
{
    /**
     * Dashboard utama (redirect sesuai role user)
     */
    public function index()
    {
        $role = auth()->user()->role;

        switch ($role) {
            case 'admin':
                $projects = Project::with('boards', 'members.user')->get();
                return view('admin.dashboard', compact('projects'));

            case 'team_lead':
                return $this->teamLeadDashboard();

            case 'developer':
                return $this->developerDashboard();

            case 'designer':
                return $this->designerDashboard();

            default:
                abort(403, 'Role tidak dikenal');
        }
    }

    /**
     * ================= ADMIN =================
     */

    // Form buat proyek
    public function create()
    {
        if (auth()->user()->role !== 'admin') abort(403);
        return view('admin.projects.create');
    }

    // Simpan proyek baru
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $request->validate([
            'project_name' => 'required|string|max:255',
            'description'  => 'nullable|string',
            'deadline'     => 'nullable|date',
        ]);

        // Simpan proyek
        $project = Project::create([
            'project_name' => $request->project_name,
            'description'  => $request->description,
            'created_by'   => auth()->id(),
            'deadline'     => $request->deadline
        ]);

        // Masukkan Admin ke project_members
        ProjectMember::create([
            'project_id' => $project->project_id,
            'user_id'    => auth()->id(),
            'role'       => 'admin',
        ]);

        // Buat boards default
        $boards = ['To Do', 'In Progress', 'Review', 'Done'];
        foreach ($boards as $i => $board) {
            Board::create([
                'project_id' => $project->project_id,
                'board_name' => $board,
                'position'   => $i + 1
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Project berhasil dibuat!');
    }

    // Detail proyek (Admin)
    public function show(Project $project)
    {
        $project = Project::with(['boards.cards', 'members.user'])
                    ->findOrFail($project->project_id);

        if (auth()->user()->role !== 'admin') abort(403);

        return view('admin.projects.show', compact('project'));
    }

    /**
     * ================= TEAM LEAD =================
     */

    // Dashboard Team Lead
    public function teamLeadDashboard()
    {
        $userId = auth()->id();

        $projects = Project::whereHas('members', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })->with('boards', 'members.user')->get();

        return view('teamlead.dashboard', compact('projects'));
    }

    // Detail proyek Team Lead
    public function teamLeadShow(Project $project)
    {
        $userId = auth()->id();

        $project = Project::where('project_id', $project->project_id)
            ->whereHas('members', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->with('boards.cards', 'members.user')
            ->firstOrFail();

        return view('teamlead.projects.show', compact('project'));
    }

    /**
     * ================= DEVELOPER =================
     */
   public function developerDashboard()
{
    $userId = auth()->id();

    // ambil semua cards yang di-assign ke developer ini
    $cards = \App\Models\Card::whereHas('assignments', function($q) use ($userId) {
        $q->where('user_id', $userId);
    })
    ->with(['board.project']) // supaya bisa tahu project dan board
    ->get();

    return view('developer.dashboard', compact('cards'));
}


    /**
     * ================= DESIGNER =================
     */
   public function designerDashboard()
{
    $userId = auth()->id();

    // ambil semua cards yang di-assign ke designer ini
    $cards = \App\Models\Card::whereHas('assignments', function($q) use ($userId) {
        $q->where('user_id', $userId);
    })
    ->with(['board.project'])
    ->get();

    return view('designer.dashboard', compact('cards'));
}

}
