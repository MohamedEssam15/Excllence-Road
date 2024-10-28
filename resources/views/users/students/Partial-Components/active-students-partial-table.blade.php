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
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <a class="px-2 text-danger" title="@lang('translation.block')" data-bs-toggle="modal"
                            data-bs-target="#blockModal" data-bs-studentid="{{ $student->id }}"
                            data-bs-studentname="{{ $student->name }}">
                            <i class="fas fa-lock"></i>
                        </a>
                    </li>
                </ul>
            </td>
        </tr>
    @endforeach
@endif
