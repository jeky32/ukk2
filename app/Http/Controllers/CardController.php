<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Board;
use App\Models\CardAssignment;
use App\Models\User;
use App\Models\TimeLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CardController extends Controller
{
    /**
     * Daftar cards dalam board
     */
    public function index(Board $board)
    {
        if (Auth::user()->role !== 'team_lead') {
            abort(403, 'Hanya Team Lead yang bisa melihat daftar card');
        }

        $cards = Card::with('assignments.user')
            ->where('board_id', $board->board_id)
            ->get();

        return view('teamlead.cards.index', compact('cards', 'board'));
    }

    /**
     * Form tambah card
     */
    public function create(Board $board)
    {
        if (Auth::user()->role !== 'team_lead') abort(403);

        return view('teamlead.cards.create', compact('board'));
    }

    /**
     * Simpan card baru
     */
    public function store(Request $request, Board $board)
    {
        if (Auth::user()->role !== 'team_lead') abort(403);

        $request->validate([
            'card_title'      => 'required|string|max:255',
            'description'     => 'nullable|string',
            'priority'        => 'required|in:low,medium,high',
            'estimated_hours' => 'nullable|numeric|min:0',
            'due_date'        => 'nullable|date|after_or_equal:today',
            'usernames'       => 'required|string'
        ]);

        $lastPosition = Card::where('board_id', $board->board_id)->max('position');
        $position = $lastPosition ? $lastPosition + 1 : 1;

        $card = Card::create([
            'board_id'        => $board->board_id,
            'card_title'      => $request->card_title,
            'description'     => $request->description,
            'priority'        => $request->priority,
            'estimated_hours' => $request->estimated_hours,
            'due_date'        => $request->due_date,
            'status'          => 'todo',
            'position'        => $position,
            'created_by'      => Auth::id(),
        ]);

        // Multiple assignment
        $usernames = array_map('trim', explode(',', $request->usernames));
        foreach ($usernames as $username) {
            $user = User::where('username', $username)
                ->whereIn('role', ['developer', 'designer'])
                ->first();

            if ($user) {
                CardAssignment::create([
                    'card_id'          => $card->card_id,
                    'user_id'          => $user->user_id,
                    'assignment_status'=> 'assigned',
                    'assigned_at'      => now()
                ]);
            }
        }

        return redirect()->route('teamlead.cards.index', $board)
            ->with('success', '✅ Card berhasil dibuat & ditugaskan!');
    }

    /**
     * Edit card
     */
    public function edit(Board $board, Card $card)
    {
        if (Auth::user()->role !== 'team_lead') abort(403);

        $card->load('assignments.user');
        return view('teamlead.cards.edit', compact('board', 'card'));
    }

    /**
     * Update card
     */
    public function update(Request $request, Board $board, Card $card)
    {
        if (Auth::user()->role !== 'team_lead') abort(403);

        $request->validate([
            'card_title'      => 'required|string|max:255',
            'description'     => 'nullable|string',
            'priority'        => 'required|in:low,medium,high',
            'estimated_hours' => 'nullable|numeric|min:0',
            'due_date'        => 'nullable|date|after_or_equal:today',
            'usernames'       => 'required|string'
        ]);

        $card->update([
            'card_title'      => $request->card_title,
            'description'     => $request->description,
            'priority'        => $request->priority,
            'estimated_hours' => $request->estimated_hours,
            'due_date'        => $request->due_date,
        ]);

        // hapus assignment lama
        CardAssignment::where('card_id', $card->card_id)->delete();

        // tambah assignment baru
        $usernames = array_map('trim', explode(',', $request->usernames));
        foreach ($usernames as $username) {
            $user = User::where('username', $username)
                ->whereIn('role', ['developer', 'designer'])
                ->first();

            if ($user) {
                CardAssignment::create([
                    'card_id'          => $card->card_id,
                    'user_id'          => $user->user_id,
                    'assignment_status'=> 'assigned',
                    'assigned_at'      => now()
                ]);
            }
        }

        return redirect()->route('teamlead.cards.index', $board)
            ->with('success', '✅ Card berhasil diperbarui!');
    }

    /**
     * Hapus card
     */
    public function destroy(Board $board, Card $card)
    {
        if (Auth::user()->role !== 'team_lead') abort(403);

        $card->delete();
        return redirect()->route('teamlead.cards.index', $board)
            ->with('success', '🗑️ Card berhasil dihapus!');
    }

    
}
