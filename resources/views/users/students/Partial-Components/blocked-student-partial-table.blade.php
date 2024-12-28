@php
    $i = 1;
@endphp
@if (!isset($students[0]))
    <tr>
        <td colspan="10" class="text-center">@lang('translation.noUsers')</td>
    </tr>
@else
    @foreach ($students as $student)
        <tr>
            <th scope="row">
                {{ $i++ }}
            </th>
            <td>
                <img src="{{ $student->getAvatarPath() }}" alt="" class="avatar-xs rounded-circle me-2">
                <span class="text-body">{{ $student->name }}</span>
            </td>
            <td>{{ $student->email }}</td>
            <td>
                @can('block-unblock-student')
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item">
                            <a class="btn btn-outline-success btn-sm" title="@lang('translation.accept')" data-bs-toggle="modal"
                                data-bs-target="#acceptModal" data-bs-studentid="{{ $student->id }}"
                                data-bs-studentname="{{ $student->name }}">
                                <i class="fas fa-lock-open"></i>
                            </a>
                        </li>
                    </ul>
                @endcan

            </td>
        </tr>
    @endforeach
@endif
