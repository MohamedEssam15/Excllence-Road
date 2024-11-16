@extends('layouts.master')
@section('title')
    @lang('translation.editPackages')
@endsection
@section('css')
    <!-- plugin css -->
    <link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            @lang('translation.packages')
        @endslot
        @slot('title')
            @lang('translation.editPackages')
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title">@lang('translation.packages')</h4>
                            @role('admin')
                                <p class="card-title-desc">@lang('translation.editPackages')</p>
                            @endrole
                        </div>
                    </div>
                    <br>
                    <div class="mt-4">
                        <form action="{{ route('packages.update', $package->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="formrow-arName-input">@lang('translation.arName')</label>
                                        <input type="text" name="arName" value="{{ $package->translate('ar')->name }}"
                                            class="form-control" id="formrow-arName-input" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="formrow-enName-input">@lang('translation.enName')</label>
                                        <input type="text" name="enName" value="{{ $package->translate('en')->name }}"
                                            class="form-control" id="formrow-enName-input" required>
                                    </div>
                                </div>
                            </div>
                            <!-- Arabic Description -->
                            <div class="mb-3">
                                <label for="arDescription" class="form-label">@lang('translation.arDescription')</label>
                                <textarea class="form-control" id="arDescription" name="arDescription" rows="3" required>{{ $package->translate('ar')->description }}</textarea>
                            </div>

                            <!-- English Description -->
                            <div class="mb-3">
                                <label for="enDescription" class="form-label">@lang('translation.enDescription')</label>
                                <textarea class="form-control" id="enDescription" name="enDescription" rows="3" required>{{ $package->translate('en')->description }}</textarea>
                            </div>

                            <!-- Price, Start Date, and End Date -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label" for="formrow-price-input">@lang('translation.price')</label>
                                        <input type="text" name="price" value="{{ $package->price }}"
                                            class="form-control" id="formrow-price-input">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label" for="formrow-startDate-input">@lang('translation.startDate')</label>
                                        <div class="input-group" id="datepicker2">
                                            <input type="text" name="startDate" value="{{ $package->start_date }}"
                                                class="form-control" placeholder="yyyy-mm-dd" data-date-format="yyyy-mm-dd"
                                                data-date-container='#datepicker2' data-provide="datepicker"
                                                data-date-autoclose="true">
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label" for="formrow-endDate-input">@lang('translation.endDate')</label>
                                        <div class="input-group" id="datepicker2">
                                            <input type="text" name="endDate" value="{{ $package->end_date }}"
                                                class="form-control" placeholder="yyyy-mm-dd" data-date-format="yyyy-mm-dd"
                                                data-date-container='#datepicker2' data-provide="datepicker"
                                                data-date-autoclose="true">
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Popular Package -->
                            <div class="form-check form-switch form-switch-lg">
                                <input type="checkbox" name="addToPopularPackages" value="1" class="form-check-input"
                                    id="addToPopularPackages" {{ $package->is_popular ? 'checked' : '' }}>
                                <label class="form-check-label" for="addToPopularPackages">@lang('translation.addToPopularPackages')</label>
                            </div>
                            <br>

                            <!-- Package Image -->
                            <div class="mb-3">
                                <label for="formFileLg" class="form-label">@lang('translation.packgeImage')</label>
                                <input class="form-control form-control-lg" id="formFileLg" name="coverPhoto"
                                    type="file">
                                <small>@lang('translation.currentImage'): <img src="{{ $package->getCoverPhotoPath() }}"
                                        alt="Current Image" width="100"></small>
                            </div>

                            <!-- Course Selection -->
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="card-title">@lang('translation.Courses')</h4>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-end">
                                        <a type="button"
                                            class="btn btn-sm btn-success btn-soft-success waves-effect waves-light m-2"
                                            onclick="addCourseSelection()"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>

                                <div class="row" id="course-selection-container">
                                    @foreach ($package->courses as $course)
                                        <div class="row mt-3 p-0">
                                            <div class="col-md-4">
                                                <select class="form-select" name="categories[]">
                                                    <option value="{{ $course->category->id }}">
                                                        {{ $course->category->translate(config('app.locale'))->name }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <select class="form-select" name="courses[]">
                                                    <option value="{{ $course->id }}">{{ $course->translate(config('app.locale'))->name }}</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 d-flex justify-content-end p-0">
                                                <button type="button"
                                                    class="btn btn-sm btn-danger btn-soft-danger waves-effect waves-light m-2"
                                                    onclick="$(this).closest('.row').remove()"><i
                                                        class="fas fa-minus"></i></button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Submit and Reset Buttons -->
                            <div class="d-flex flex-wrap gap-3 mt-3">
                                <button type="submit"
                                    class="btn btn-primary waves-effect waves-light w-md">@lang('translation.submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var categories = null;
        // Function to fetch and populate categories from the backend
        function loadCategories(categories, categorySelect) {
            // Clear the category select box
            categorySelect.empty();
            categorySelect.append($('<option>').text('Select Category').attr('value', ''));
            // Populate the select box with categories from the backend
            categories.forEach(category => {
                categorySelect.append($('<option>').val(category.id).text(category.name));
            });
        }

        function getCategories(callback) {
            $.ajax({
                url: "{{ route('categories.get') }}", // Laravel route to fetch categories
                method: 'GET',
                success: function(response) {
                    callback(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching categories:', error);
                }
            });
        }
        // Function to add a new course selection pair
        function addCourseSelection() {
            const container = $('#course-selection-container');

            // Create a row for category and course select inputs
            const row = $('<div>').addClass('row mt-3 p-0');

            const courseSelect = $('<select>')
                .addClass('form-select col-md-4')
                .attr('name', 'courses[]')
                .attr('required', true);
            // Category select
            const categorySelect = $('<select>')
                .addClass('form-select col-md-4')
                .attr('name', 'categories[]')
                .attr('required', true)
                .change(function() {
                    updateCourses($(this), courseSelect);
                });
            if (categories == null) {
                getCategories(function(returnCategories) {
                    categories = returnCategories;
                    loadCategories(returnCategories, categorySelect);
                });
            } else {
                loadCategories(categories, categorySelect);
            }
            // Populate categories from the backend


            // // Course select (empty initially)
            // const courseSelect = $('<select>')
            //     .addClass('form-select col-md-4')
            //     .attr('name', 'courses[]')
            //     .attr('required', true);

            // Remove button
            const removeBtn = $('<button>')
                .addClass('btn btn-sm btn-danger btn-soft-danger waves-effect waves-light m-2')
                .attr('type', 'button')
                .html('<i class="fas fa-minus"></i>')
                .click(function() {
                    row.remove();
                });

            // Append category select, course select, and remove button to row
            row.append(
                $('<div>').addClass('col-md-4').append(categorySelect),
                $('<div>').addClass('col-md-4').append(courseSelect),
                $('<div>').addClass('col-md-4 d-flex justify-content-end p-0').append(removeBtn)
            );
            // row.append(categorySelect).append(courseSelect).append(removeBtn);

            // Append the row to the container
            container.append(row);
        }
        // Function to update courses based on selected category ID (AJAX Request using jQuery)
        function updateCourses(categorySelect, courseSelect) {
            // const courseSelect = courseSelect; // Get the sibling course select box
            const selectedCategoryId = categorySelect.val();

            if (selectedCategoryId) {
                // Replace ':categoryId' in the URL with the actual category ID value
                const url = "{{ route('courses.getbycategory', ':categoryId') }}".replace(':categoryId',
                    selectedCategoryId);

                // Make AJAX request using jQuery
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        courseSelect.empty(); // Clear the previous courses

                        // Populate the course select with the new options
                        console.log(response);
                        response.forEach(course => {
                            courseSelect.append($('<option>').val(course.id).text(course
                                .name)); // Assuming 'id' and 'name' fields in the course
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching courses:', error);
                    }
                });
            } else {
                // Clear the course select box if no category is selected
                courseSelect.empty();
            }
        }
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
    <script src="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jquery-repeater/jquery-repeater.min.js') }}"></script>
@endsection
