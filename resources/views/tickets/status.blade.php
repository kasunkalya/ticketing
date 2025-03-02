@extends('layouts.guest')

@section('content')
<div class="container">
    <h2 class="mb-4">Check Ticket Status</h2>
    
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('ticket.status.check') }}" class="mb-4">
        @csrf
        <div class="mb-3">
            <label for="reference_no" class="form-label">Enter Reference Number</label>
            <input type="text" name="reference_no" id="reference_no" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Check Status</button>
    </form>

    @if(isset($ticket))
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Ticket Details</h4>
                <p><strong>Reference No:</strong> {{ $ticket->reference_no }}</p>
                <p><strong>Customer Name:</strong> {{ $ticket->customer_name }}</p>
                <p><strong>Email:</strong> {{ $ticket->email }}</p>
                <p><strong>Status:</strong> <span class="badge bg-success">{{ ucfirst($ticket->status) }}</span></p>
                <p><strong>Issue Description:</strong></p>
                <p>{{ $ticket->problem_description }}</p>
                
                <h5>Replies</h5>
                <div class="border p-3" style="max-height: 300px; overflow-y: auto;">
                    @if ($ticket->replies->count() > 0)
                        @foreach ($ticket->replies as $reply)
                            <div class="border p-2 mb-2 rounded">
                                <p><strong>{{ $reply->user->name }}:</strong> {{ $reply->message }}</p>
                                <small class="text-muted">{{ $reply->created_at->format('d M Y, h:i A') }}</small>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No replies yet.</p>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
