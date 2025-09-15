<?php

namespace App\Http\Controllers;

use App\Models\ProjectMember;
use App\Models\User;
use App\Models\CardAssignment;
use Illuminate\Http\Request;

class ProjectMemberController extends Controller
{
    /**
     * Tambahkan anggota baru ke proyek
     */
    public function addMember(Request $request, $projectId)
    {
        // Cari user berdasarkan username
        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return back()->with('error', '❌ User tidak ditemukan!');
        }

        // Cek apakah user masih aktif di project lain
        $existingMember = ProjectMember::where('user_id', $user->user_id)->first();

        if ($existingMember) {
            // Cek apakah masih ada tugas aktif (assigned/in_progress)
            $activeTasks = CardAssignment::where('user_id', $user->user_id)
                            ->whereIn('assignment_status', ['assigned', 'in_progress'])
                            ->exists();

            if ($activeTasks) {
                return back()->with('error', '⚠️ User masih punya tugas aktif di proyek lain!');
            }

            // Kalau tidak ada tugas aktif, maka user bisa dihapus dari project lama (opsional)
            // ProjectMember::where('id', $existingMember->id)->delete();
        }

        // Tambahkan user ke project baru
        ProjectMember::create([
            'project_id' => $projectId,
            'user_id'    => $user->user_id,
            'role'       => 'member',
            'joined_at'  => now(),
        ]);

        return back()->with('success', '✅ Anggota berhasil ditambahkan ke proyek baru!');
    }
}
