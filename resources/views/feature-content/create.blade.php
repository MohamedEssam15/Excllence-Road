@extends('layouts.master')
@section('title')
    @lang('translation.addFeatureContent')
@endsection
@section('css')
    <!-- plugin css -->
    <link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            @lang('translation.featureContent')
        @endslot
        @slot('title')
            @lang('translation.addFeatureContent')
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title">@lang('translation.featureContent')</h4>
                            @role('admin')
                                <p class="card-title-desc">@lang('translation.addFeatureContent')</p>
                            @endrole
                        </div>
                        <div class="col-md-6 d-flex justify-content-end align-items-center">
                            <!-- Button aligned to the top right -->
                            {{-- <a href="{{route('courses.add')}}" class="btn btn-outline-primary waves-effect waves-light">@lang('translation.addCourses')</a> --}}
                        </div>
                    </div>
                    <div class="mt-4">
                        <form action="{{ route('featureContent.store') }}" class="outer-repeater" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="formrow-arName-input">@lang('translation.subject')</label>
                                        <input type="text" name="subject" class="form-control" id="formrow-arName-input">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="formrow-contentType-input">@lang('translation.contentType')</label>
                                        <select name="contentType" class="form-select" id="formrow-contentType-input"
                                            required>
                                            <option value="photo">@lang('translation.photo')</option>
                                            <option value="video">@lang('translation.video')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="formFileLg" class="form-label">@lang('translation.file')</label>
                                <input class="form-control form-control-lg" id="formFileLg" name="uploadedFile"
                                    type="file">
                            </div>
                            <hr style="border: 1x solid #000; opacity: 1; margin: 20px 0;">
                            <h6 class="card-title">@lang('translation.assignToExistingContent')</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label" for="contentType">@lang('translation.contentType')</label>
                                        <select name="assignToType" class="form-select" id="contentType">
                                            <option value="0">@lang('translation.notFound')</option>
                                            <option value="package">@lang('translation.package')</option>
                                            <option value="course">@lang('translation.course')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label" for="items-input">@lang('translation.content')</label>
                                        <select name="assignTo" class="form-select" id="items-input" required>
                                            <option value="0">@lang('translation.selectItem')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-3 mt-3">
                                <button type="submit"
                                    class="btn btn-primary waves-effect waves-light w-md">@lang('translation.submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>





            {{-- toastr --}}
            <div id="toastContainer" class="position-fixed top-0 end-0 " style="z-index: 1060;margin-top: 5%;">
                <div id="toastr" class="toast overflow-hidden" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="align-items-center text-white
                            bg-success border-0">
                        <div class="d-flex">
                            <div class="toast-body">
                                @if (session('status'))
                                    {{ session('status') }}.
                                @endif
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- error toastr --}}
            <div id="toastContainerError" class="position-fixed top-0 end-0 " style="z-index: 1060;margin-top: 5%;">
                <div id="toastrError" class="toast overflow-hidden" role="alert" aria-live="assertive" aria-atomic="true">
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
    <script>
        $(document).ready(function() {
            $('#contentType').on('change', function() {
                const selectedValue = $(this).val(); // Get selected value (package or course)
                const $itemsSelectBox = $('#items-input'); // Second select box
                let url = null;
                // Clear previous options
                $itemsSelectBox.html('<option value="0">@lang('translation.selectItem')</option>');
                if (selectedValue == 'course') {
                    url = "/feature-content/get-courses";
                } else if (selectedValue == 'package') {
                    url = "/feature-content/get-packages";
                } else {
                    url = null;
                }
                // Fetch data based on selected value
                if (url != null) {
                    $.ajax({
                        url: `${baseUrl + url}`, // Replace with your endpoint
                        method: 'GET',
                        success: function(data) {
                            // Populate the second select box with fetched data
                            $.each(data.data, function(index, item) {
                                $itemsSelectBox.append(
                                    $('<option>', {
                                        value: item
                                            .id, // Assuming each item has an ID
                                        text: item
                                            .name, // Assuming each item has a name
                                    })
                                );
                            });
                            // Remove the default placeholder option
                            $itemsSelectBox.find('option[value="0"]').remove();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching data:', error);
                        },
                    });
                }

            });
        });
    </script>
    <script>
        const currentLang = document.documentElement.lang || 'ar';

        function fireToastr() {
            const toastContainer = document.getElementById('toastContainer');
            if (currentLang == 'ar') {
                toastContainer.style.marginLeft = '1%';
            } else {
                toastContainer.style.marginRight = '1%';
            }
            const toastLiveExample3 = document.getElementById("toastr");
            var toast = new bootstrap.Toast(toastLiveExample3, {
                delay: 3000
            });
            toast.show();
        }

        function handleErrorResponse(errors) {
            console.log(errors);
            const errorMessage = errors.length > 0 ?
                errors.join('<br>') // Join error messages with a line break for display
                :
                'An unexpected error occurred'; // Fallback message

            fireErrorToastr(errorMessage);
        }

        function fireErrorToastr(message) {
            const toastContainer = document.getElementById('toastContainerError');
            if (currentLang == 'ar') {
                toastContainer.style.marginLeft = '1%';
            } else {
                toastContainer.style.marginRight = '1%';
            }
            const toastLiveExample3 = document.getElementById("toastrError");
            const toastBody = toastLiveExample3.querySelector('.toast-body');
            toastBody.innerHTML = message;
            var toast = new bootstrap.Toast(toastLiveExample3, {
                delay: 10000
            });
            toast.show();
        }
    </script>

    @if ($errors->any())
        <script>
            handleErrorResponse(@json($errors->all()))
        </script>
    @endif

    @if (session('status'))
        <script>
            fireToastr()
        </script>
    @endif
    <!-- Varying Modal Content js -->
    <script src="{{ URL::asset('assets/js/pages/bootstrap-toasts.init.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jquery-repeater/jquery-repeater.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/form-repeater.int.js') }}"></script>
@endsection
