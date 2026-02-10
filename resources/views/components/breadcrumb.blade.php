@if (isset($breadcrumbs) && count($breadcrumbs) > 0)
    <nav class="mb-4 text-sm text-gray-600">
        <ol class="flex items-center space-x-2">
            @foreach ($breadcrumbs as $breadcrumb)
                <li class="flex items-center">
                    @if (!$loop->last)
                        <a href="{{ $breadcrumb['url'] }}" class="text-blue-600 hover:underline">
                            {{ $breadcrumb['label'] }}
                        </a>
                        <span class="mx-2">/</span>
                    @else
                        <span class="text-gray-500 font-medium">
                            {{ $breadcrumb['label'] }}
                        </span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
