@php
    $title = $title ?? '';
    $breadcrumbs = $breadcrumbs ?? [];
    $content = $content ?? '';
@endphp

<main class="flex-1 bg-base-200 min-h-screen">
    <!-- Header Component -->
    @include('layouts.header', ['breadcrumbs' => $breadcrumbs])

    <!-- Main Content -->
    <div class="p-4 lg:p-6">
        @if ($title)
            <div class="mb-6">
                <h1 class="text-2xl lg:text-3xl font-bold text-base-content">{{ $title }}</h1>
            </div>
        @endif

        <div class="space-y-6">
            @if ($content)
                @include('partials.' . $content)
            @endif
        </div>
    </div>
</main>
