@php
    $i = 1;
@endphp
@if (!isset($courses[0]))
    <tr>
        <td colspan="10" class="text-center">@lang('response.noCourses')</td>
    </tr>
@else
    @foreach ($courses as $course)
        <tr data-id="1">
            <td data-field="#" style="width: 80px">{{ $i++ }}</td>
            <td data-field="@lang('translation.name')">
                <a class="btn-outline-secondary" href="{{ route('courses.info', $course->id) }}" title="@lang('translation.show')">
                    {{ $course->translate(config('app.locale'))->name ?? $course->name }}
                </a>
            </td>
            <td data-field="@lang('translation.image')">
                <img src="{{ $course->getCoverPhotoPath() }}" alt="" height="22">
            </td>
            <td data-field="@lang('translation.teacherName')">{{ $course->teacher->name }}</td>
            <td data-field="@lang('translation.price')">{{ $course->price . ' ' . __('translation.currency') }}</td>
            <td data-field="@lang('translation.discount')">{{ $course->discount ?? '-' }}</td>
            <td data-field="@lang('translation.discountType')">
                @if ($course->discount_type == 'percentage')
                    @lang('translation.percentage')
                @elseif ($course->discount_type == 'fixed')
                    @lang('translation.fixedPrice')
                @else
                    -
                @endif
            </td>
            <td data-field="@lang('translation.newPrice')">
                {{ $course->new_price ? $course->new_price . ' ' . __('translation.currency') : '-' }}</td>
            <td data-field="@lang('translation.teacherCommistion')">{{ $course->teacher_commision . '%' }}</td>
            <td data-field="@lang('translation.category')">
                {{ $course->category->translate(config('app.locale'))->name }}
            </td>
            <td data-field="@lang('translation.level')">
                {{ $course->level->translate(config('app.locale')) }}
            </td>
            <td data-field="@lang('translation.specificTo')">
                @if ($course->is_specific)
                    {{ $course->translate(config('app.locale'))->specific_to ?? $course->specific_to }}
                @else
                    @lang('translation.notSpecific')
                @endif
            </td>
            <td data-field="@lang('translation.status')">
                @if ($course->status->name == 'active')
                    <span
                        class="badge bg-success-subtle text-success">{{ $course->status->translate(config('app.locale')) }}</span>
                @elseif ($course->status->name == 'pending')
                    <span
                        class="badge bg-warning-subtle text-warning">{{ $course->status->translate(config('app.locale')) }}</span>
                @else
                    <span
                        class="badge bg-danger-subtle text-danger">{{ $course->status->translate(config('app.locale')) }}</span>
                @endif
            </td>
            <td style="width: 100px">
                <a class="btn btn-outline-secondary btn-sm" title="@lang('translation.edit')" data-bs-toggle="modal"
                    data-bs-target="#modifyModal"
                    data-bs-courseid="{{ $course->id }}"data-bs-teachercommision="{{ $course->teacher_commision }}"
                    data-bs-ispopular="{{ $course->is_populer }}" data-bs-ismobileonly="{{ $course->is_mobile_only }}"
                    data-bs-coursename="{{ $course->translate(config('app.locale'))->name ?? $course->name }}">
                    <i class="fas fa-pencil-alt"></i>
                </a>
                @if (is_null($course->discount))
                    <a class="btn btn-outline-success btn-sm" title="@lang('translation.addDiscount')" data-bs-toggle="modal"
                        data-bs-target="#addDiscountModal" data-bs-courseid="{{ $course->id }}"
                        data-bs-coursename="{{ $course->translate(config('app.locale'))->name ?? $course->name }}">
                        <i class="fas fa-percentage"></i>
                    </a>
                @else
                    <a class="btn btn-outline-danger btn-sm" title="@lang('translation.removeDiscount')" data-bs-toggle="modal"
                        data-bs-target="#removeDiscountModal" data-bs-courseid="{{ $course->id }}"
                        data-bs-coursename="{{ $course->translate(config('app.locale'))->name ?? $course->name }}">
                        <i class="fas fa-percentage"></i>
                    </a>
                @endif

            </td>
        </tr>
    @endforeach
@endif
