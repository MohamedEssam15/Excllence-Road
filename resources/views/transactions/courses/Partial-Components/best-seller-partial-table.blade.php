@php
    $i = 1;
@endphp
@if (!isset($courses[0]))
    <tr>
        <td colspan="10" class="text-center">@lang('translation.noCourses')</td>
    </tr>
@else
    @foreach ($courses as $course)
        <tr>
            <th scope="row">
                {{ $i++ }}
            </th>
            <td>
                <a href="{{ route('courses.info', $course->id) }}"
                    class=" btn-outline-secondary">{{ $course->translate()->name ?? $course->name }}</a>
            </td>
            <td>{{ $course->teacher->name }}</td>
            <td>
                <span class="text-body">{{ $course->price }} @lang('translation.currency')</span>
            </td>
            <td>{{ $course->category->translate()->name ?? $course->category->name }}</td>
            <td>
                <span class="text-body">{{ $course->level->translate() ?? $course->level->name }}</span>
            </td>
            <td>
                @if ($course->is_specific)
                    {{ $course->translate()->specific_to ?? $course->specific_to }}
                @else
                    @lang('translation.notSpecific')
                @endif
            </td>
            <td>
                @if ($course->status->name == 'active')
                    <span class="badge bg-success-subtle text-success">{{ $course->status->translate() }}</span>
                @elseif ($course->status->name == 'pending')
                    <span class="badge bg-warning-subtle text-warning">{{ $course->status->translate() }}</span>
                @else
                    <span class="badge bg-danger-subtle text-danger">{{ $course->status->translate() }}</span>
                @endif
            </td>
            <td>
                <span class="text-body">{{ $course->enrollments_count }}</span>
            </td>
        </tr>
    @endforeach
@endif
