@php
    $i = 1;
@endphp
@if (!isset($contacts[0]))
    <tr>
        <td colspan="5" class="text-center">@lang('translation.noContactUsMessages')</td>
    </tr>
@else
    @foreach ($contacts as $contact)
        <tr data-id="1">
            <td data-field="#">{{ $i++ }}</td>
            <td data-field="@lang('translation.name')">
                <p class="btn-outline-secondary">
                    {{ $contact->name }}
                </p>
            </td>
            <td data-field="@lang('translation.email')" class="text-center">
                <p class="btn-outline-secondary">
                    {{ $contact->email }}
                </p>
            </td>

            </td>
            <td data-field="@lang('translation.phone')" class="text-center">
                <p class="btn-outline-secondary">
                    {{ $contact->phone }}
                </p>
            </td>
            <td data-field="@lang('translation.sentAt')">
                <p class="btn-outline-secondary">
                    {{ $contact->created_at->diffForHumans() }}
                </p>
            </td>
            <td data-field="@lang('translation.message')" style="width: 30%">
                <p class="btn-outline-secondary">
                    {{ $contact->message }}
                </p>
            </td>
        </tr>
    @endforeach
@endif
