@php
    $i = 1;
@endphp
@if (!isset($contents[0]))
    <tr>
        <td colspan="10" class="text-center">@lang('translation.noFeatureContent')</td>
    </tr>
@else
    @foreach ($contents as $content)
        <tr data-id="1">
            <td data-field="#" style="width: 80px">{{ $i++ }}</td>
            <td data-field="@lang('translation.name')">
                <p class="btn-outline-secondary">
                    {{ $content->subject }}
                </p>
            </td>
            <td data-field="@lang('translation.Courses')" class="text-center" style="width: 40%">
                @if ($content->type == 'photo')
                    <img src="{{ $content->getCoverPath() }}" alt="{{ $content->subject }}"
                        style="width: 50%; height: 100%">
                @else
                    <video src="{{ $content->getCoverPath() }}" alt="{{ $content->subject }}"
                        style="width: 50%; height: 100%" controls controlsList="nodownload" allowfullscreen
                        oncontextmenu="return false;"></video>
                @endif
            </td>
            <td data-field="@lang('translation.date')" class="text-center">
                <p style="direction: ltr;text-align: right">
                    {{ $content->created_at->format('Y-m-d g:i a') }}</p>
            </td>
            <td data-field="@lang('translation.type')" class="text-center">
                <p style="direction: ltr;text-align: right">
                    {{ $content->modelable_type != null ? __('translation.' . $content->modelable_type) : __('translation.notFound') }}
                </p>
            </td>
            <td data-field="@lang('translation.type')" class="text-center">
                <p style="direction: ltr;text-align: right">
                    {{ $content->modelable_type != null ? $content->modelable->translate()?->name ?? $content->modelable->name : __('translation.notFound') }}
                </p>
            </td>
            <td style="width: 100px">

                <a class="btn btn-outline-danger btn-sm" title="@lang('translation.delete')" data-bs-toggle="modal"
                    data-bs-target="#deleteModal" data-bs-contentid="{{ $content->id }}"
                    data-bs-contentname="{{ $content->subject }}">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </td>
        </tr>
    @endforeach
@endif
