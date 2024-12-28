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
                @can('show-teacher')
                    <a class="btn-outline-secondary" href="{{ route('users.teacher.show', $teacher->id) }}"
                        class="text-body">{{ $teacher->name }}</a>
                @else
                    <a class="btn-outline-secondary" href="javascript:void(0)" class="text-body">{{ $teacher->name }}</a>
                @endcan

            </td>
            <td>{{ $teacher->teacher_courses_count }}</td>
            <td>{{ $teacher->email }}</td>
            <td>
                @can('block-unblock-teacher')
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item">
                            <a class="px-2 btn btn-outline-danger btn-sm" title="@lang('translation.block')" data-bs-toggle="modal"
                                data-bs-target="#blockModal" data-bs-teacherid="{{ $teacher->id }}"
                                data-bs-teachername="{{ $teacher->name }}">
                                <i class="fas fa-lock"></i>
                            </a>
                        </li>
                    </ul>
                @endcan
            </td>
        </tr>
    @endforeach
@endif
