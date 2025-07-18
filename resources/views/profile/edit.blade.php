<!-- filepath: c:\laragon\www\aplikasi-pengelolaan-barang\resources\views\profile\edit.blade.php -->
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">
                        <x-heroicon-o-user class="w-6 h-6" />
                        {{ __('Profile Information') }}
                    </h2>
                    <p class="text-sm text-gray-600 mb-4">
                        {{ __("Update your account's profile information and email address.") }}
                    </p>

                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title">
                        <x-heroicon-o-key class="w-6 h-6" />
                        {{ __('Update Password') }}
                    </h2>
                    <p class="text-sm text-gray-600 mb-4">
                        {{ __('Ensure your account is using a long, random password to stay secure.') }}
                    </p>

                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-error">
                        <x-heroicon-o-exclamation-triangle class="w-6 h-6" />
                        {{ __('Delete Account') }}
                    </h2>
                    <p class="text-sm text-gray-600 mb-4">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                    </p>

                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
