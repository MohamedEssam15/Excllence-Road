@php
    $i = 1;
@endphp
@if (!isset($teachers[0]))
    <tr>
        <td colspan="10" class="text-center">@lang('translation.noUsers')</td>
    </tr>
@else
    @foreach ($teachers as $teacher)
        <tr>
            <th scope="row">
                {{ $i++ }}
            </th>
            <td>
                <img src="{{ $teacher['teacher_avater'] }}" alt="" class="avatar-xs rounded-circle me-2">
                <a href="{{ route('users.teacher.show', $teacher['teacher_id']) }}"
                    class=" btn-outline-secondary">{{ $teacher['name'] }}</a>
            </td>
            <td>{{ $teacher['courses_count'] }}</td>
            <td>{{ $teacher['current_month_revenue'] }} @lang('translation.currency')</td>
            <td>{{ $teacher['total_revenue'] }} @lang('translation.currency')</td>

        </tr>
    @endforeach
@endif
