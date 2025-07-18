<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success mb-4">
            <x-heroicon-o-check-circle class="w-5 h-5" />
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-control w-full">
            <label class="label" for="email">
                <span class="label-text">{{ __('Email') }}</span>
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                class="input input-bordered w-full @error('email') input-error @enderror" required autofocus />
            @error('email')
                <label class="label">
                    <span class="label-text-alt text-error">{{ $message }}</span>
                </label>
            @enderror
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="link link-hover text-sm" href="{{ route('login') }}">
                <x-heroicon-o-arrow-left class="w-4 h-4 mr-1" />
                {{ __('Back to login') }}
            </a>

            <button type="submit" class="btn btn-primary">
                <x-heroicon-o-paper-airplane class="w-4 h-4 mr-2" />
                {{ __('Email Password Reset Link') }}
            </button>
        </div>
    </form>
</x-guest-layout>
