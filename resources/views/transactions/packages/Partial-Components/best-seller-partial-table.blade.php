@php
    $i = 1;
@endphp
@if (!isset($packages[0]))
    <tr>
        <td colspan="10" class="text-center">@lang('translation.noPackages')</td>
    </tr>
@else
    @foreach ($packages as $package)
        <tr>
            <th scope="row">
                {{ $i++ }}
            </th>
            <td>
                <a href="{{ route('packages.info', $package->id) }}"
                    class=" btn-outline-secondary">{{ $package->translate()->name ?? $package->name }}</a>
            </td>
            <td>
                <span class="text-body">{{ $package->price }} @lang('translation.currency')</span>
            </td>

            <td>
                <span class="text-body">{{ $package->user_enrollments_count }}</span>
            </td>
        </tr>
    @endforeach
@endif
