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
            <td data-field="@lang('translation.price')">{{ $course->price }}</td>
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

                @if ($course->start_date > \Carbon\Carbon::today())
                    <a class="btn btn-outline-info btn-sm" title="@lang('translation.returnBack')" data-bs-toggle="modal"
                        data-bs-target="#backToPendingModal" data-bs-courseid="{{ $course->id }}"
                        data-bs-coursename="{{ $course->translate(config('app.locale'))->name ?? $course->name }}">
                        <i class="fas fa-undo"></i>
                    </a>
                @endif
            </td>
        </tr>
    @endforeach
@endif
