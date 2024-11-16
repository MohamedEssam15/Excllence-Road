@php
    $i = 1;
@endphp
@if (!isset($categories[0]))
    <tr>
        <td colspan="10" class="text-center">@lang('translation.noCategories')</td>
    </tr>
@else
    @foreach ($categories as $category)
        <tr data-id="1">
            <td data-field="#" style="width: 80px">{{ $i++ }}</td>
            <td data-field="@lang('translation.name')">
                <p class="btn-outline-secondary">
                    {{ $category->translate(config('app.locale'))->name ?? $category->name }}
                </p>
            </td>
            <td data-field="@lang('translation.Courses')">
                {{ $category->courses_count }}
            </td>
            <td data-field="@lang('translation.date')" style="width: 30%">
                <p style="direction: ltr;text-align: right">
                    {{ $category->created_at->format('Y-m-d g:i a') }}</p>
            </td>
            <td style="width: 100px">
                <a class="btn btn-outline-secondary btn-sm" title="@lang('translation.edit')" data-bs-toggle="modal"
                    data-bs-target="#modifyCategoryModal" data-bs-categoryid="{{ $category->id }}"
                    data-bs-arname="{{ $category->translate('ar')->name }}"
                    data-bs-enname="{{ $category->translate('en')->name }}"
                    data-bs-categoryname="{{ $category->translate()->name ?? $category->name }}">
                    <i class="fas fa-pencil-alt"></i>
                </a>
            </td>
        </tr>
    @endforeach
@endif
