@extends('layouts.master')
@section('title')
    @lang('translation.activeCourses')
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            @lang('translation.Courses')
        @endslot
        @slot('title')
            @lang('translation.activeCourses')
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mt-2">
                            <h4 class="card-title">@lang('translation.Courses')</h4>
                            <p class="card-title-desc">@lang('translation.activeCourses')</p>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end align-items-center">
                            <!-- Button -->
                            <div class="mb-2">
                                <a href="{{ route('courses.popular') }}"
                                    class="btn btn-outline-primary waves-effect waves-light">@lang('translation.ManagePopularCourses')</a>
                            </div>
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
                                        <label for="teacherCommistion" class="col-form-label">@lang('translation.teacherCommistion')</label>
                                        <input type="number" name="teacherCommistion" class="form-control"
                                            id="teacherCommisionInput">
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch form-switch-lg mb-2">
                                            <input type="checkbox" name="addToPopularCourses" value="1"
                                                class="form-check-input" id="addToPopularCourses">
                                            <label class="form-check-label" for="addToPopularCourses">@lang('translation.addToPopularCourses')</label>
                                        </div>
                                        <div class="form-check form-switch form-switch-lg">
                                            <input type="checkbox" name="isMobileOnly" value="1" class="form-check-input"
                                                id="isMobileOnly">
                                            <label class="form-check-label" for="isMobileOnly">@lang('translation.isMobileOnly')</label>
                                        </div>
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
            @can('discount')
                {{-- add discount modal --}}
                <div class="modal fade" id="addDiscountModal" tabindex="-1" aria-labelledby="addDiscountModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addDiscountModalLabel"></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="addDiscountForm">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="col-md-2 col-form-label">@lang('translation.discountType')</label>
                                        <select name="discountType" class="form-select">
                                            <option value="percentage">@lang('translation.percentage')</option>
                                            <option value="fixed">@lang('translation.fixedPrice')</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="discount" class="col-form-label">@lang('translation.discount')</label>
                                        <input type="number" name="discount" class="form-control" id="discountInput">
                                    </div>
                                    <input type="hidden" name="courseId" class="form-control" id="add-discount-course-id">
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">@lang('translation.close')</button>
                                <button type="submit" id="addDiscountButton"
                                    class="btn btn-success">@lang('translation.accept')</button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- remove discount modal --}}
                <div class="modal fade" id="removeDiscountModal" tabindex="-1" aria-labelledby="removeDiscountModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modifyModalLabel"></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="removeDiscountForm">
                                    @csrf
                                    <div class="mb-3">
                                        <p>
                                            @lang('translation.areYouSureRemoveDiscount')
                                        </p>
                                    </div>
                                    <input type="hidden" name="courseId" class="form-control"
                                        id="remove-discount-course-id">
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">@lang('translation.close')</button>
                                <button type="button" id="removeDiscountButton"
                                    class="btn btn-danger">@lang('translation.accept')</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            {{-- toastr --}}
            <div id="toastContainer" class="position-fixed top-0 end-0 " style="z-index: 1060;margin-top: 5%;">
                <div id="toastr" class="toast overflow-hidden" role="alert" aria-live="assertive"
                    aria-atomic="true">
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
                    url: baseUrl + "/courses/active?page=" +
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
    <script src="{{ URL::asset('assets/js/used/modify-modal.init.js') }}"></script>
    <!-- Varying Modal Content js -->
    <script src="{{ URL::asset('assets/js/pages/bootstrap-toasts.init.js') }}"></script>
@endsection
