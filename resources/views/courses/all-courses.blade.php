@extends('layouts.master')
@section('title')
    @lang('translation.Courses')
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            @lang('translation.Courses')
        @endslot
        @slot('title')
            @lang('translation.allCourses')
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title">@lang('translation.Courses')</h4>
                            @role('admin')
                                <p class="card-title-desc">@lang('translation.allCoursesAdmin')</p>
                            @endrole
                        </div>
                        <div class="col-md-6 d-flex justify-content-end align-items-center">
                            <!-- Button aligned to the top right -->
                            {{-- <a href="{{route('courses.add')}}" class="btn btn-outline-primary waves-effect waves-light">@lang('translation.addCourses')</a> --}}
                        </div>
                    </div>
                    <br>

                    <div class="table-responsive">
                        <table class="table table-editable table-nowrap align-middle table-edits">
                            <thead>
                                <tr>
                                    <th>@lang('translation.id')</th>
                                    <th>@lang('translation.name')</th>
                                    <th>@lang('translation.image')</th>
                                    <th>@lang('translation.price')</th>
                                    <th>@lang('translation.teacherCommistion')</th>
                                    <th>@lang('translation.category')</th>
                                    <th>@lang('translation.level')</th>
                                    <th>@lang('translation.specificTo')</th>
                                    <th>@lang('translation.status')</th>
                                    <th>@lang('translation.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($courses as $course)
                                    <tr data-id="1">
                                        <td data-field="@lang('translation.id')" style="width: 80px">{{ $course->id }}</td>
                                        <td data-field="@lang('translation.name')">
                                            <a class="btn-outline-secondary" href="{{ route('courses.info', $course->id) }}"
                                                title="Edit">
                                                {{ $course->translate(config('app.locale'))->name }}
                                            </a>
                                        </td>
                                        <td data-field="@lang('translation.image')"><img src="{{ $course->getCoverPhotoPath() }}"
                                                alt="" height="22"></td>
                                        <td data-field="@lang('translation.price')">{{ $course->price }}</td>
                                        <td data-field="@lang('translation.teacherCommistion')">{{ $course->teacher_commision . '%' }}</td>
                                        <td data-field="@lang('translation.category')">
                                            {{ $course->category->translate(config('app.locale'))->name }}</td>
                                        <td data-field="@lang('translation.level')">
                                            {{ $course->level->translate(config('app.locale')) }}</td>
                                        <td data-field="@lang('translation.specificTo')">
                                            @if ($course->is_specific)
                                                {{ $course->translate(config('app.locale'))->specific_to }}
                                            @else
                                                @lang('translation.notSpecific')
                                            @endif
                                        </td>
                                        <td data-field="@lang('translation.status')">
                                            @if ($course->status->name == 'active')
                                                <span
                                                    class="badge bg-success-subtle text-success">{{ $course->status->translate(config('app.locale')) }}</span>
                                            @elseif ($course->status->name == 'pending')
                                                <span
                                                    class="badge bg-warning-subtle text-warning">{{ $course->status->translate(config('app.locale')) }}</span>
                                            @else
                                                <span
                                                    class="badge bg-danger-subtle text-danger">{{ $course->status->translate(config('app.locale')) }}</span>
                                            @endif
                                        </td>
                                        <td style="width: 100px">
                                            @role('teacher')
                                                <a class="btn btn-outline-secondary btn-sm edit"
                                                    href="{{ route('courses.edit', $course->id) }}" title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                @elserole('admin')
                                                <a class="btn btn-outline-secondary btn-sm edit" title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <a class="btn btn-outline-secondary btn-sm edit" title="Edit">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            @endrole
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <div class="d-flex justify-content-center">
                            {{ $courses->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection
