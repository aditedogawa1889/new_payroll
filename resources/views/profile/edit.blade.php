<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="admin-card">
            <div class="admin-card-header bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800">{{ __('Update Profile Information') }}</h3>
            </div>
            <div class="admin-card-body max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="admin-card border-t-4 border-adminlte-warning">
            <div class="admin-card-header bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800">{{ __('Update Password') }}</h3>
            </div>
            <div class="admin-card-body max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="admin-card border-t-4 border-adminlte-danger">
            <div class="admin-card-header bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800">{{ __('Delete Account') }}</h3>
            </div>
            <div class="admin-card-body max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-admin-layout>

