@extends('layouts.app')

@section('title', 'Conversation avec ' . $user->name)

@section('content')

<div class="row justify-content-center">
<div class="col-lg-8">

    <div class="d-flex align-items-center mb-3">
        <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary btn-sm me-3">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h5 class="mb-0">Conversation avec <strong>{{ $user->name }}</strong></h5>
    </div>

    <!-- Zone des messages -->
    <div class="card shadow-sm mb-3" style="max-height:500px; overflow-y:auto;" id="chatBox">
        <div class="card-body p-3">
            @forelse($messages as $msg)
            <div class="mb-3 {{ $msg->sender_id == Auth::id() ? 'd-flex flex-column align-items-end' : '' }}">
                @if($msg->listing)
                    <small class="text-muted d-block mb-1">
                        <i class="fas fa-home me-1"></i>
                        <a href="{{ route('listings.show', $msg->listing) }}" class="text-decoration-none">
                            {{ Str::limit($msg->listing->title, 40) }}
                        </a>
                    </small>
                @endif
                <div class="p-3 rounded-3 {{ $msg->sender_id == Auth::id() ? 'bg-success text-white' : 'bg-light' }}"
                     style="max-width:75%; display:inline-block;">
                    {{ $msg->body }}
                </div>
                <small class="text-muted d-block mt-1">
                    {{ $msg->sender->name }} · {{ $msg->created_at->format('d/m H:i') }}
                </small>
            </div>
            @empty
                <p class="text-center text-muted py-4">Aucun message. Commencez la conversation !</p>
            @endforelse
        </div>
    </div>

    <!-- Formulaire de réponse -->
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('messages.store') }}">
                @csrf
                <input type="hidden" name="receiver_id" value="{{ $user->id }}">
                <div class="input-group">
                    <textarea name="body" class="form-control" rows="2"
                              placeholder="Écrire un message..." required></textarea>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
</div>

@endsection

@push('scripts')
<script>
    // Scroll automatique vers le bas (dernier message)
    const chatBox = document.getElementById('chatBox');
    chatBox.scrollTop = chatBox.scrollHeight;
</script>
@endpush
