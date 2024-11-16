@extends('layouts.master')
@section('title')
    @lang('translation.showCourse')
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            @lang('translation.Courses')
        @endslot
        @slot('title')
            @lang('translation.showCourse')
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="">{{ $course->translate()->name ?? $course->name }}</h4>
                            <p class="card-title-desc lead">@lang('translation.showCourse')</p>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end align-items-center">
                            <img src="{{ $course->getCoverPhotoPath() }}" alt="Course Image" class="rounded"
                                style="max-width: 20%; height: auto;">
                        </div>
                    </div>
                    <div class="mt-4">
                        <!-- Display package details -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-arName-input">@lang('translation.arName')</label>
                                    <input type="text" name="arName" class="form-control" id="formrow-arName-input"
                                        value="{{ $course->translate('ar')?->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-enName-input">@lang('translation.enName')</label>
                                    <input type="text" name="enName" class="form-control" id="formrow-enName-input"
                                        value="{{ $course->translate('en')?->name }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Arabic and English Description -->
                            <div class=" col-md-6 mb-3">
                                <label for="arDescription" class="form-label">@lang('translation.arDescription')</label>
                                <textarea class="form-control" id="arDescription" name="arDescription" rows="3" readonly>{{ $course->translate('ar')?->description }}</textarea>
                            </div>

                            <div class=" col-md-6 mb-3">
                                <label for="enDescription" class="form-label">@lang('translation.enDescription')</label>
                                <textarea class="form-control" id="enDescription" name="enDescription" rows="3" readonly>{{ $course->translate('en')?->description }}</textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-price-input">@lang('translation.price')</label>
                                    <input type="text" name="price" class="form-control" id="formrow-price-input"
                                        value="{{ $course->price }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-startDate-input">@lang('translation.startDate')</label>
                                    <input type="text" name="startDate" class="form-control" id="formrow-startDate-input"
                                        value="{{ $course->start_date }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-endDate-input">@lang('translation.endDate')</label>
                                    <input type="text" name="endDate" class="form-control" id="formrow-endDate-input"
                                        value="{{ $course->end_date }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label"
                                        for="formrow-teacher_commission-input">@lang('translation.teacherCommistion')</label>
                                    <input type="text" name="endDate" class="form-control"
                                        id="formrow-teacher_commission-input" value="{{ $course->teacher_commision }}"
                                        readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <!-- Popular Package -->
                            <div class="form-check form-switch form-switch-lg me-2 col-md-4 d-flex align-items-center">
                                <input type="checkbox" class="form-check-input" id="addToPopularPackages"
                                    {{ $course->is_popular ? 'checked' : '' }} disabled>
                                <label class="form-check-label ms-2" for="addToPopularPackages">@lang('translation.addedToPopularCourses')</label>
                            </div>

                            <!-- Mobile Only Package -->
                            <div class="form-check form-switch form-switch-lg me-2 col-md-4 d-flex align-items-center">
                                <input type="checkbox" class="form-check-input" id="addToMobileOnlyPackages"
                                    {{ $course->is_mobile_only ? 'checked' : '' }} disabled>
                                <label class="form-check-label ms-2"
                                    for="addToMobileOnlyPackages">@lang('translation.isSpecificForMobile')</label>
                            </div>

                            <!-- Status -->
                            <div class="col-md-3 d-flex align-items-center">
                                <p class="form-label me-3 mb-0 fs-6">@lang('translation.status') :</p>
                                @if ($course->status->name == 'active')
                                    <span
                                        class="badge bg-success-subtle text-success fs-6">{{ $course->status->translate() }}</span>
                                @elseif ($course->status->name == 'cancelled')
                                    <span
                                        class="badge bg-danger-subtle text-danger fs-6">{{ $course->status->translate() }}</span>
                                @else
                                    <span
                                        class="badge bg-warning-subtle text-warning fs-6">{{ $course->status->translate() }}</span>
                                @endif

                            </div>
                        </div>
                        @if (isset($course->units[0]))
                            <div class="card mt-5">
                                <div class="card-body">
                                    <h4 class="card-title">@lang('translation.units')</h4>
                                    <br>
                                    <div class="accordion" id="accordionExample">
                                        @foreach ($course->units as $unit)
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="{{ 'accordion' . $unit->id }}">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#{{ 'collapse' . $unit->id }}"
                                                        aria-expanded="true"
                                                        aria-controls="{{ 'collapse' . $unit->id }}">
                                                        {{ $unit->translate() ?? $unit->name }}
                                                    </button>
                                                </h2>
                                                <div id="{{ 'collapse' . $unit->id }}"
                                                    class="accordion-collapse collapse"
                                                    aria-labelledby="{{ 'accordion' . $unit->id }}"
                                                    data-bs-parent="#{{ 'accordion' . $unit->id }}">
                                                    <div class="card m-2">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label"
                                                                            for="formrow-unitArName-input">@lang('translation.arName')</label>
                                                                        <input type="text" name="arName"
                                                                            class="form-control"
                                                                            id="formrow-unitArName-input"
                                                                            value="{{ $unit->translate('ar') ?? __('translation.notFound') }}"
                                                                            readonly>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="mb-3">
                                                                        <label class="form-label"
                                                                            for="formrow-unitEnName-input">@lang('translation.enName')</label>
                                                                        <input type="text" name="enName"
                                                                            class="form-control"
                                                                            id="formrow-unitEnName-input"
                                                                            value="{{ $unit->translate('en') ?? __('translation.notFound') }}"
                                                                            readonly>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <h4 class="card-title">@lang('translation.lessons') :</h4> <br>
                                                            <div class="accordion accordion-flush"
                                                                id="accordionFlushExample">
                                                                @foreach ($unit->lessons as $lesson)
                                                                    <div class="accordion-item">
                                                                        <h2 class="accordion-header"
                                                                            id="flush-{{ $lesson->id }}">
                                                                            <button class="accordion-button collapsed"
                                                                                type="button" data-bs-toggle="collapse"
                                                                                data-bs-target="#flush-collapse{{ $lesson->id }}"
                                                                                aria-expanded="false"
                                                                                aria-controls="flush-collapse{{ $lesson->id }}">
                                                                                {{ $lesson->translate()->name ?? $lesson->name }}
                                                                            </button>
                                                                        </h2>
                                                                        <div id="flush-collapse{{ $lesson->id }}"
                                                                            class="accordion-collapse collapse"
                                                                            aria-labelledby="flush-{{ $lesson->id }}"
                                                                            data-bs-parent="#accordionFlushExample">
                                                                            <div class="accordion-body text-muted">
                                                                                <div class="card">
                                                                                    <div class="card-body">
                                                                                        <div class="row">
                                                                                            <div class="col-md-3">
                                                                                                <div class="mb-3">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="formrow-lessonArName-input">@lang('translation.arName')</label>
                                                                                                    <input type="text"
                                                                                                        name="arName"
                                                                                                        class="form-control"
                                                                                                        style="direction: ltr"
                                                                                                        id="formrow-lessonArName-input"
                                                                                                        value="{{ $lesson->translate('ar')?->name ?? __('translation.notFound') }}"
                                                                                                        readonly>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-3">
                                                                                                <div class="mb-3">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="formrow-lessonEnName-input">@lang('translation.enName')</label>
                                                                                                    <input type="text"
                                                                                                        name="enName"
                                                                                                        class="form-control"
                                                                                                        id="formrow-lessonEnName-input"
                                                                                                        value="{{ $lesson->translate('en')?->name ?? __('translation.notFound') }}"
                                                                                                        readonly>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-3">
                                                                                                <div class="mb-3">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="formrow-lessonType-input">@lang('translation.lessonType')</label>
                                                                                                    @if ($lesson->type == 'video')
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            name="enName"
                                                                                                            class="form-control"
                                                                                                            style="direction: ltr"
                                                                                                            id="formrow-lessonType-input"
                                                                                                            value="@lang('translation.video')"
                                                                                                            readonly>
                                                                                                    @else
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            name="enName"
                                                                                                            class="form-control"
                                                                                                            style="direction: ltr"
                                                                                                            id="formrow-lessonType-input"
                                                                                                            value="@lang('translation.meeting')"
                                                                                                            readonly>
                                                                                                    @endif





                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-3">
                                                                                                <div class="mb-3">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="formrow-meetingDate-input">@lang('translation.meetingDate')</label>
                                                                                                    <input type="text"
                                                                                                        name="enName"
                                                                                                        class="form-control"
                                                                                                        style="direction: ltr"
                                                                                                        id="formrow-meetingDate-input"
                                                                                                        value="{{ $lesson->meeting_date?->format('Y-m-d g:i A') ?? __('translation.notFound') }}"
                                                                                                        readonly>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <!-- Arabic and English Description -->
                                                                                            <div class=" col-md-6 mb-3">
                                                                                                <label for="arDescription"
                                                                                                    class="form-label">@lang('translation.arDescription')</label>
                                                                                                <textarea class="form-control" id="arDescription" name="arDescription" rows="3" readonly>{{ $lesson->translate('ar')?->description ?? __('translation.notFound') }}</textarea>
                                                                                            </div>

                                                                                            <div class=" col-md-6 mb-3">
                                                                                                <label for="enDescription"
                                                                                                    class="form-label">@lang('translation.enDescription')</label>
                                                                                                <textarea class="form-control" id="enDescription" name="enDescription" rows="3" readonly>{{ $lesson->translate('en')?->description ?? __('translation.notFound') }}</textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                        @if ($lesson->type == 'video')
                                                                                            <!-- 21:9 aspect ratio -->
                                                                                            <div class="ratio ratio-21x9">
                                                                                                <video
                                                                                                    src="{{ $lesson->getVideoLink() }}"
                                                                                                    title="{{ $lesson->translate()->name ?? $lesson->name }}"
                                                                                                    controls
                                                                                                    controlsList="nodownload"
                                                                                                    allowfullscreen
                                                                                                    oncontextmenu="return false;"></video>
                                                                                            </div>
                                                                                        @else
                                                                                            <div
                                                                                                class="col-md-3 d-flex align-items-center">
                                                                                                <p
                                                                                                    class="form-label me-3 mb-0 fs-5">
                                                                                                    @lang('translation.meetingLink') :</p>
                                                                                                <p class="mb-0 fs-5"><a
                                                                                                        class="link-opacity-75-hover"
                                                                                                        href="{{ $lesson->video_link }}">{{ $lesson->video_link }}</a>
                                                                                                </p>
                                                                                            </div>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div><!-- end card-body -->
                                                    </div><!-- end card -->
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div><!-- end card-body -->
                            </div><!-- end card -->
                        @endif


                        @if ($course->course_trailer != null)
                            <div class="col-lg-auto mt-5">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">@lang('translation.trailer')</h4>
                                        <!-- 21:9 aspect ratio -->
                                        <div class="ratio ratio-21x9">
                                            <video src="{{ $course->getCourseTrailerPath() }}"
                                                title="{{ $course->translate()->name ?? $course->name }}" controls
                                                controlsList="nodownload" allowfullscreen
                                                oncontextmenu="return false;"></video>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (isset($course->reviews[0]))
                            <div class="col-lg-auto mt-5">
                                <div class="mt-4">
                                    <h5 class="font-size-16 mb-3">@lang('translation.reviews') : </h5>
                                    <div class="text-muted mb-3">
                                        <span class="badge bg-success font-size-14 me-1"><i class="mdi mdi-star"></i>
                                            {{ $course->average_rating }}</span> {{ $course->reviews_count }}
                                        @lang('translation.reviewsCount')
                                    </div>
                                    @foreach ($course->reviews as $review)
                                        <div class="border p-4 rounded">
                                            <div class=" pb-3">
                                                <p class="float-sm-end text-muted font-size-13 " style="direction: ltr">
                                                    {{ $review->created_at->format('Y-m-d g:i A') }}</p>
                                                <div class="badge bg-success mb-2"><i class="mdi mdi-star"></i>
                                                    {{ $review->rating }}</div>
                                                <p class="text-muted mb-4">{{ $review->comment }}</p>
                                                <div class="d-flex align-items-start">
                                                    <div class="flex-grow-1">
                                                        <h5 class="font-size-15 mb-0">{{ $review->student->name }}</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            {{-- toastr --}}
            <div id="acceptToastContainer" class="position-fixed top-0 end-0 " style="z-index: 1060;margin-top: 5%;">
                <div id="acceptToastr" class="toast overflow-hidden" role="alert" aria-live="assertive"
                    aria-atomic="true">
                    <div class="align-items-center text-white
                    bg-success border-0">
                        <div class="d-flex">
                            <div class="toast-body">
                                @lang('translation.courseAccepted').
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- error toastr --}}
            <div id="acceptToastContainerError" class="position-fixed top-0 end-0 "
                style="z-index: 1060;margin-top: 5%;">
                <div id="acceptToastrError" class="toast overflow-hidden" role="alert" aria-live="assertive"
                    aria-atomic="true">
                    <div class="align-items-center text-white
                    bg-danger border-0">
                        <div class="d-flex">
                            <div class="toast-body" id="errorToastrBody">
                                @lang('translation.courseAccepted').
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection
@section('script')
@endsection
