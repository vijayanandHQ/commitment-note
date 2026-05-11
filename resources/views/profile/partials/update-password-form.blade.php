<section>
    <header class="mb-3">
        <h5 class="fw-bold">Update Password</h5>
        <p class="text-muted">
            Use a strong password to keep your account secure.
        </p>
    </header>

    <form method="POST" action="{{ route('profile.password.update') }}">
        @csrf
        @method('PUT')

        {{-- Current Password --}}
        <div class="mb-3">
            <label class="form-label">Current Password</label>
            <input
                type="password"
                name="current_password"
                class="form-control @error('current_password') is-invalid @enderror"
                required
            >
            @error('current_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- New Password --}}
        <div class="mb-3">
            <label class="form-label">New Password</label>
            <input
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                required
            >
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input
                type="password"
                name="password_confirmation"
                class="form-control"
                required
            >
        </div>

        <button type="submit" class="btn btn-primary">
            Update Password
        </button>

        @if(session('success'))
            <span class="ms-3 text-success">{{ session('success') }}</span>
        @endif
    </form>
</section>
