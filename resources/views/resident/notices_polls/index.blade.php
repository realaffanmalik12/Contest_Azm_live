@extends('layouts.resident')

@section('title', 'Notices & Digital Polling')
@section('page-title', 'Society Notices & Digital Polling')

@section('content')
<div class="row g-4">
    <!-- Notice Board -->
    <div class="col-lg-6">
        <div class="glass-panel p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-secondary border-opacity-25">
                <h5 class="font-heading fw-bold mb-0"><i class="bi bi-newspaper me-2" style="color: var(--primary-brand);"></i> Official Notice Board</h5>
                <span class="badge badge-neutral-custom">Society Circulars</span>
            </div>

            @forelse($notices as $notice)
                <div class="p-3 mb-3 rounded-3" style="background: rgba(255, 248, 240, 0.6); border: 1px solid var(--borders-dividers);">
                    <h6 class="fw-bold mb-1" style="color: var(--primary-brand);">{{ $notice->title }}</h6>
                    <small class="text-muted d-block mb-2"><i class="bi bi-clock me-1"></i> {{ \Carbon\Carbon::parse($notice->published_at ?? $notice->created_at)->format('M d, Y') }}</small>
                    <p class="mb-0 text-dark small" style="line-height: 1.6;">{{ $notice->content }}</p>
                </div>
            @empty
                <div class="text-center text-muted py-4">No official notices posted.</div>
            @endforelse
        </div>
    </div>

    <!-- Active Community Polls -->
    <div class="col-lg-6">
        <div class="glass-panel p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom border-secondary border-opacity-25">
                <h5 class="font-heading fw-bold mb-0"><i class="bi bi-ui-checks me-2" style="color: var(--primary-brand);"></i> Digital Community Voting</h5>
                <span class="badge badge-info-custom">Active Polls</span>
            </div>

            @forelse($polls as $poll)
                @php
                    $userVoted = $poll->votes->where('user_id', auth()->id())->first();
                    $totalVotes = $poll->votes->count();
                @endphp
                <div class="p-3 mb-3 rounded-3" style="background: rgba(255, 248, 240, 0.6); border: 1px solid var(--borders-dividers);">
                    <h6 class="fw-bold mb-1" style="color: var(--dark-text);">{{ $poll->title }}</h6>
                    <p class="text-muted small mb-3">{{ $poll->description }}</p>

                    @if($userVoted)
                        <div class="alert glass-panel border-0 py-2 px-3 small mb-3 text-success fw-semibold" style="background: rgba(39, 174, 96, 0.15);">
                            <i class="bi bi-check-circle-fill me-1"></i> You cast your vote in this poll.
                        </div>
                        <label class="form-label small fw-bold text-uppercase" style="color: var(--muted-text);">Live Results (Total: {{ $totalVotes }} votes):</label>
                        @foreach($poll->options as $option)
                            @php
                                $optionVotes = $option->votes->count();
                                $percentage = $totalVotes > 0 ? round(($optionVotes / $totalVotes) * 100) : 0;
                            @endphp
                            <div class="mb-2">
                                <div class="d-flex justify-content-between small fw-semibold">
                                    <span>{{ $option->option_text }}</span>
                                    <span>{{ $percentage }}% ({{ $optionVotes }})</span>
                                </div>
                                <div class="progress mt-1" style="height: 8px; background: rgba(223, 211, 196, 0.5);">
                                    <div class="progress-bar" style="width: {{ $percentage }}%; background: var(--primary-brand);"></div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <form method="POST" action="{{ route('resident.notices-polls.vote', $poll) }}">
                            @csrf
                            @foreach($poll->options as $option)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="poll_option_id" value="{{ $option->id }}" id="opt_{{ $option->id }}" style="accent-color: var(--primary-brand);" required>
                                    <label class="form-check-label small fw-semibold" for="opt_{{ $option->id }}">
                                        {{ $option->option_text }}
                                    </label>
                                </div>
                            @endforeach
                            <button type="submit" class="btn btn-sm btn-primary-custom mt-2"><i class="bi bi-box-arrow-in-down me-1"></i> Submit Vote</button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="text-center text-muted py-4">No active community polls.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
