<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use App\Models\Card;
use App\Models\TimeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubtaskController extends Controller
{
    // Buat subtask baru
    public function store(Request $request, Card $card)
    {
        $request->validate([
            'subtask_title' => 'required|string|max:100',
            'description'   => 'nullable|string',
        ]);

        Subtask::create([
            'card_id' => $card->card_id,
            'subtask_title' => $request->subtask_title,
            'description'   => $request->description,
            'status'        => 'todo',
            'created_at'    => now(), // waktu lokal
        ]);

        return back()->with('success', '✅ Subtask berhasil ditambahkan');
    }

    // Mulai subtask
    public function start(Subtask $subtask)
    {
        $subtask->update(['status' => 'in_progress']);

        TimeLog::create([
            'card_id'   => $subtask->card_id,
            'subtask_id'=> $subtask->subtask_id,
            'user_id'   => Auth::id(),
            'start_time'=> now(),
            'description' => 'Mulai subtask',
        ]);

        return back()->with('success', '🚀 Subtask dimulai!');
    }

    // Selesaikan subtask
    public function complete(Subtask $subtask)
    {
        $log = TimeLog::where('subtask_id', $subtask->subtask_id)
            ->where('user_id', Auth::id())
            ->whereNull('end_time')
            ->latest('start_time')
            ->first();

        if ($log) {
            $log->update([
                'end_time' => now(),
                'duration_minutes' => now()->diffInMinutes($log->start_time),
            ]);
        }

        $subtask->update([
            'status' => 'done',
            'actual_hours' => $log ? round($log->duration_minutes / 60, 2) : null,
        ]);

        // cek apakah semua subtask di card sudah selesai
        $card = $subtask->card;
        $unfinished = $card->subtasks()->where('status','!=','done')->count();

        if ($unfinished == 0) {
            $card->update(['board_id' => 2]); // otomatis pindah ke In Progress
        }

        return back()->with('success', '✅ Subtask selesai!');
    }
}
