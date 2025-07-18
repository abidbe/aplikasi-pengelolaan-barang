<x-guest-layout>
    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success mb-4">
            <x-heroicon-o-check-circle class="w-5 h-5" />
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Username -->
        <div class="form-control w-full">
            <label class="label" for="username">
                <span class="label-text">{{ __('Username') }}</span>
            </label>
            <input id="username" type="text" name="username" value="{{ old('username') }}"
                class="input input-bordered w-full @error('username') input-error @enderror" required autofocus
                autocomplete="username" />
            @error('username')
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
                autocomplete="current-password" />
            @error('password')
                <label class="label">
                    <span class="label-text-alt text-error">{{ $message }}</span>
                </label>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="form-control mt-4">
            <label class="label cursor-pointer justify-start">
                <input id="remember_me" type="checkbox" name="remember" class="checkbox checkbox-sm mr-2" />
                <span class="label-text">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <a class="link link-hover text-sm" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <button type="submit" class="btn btn-primary">
                <x-heroicon-o-arrow-right-end-on-rectangle class="w-4 h-4 mr-2" />
                {{ __('Log in') }}
            </button>
        </div>
    </form>
</x-guest-layout>
