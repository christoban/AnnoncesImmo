@extends('layouts.app')

@section('title', 'Messagerie - ImmoNow')

@section('content')

<h3 class="mb-4"><i class="fas fa-envelope me-2 text-primary"></i>Ma Messagerie</h3>

@if($messages->isEmpty())
    <div class="text-center py-5">
        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
        <p class="text-muted">Votre boîte de réception est vide.</p>
    </div>
@else
    <div class="list-group shadow-sm">
        @foreach($messages as $message)
        <a href="{{ route('messages.conversation', $message->sender) }}"
           class="list-group-item list-group-item-action {{ !$message->is_read ? 'fw-bold bg-light' : '' }}">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3"
                         style="width:45px;height:45px;font-size:1.1rem;flex-shrink:0;">
                        {{ strtoupper(substr($message->sender->name, 0, 1)) }}
                    </div>
                    <div>
                        <strong>{{ $message->sender->name }}</strong>
                        @if($message->listing)
                            <span class="badge bg-secondary ms-2">{{ Str::limit($message->listing->title, 25) }}</span>
                        @endif
                        <br>
                        <span class="text-muted small">{{ Str::limit($message->body, 60) }}</span>
                    </div>
                </div>
                <small class="text-muted">{{ $message->created_at->diffForHumans() }}</small>
            </div>
        </a>
        @endforeach
    </div>
    <div class="mt-4">{{ $messages->links() }}</div>
@endif

@endsection
