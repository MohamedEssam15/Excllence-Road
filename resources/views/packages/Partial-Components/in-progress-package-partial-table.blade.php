@php
    $i = 1;
@endphp
@if (!isset($packages[0]))
    <tr>
        <td colspan="10" class="text-center">@lang('response.noPackages')</td>
    </tr>
@else
    @foreach ($packages as $package)
        <tr data-id="1">
            <td data-field="#" style="width: 80px">{{ $i++ }}</td>
            <td data-field="@lang('translation.name')">
                <a class="btn-outline-secondary" href="{{ route('packages.info', $package->id) }}"
                    title="@lang('translation.show')">
                    {{ $package->translate(config('app.locale'))->name }}
                </a>
            </td>
            <td data-field="@lang('translation.image')">
                <img src="{{ $package->getCoverPhotoPath() }}" alt="" height="22">
            </td>
            <td data-field="@lang('translation.price')">{{ $package->price }} @lang('translation.currency')</td>
            <td data-field="@lang('translation.discount')">{{ $package->discount ?? '-' }}</td>
            <td data-field="@lang('translation.discountType')">
                @if ($package->discount_type == 'percentage')
                    @lang('translation.percentage')
                @elseif ($package->discount_type == 'fixed')
                    @lang('translation.fixedPrice')
                @else
                    -
                @endif
            </td>
            <td data-field="@lang('translation.newPrice')">
                {{ $package->new_price ? $package->new_price . ' ' . __('translation.currency') : '-' }}</td>
            <td data-field="@lang('translation.startDate')">{{ $package->start_date }}</td>
            <td data-field="@lang('translation.endDate')">
                {{ $package->end_date }}
            </td>
            <td data-field="@lang('translation.isPopular')">
                @if ($package->is_popular)
                    <span class="badge bg-success-subtle text-success">@lang('translation.yes')</span>
                @else
                    <span class="badge bg-danger-subtle text-danger">@lang('translation.no')</span>
                @endif
            </td>
        </tr>
    @endforeach
@endif
