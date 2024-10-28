@php
    $i = 1;
@endphp
@if (!isset($admins[0]))
    <tr>
        <td colspan="10" class="text-center">@lang('translation.noUsers')</td>
    </tr>
@else
    @foreach ($admins as $admin)
        <tr>
            <th scope="row">
                {{ $i++ }}
            </th>
            <td>
                <img src="{{ $admin->getAvatarPath() }}" alt="" class="avatar-xs rounded-circle me-2">
                <span class="text-body">{{ $admin->name }}</span>
            </td>
            <td>{{ $admin->email }}</td>
            <td>
                @if (!$admin->is_blocked)
                    <span class="badge bg-success-subtle text-success">@lang('translation.active')</span>
                @else
                    <span class="badge bg-danger-subtle text-danger">@lang('translation.blocked')</span>
                @endif
            </td>
            <td>
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        @if (!$admin->is_blocked)
                            <a class="px-2 text-danger" title="@lang('translation.block')" data-bs-toggle="modal"
                                data-bs-target="#blockModal" data-bs-adminid="{{ $admin->id }}"
                                data-bs-adminname="{{ $admin->name }}">
                                <i class="fas fa-lock"></i>
                            </a>
                        @else
                            <a class="px-2 text-success" title="@lang('translation.unblock')" data-bs-toggle="modal"
                                data-bs-target="#unblockModal" data-bs-adminid="{{ $admin->id }}"
                                data-bs-adminname="{{ $admin->name }}">
                                <i class="fas fa-lock-open"></i>
                            </a>
                        @endif

                    </li>
                </ul>
            </td>
        </tr>
    @endforeach
@endif
