<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div class="form-control w-full">
            <label class="label" for="email">
                <span class="label-text">{{ __('Email') }}</span>
            </label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                class="input input-bordered w-full @error('email') input-error @enderror" required autofocus
                autocomplete="username" />
            @error('email')
                <label class="label">
                    <span class="label-text-alt text-error">{{ $message }}</span>
                </label>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-control w-full mt-4">
            <label class="label" for="password">
                <span class="label-text">{{ __('Password') }}</span>
            </label>
            <input id="password" type="password" name="password"
                class="input input-bordered w-full @error('password') input-error @enderror" required
                autocomplete="new-password" />
            @error('password')
                <label class="label">
                    <span class="label-text-alt text-error">{{ $message }}</span>
                </label>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-control w-full mt-4">
            <label class="label" for="password_confirmation">
                <span class="label-text">{{ __('Confirm Password') }}</span>
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                class="input input-bordered w-full @error('password_confirmation') input-error @enderror" required
                autocomplete="new-password" />
            @error('password_confirmation')
                <label class="label">
                    <span class="label-text-alt text-error">{{ $message }}</span>
                </label>
            @enderror
        </div>

        <div class="flex items-center justify-end mt-6">
            <button type="submit" class="btn btn-primary">
                <x-heroicon-o-key class="w-4 h-4 mr-2" />
                {{ __('Reset Password') }}
            </button>
        </div>
    </form>
</x-guest-layout>
