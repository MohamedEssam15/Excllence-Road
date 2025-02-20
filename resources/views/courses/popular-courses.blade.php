@extends('layouts.master')
@section('title')
    @lang('translation.ManagePopularCourses')
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            @lang('translation.Courses')
        @endslot
        @slot('title')
            @lang('translation.ManagePopularCourses')
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <h4 class="card-title">@lang('translation.Courses')</h4>
                            <p class="card-title-desc">@lang('translation.popularCourses')</p>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end align-items-center">
                            <!-- Button -->
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6">

                        </div>
                        <div class="col-md-6 d-flex justify-content-end align-items-center">
                            <input type="text" id="search" class="form-control" placeholder="@lang('translation.search')"
                                style="width: 30%;direction: ltr">
                        </div>
                    </div>
                    <div class="table-responsive">


                        <br>
                        <table class="table table-editable table-nowrap align-middle table-edits">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('translation.name')</th>
                                    <th>@lang('translation.image')</th>
                                    <th>@lang('translation.teacherName')</th>
                                    <th>@lang('translation.price')</th>
                                    <th>@lang('translation.discount')</th>
                                    <th>@lang('translation.discountType')</th>
                                    <th>@lang('translation.newPrice')</th>
                                    <th>@lang('translation.teacherCommistion')</th>
                                    <th>@lang('translation.category')</th>
                                    <th>@lang('translation.level')</th>
                                    <th>@lang('translation.specificTo')</th>
                                    <th>@lang('translation.status')</th>
                                    <th>@lang('translation.actions')</th>
                                </tr>
                            </thead>
                            <tbody id="courseTableBody">
                            </tbody>
                        </table>
                        <br>
                        <div class="d-flex justify-content-center" id="paginationLinks">
                            {{-- {{ $courses->links('vendor.pagination.bootstrap-5') }} --}}
                        </div>
                    </div>

                </div>
            </div>
            @can('delete-course')
                {{-- delete course modal --}}
                <div class="modal fade" id="deleteCourseModal" tabindex="-1" aria-labelledby="deleteCourseModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteCourseModalLabel"></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="deleteCourseForm">
                                    @csrf
                                    @method('DELETE')
                                    <div class="mb-3">
                                        <p>
                                            @lang('translation.areYouSureDelete')
                                        </p>
                                    </div>
                                    <input type="hidden" name="courseId" class="form-control" id="delete-course-id">
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">@lang('translation.close')</button>
                                <button type="button" id="deleteCourseButton"
                                    class="btn btn-danger">@lang('translation.accept')</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            @can('edit-course')
                {{-- modify modal --}}
                <div class="modal fade" id="modifyModal" tabindex="-1" aria-labelledby="modifyModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modifyModalLabel"></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="modifyCourseForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="teacherCommistion" class="col-form-label">@lang('translation.courseOrder')</label>
                                        <input type="number" name="popularOrder" class="form-control"
                                            id="teacherPopularOrderInput">
                                    </div>
                                    <input type="hidden" name="courseId" class="form-control" id="course-id">
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">@lang('translation.close')</button>
                                <button type="button" id="modifyCourseButton"
                                    class="btn btn-success">@lang('translation.accept')</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            {{-- toastr --}}
            <div id="toastContainer" class="position-fixed top-0 end-0 " style="z-index: 1060;margin-top: 5%;">
                <div id="toastr" class="toast overflow-hidden" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="align-items-center text-white
                            bg-success border-0">
                        <div class="d-flex">
                            <div class="toast-body">
                                @lang('translation.courseModified').
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                                aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- error toastr --}}
            <div id="toastContainerError" class="position-fixed top-0 end-0 " style="z-index: 1060;margin-top: 5%;">
                <div id="toastrError" class="toast overflow-hidden" role="alert" aria-live="assertive"
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
    <script>
        $(document).ready(function() {
            window.fetchCourses = function(query = '', page = 1) {
                $.ajax({
                    url: baseUrl + "/courses/popular?page=" +
                        page, // Replace with your route for search
                    method: 'GET',
                    data: {
                        query: query
                    },
                    success: function(data) {
                        $('#courseTableBody').html(data.table_data);
                        $('#paginationLinks').html(data.pagination);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', error); // Log any error
                    }
                });
            };

            fetchCourses(); // Initial call to fetch courses

            $('#search').on('keyup', function() {
                var query = $(this).val();
                fetchCourses(query);
            });

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[
                    1]; // Get the page number from the pagination link
                var query = $('#search').val(); // Get the current search query
                fetchCourses(query, page); // Fetch the new page with AJAX
            });
        });
    </script>
    <script>
        var modifyModal = document.getElementById('modifyModal');

        modifyModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var courseId = button.getAttribute('data-bs-courseid');
            var courseName = button.getAttribute('data-bs-coursename');
            var popularOrder = button.getAttribute('data-bs-popularorder');

            var modalTitle = modifyModal.querySelector('.modal-title');
            var modalCourseId = document.getElementById('course-id');
            var modalPopularOrder = document.getElementById('teacherPopularOrderInput');

            modalTitle.textContent = courseName;
            modalCourseId.value = courseId;
            modalPopularOrder.value = popularOrder;
        });

        $('#modifyCourseButton').click(function() {
            var form = $('#modifyCourseForm');
            var formData = form.serialize(); // Serialize form data
            $.ajax({
                type: 'POST',
                url: baseUrl + '/courses/order-update', // Change this to your actual route
                data: formData,
                success: function(response) {
                    // Assuming the server returns a JSON response
                    if (response.status == 200) {
                        // Close the modal
                        $('#modifyModal').modal('hide');
                        fetchCourses();
                        fireToastr(response.message)
                    } else {
                        handleErrorResponse(response)
                    }
                },
                error: function(xhr, status, error) {
                    const response = xhr.responseJSON || {
                        message: 'An unexpected error occurred.'
                    };
                    handleErrorResponse(response)
                }
            });
        });
    </script>
    <script>
        const currentLang = document.documentElement.lang || 'ar';

        function fireToastr(message) {
            const toastContainer = document.getElementById('toastContainer');
            if (currentLang == 'ar') {
                toastContainer.style.marginLeft = '1%';
            } else {
                toastContainer.style.marginRight = '1%';
            }
            const toastLiveExample3 = document.getElementById("toastr");
            const toastBody = toastLiveExample3.querySelector('.toast-body');
            toastBody.innerHTML = message;
            var toast = new bootstrap.Toast(toastLiveExample3, {
                delay: 3000
            });
            toast.show();
        }

        function handleErrorResponse(response) {
            const errors = response.errors || [];
            const errorMessage = errors.length > 0 ?
                errors.join('<br>') // Join error messages with a line break for display
                :
                response.message || 'An unexpected error occurred'; // Fallback message

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
                delay: 3000
            });
            toast.show();
        }
    </script>
    <script src="{{ URL::asset('assets/js/pages/bootstrap-toasts.init.js') }}"></script>
    <!-- Varying Modal Content js -->
@endsection
