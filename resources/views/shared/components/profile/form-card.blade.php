@props(['title', 'cancelRoute', 'submitText' => 'Save Changes'])

<div class="card">
    <div class="card-header bg-primary text-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ $title }}</h5>
            <a href="{{ $cancelRoute }}" class="btn btn-sm btn-light">Back to Profile</a>
        </div>
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        {{ $slot }}
        
        <div class="d-flex justify-content-between mt-4">
            <a href="{{ $cancelRoute }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                {{ $submitText }}
            </button>
        </div>
    </div>
</div> 