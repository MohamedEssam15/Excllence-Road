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
                <img src="{{ $teacher->getAvatarPath() }}" alt="" class="avatar-xs rounded-circle me-2">
                <a href="{{ route('users.teacher.show', $teacher->id) }}" class="text-body">{{ $teacher->name }}</a>
            </td>
            <td>{{ $teacher->teacher_courses_count }}</td>
            <td>{{ $teacher->email }}</td>
            <td>
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <a class="px-2 text-success" title="@lang('translation.accept')" data-bs-toggle="modal"
                            data-bs-target="#acceptModal" data-bs-teacherid="{{ $teacher->id }}"
                            data-bs-teachername="{{ $teacher->name }}">
                            <i class="far fa-check-circle font-size-18"></i>
                        </a>
                    </li>
                </ul>
            </td>
        </tr>
    @endforeach
@endif
