<!-- resources/views/components/auth-validation-errors.blade.php -->
@if (session('error'))
    <div class="mb-4">
        <div class="font-medium text-red-600">{{ session('error') }}</div>
    </div>
@elseif ($errors->any())
    <div {{ $attributes->merge(['class' => 'mb-4']) }}>
        <div class="font-medium text-red-600">Whoops! Something went wrong.</div>

        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
