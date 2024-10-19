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
            <td style="width: 100px">
                @if ($package->start_date > today())
                    <a class="btn btn-outline-secondary btn-sm" title="@lang('translation.edit')"
                        href="{{ route('packages.edit', $package->id) }}">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                @endif

            </td>
        </tr>
    @endforeach
@endif
