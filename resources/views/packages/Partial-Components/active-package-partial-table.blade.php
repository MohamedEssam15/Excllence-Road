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
                @can('show-package')
                    <a class="btn-outline-secondary" href="{{ route('packages.info', $package->id) }}"
                        title="@lang('translation.show')">
                        {{ $package->translate(config('app.locale'))->name }}
                    </a>
                @else
                    <a class="btn-outline-secondary" href="javascript:void(0)" title="@lang('translation.show')">
                        {{ $package->translate(config('app.locale'))->name }}
                    </a>
                @endcan

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
            <td style="width: 100px">
                @can('edit-package')
                    <a class="btn btn-outline-secondary btn-sm" title="@lang('translation.edit')"
                        href="{{ route('packages.edit', $package->id) }}">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                @endcan
                @can('discount')
                    @if (is_null($package->discount))
                        <a class="btn btn-outline-success btn-sm" title="@lang('translation.addDiscount')" data-bs-toggle="modal"
                            data-bs-target="#addDiscountModal" data-bs-packageid="{{ $package->id }}"
                            data-bs-packagename="{{ $package->translate(config('app.locale'))->name ?? $package->name }}">
                            <i class="fas fa-percentage"></i>
                        </a>
                    @else
                        <a class="btn btn-outline-danger btn-sm" title="@lang('translation.removeDiscount')" data-bs-toggle="modal"
                            data-bs-target="#removeDiscountModal" data-bs-packageid="{{ $package->id }}"
                            data-bs-packagename="{{ $package->translate(config('app.locale'))->name ?? $package->name }}">
                            <i class="fas fa-percentage"></i>
                        </a>
                    @endif
                @endcan
                @if (!$package->haveOrders)
                    @can('delete-package')
                    <a class="btn btn-outline-danger btn-sm" title="@lang('translation.delete')" data-bs-toggle="modal"
                            data-bs-target="#deletePackageModal" data-bs-packageid="{{ $package->id }}"
                            data-bs-packagename="{{ $package->translate(config('app.locale'))->name ?? $package->name }}">
                            <i class="fas fa-trash"></i>
                    </a>
                    @endcan
                @endif
            </td>
        </tr>
    @endforeach
@endif
