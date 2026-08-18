<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\Poll;
use App\Models\PollVote;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class NoticePollController extends Controller
{
    public function index()
    {
        $notices = Notice::orderBy('published_at', 'desc')->take(10)->get();
        $polls = Poll::with(['options.votes', 'votes'])->orderBy('created_at', 'desc')->get();

        return view('resident.notices_polls.index', compact('notices', 'polls'));
    }

    public function vote(Request $request, Poll $poll)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'poll_option_id' => 'required|exists:poll_options,id',
        ]);

        // Check if user already voted in this poll
        $alreadyVoted = PollVote::where('poll_id', $poll->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyVoted) {
            return back()->with('error', 'You have already voted in this community poll.');
        }

        $vote = PollVote::create([
            'poll_id' => $poll->id,
            'poll_option_id' => $validated['poll_option_id'],
            'user_id' => $user->id,
        ]);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'CAST_POLL_VOTE',
            'module' => 'Resident - Polls',
            'record_id' => $vote->id,
            'new_values' => json_encode($vote->toArray()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Your vote has been cast successfully!');
    }
}
