@extends('layouts.master')
@section('title')
    @lang('translation.expiredPackages')
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            @lang('translation.packages')
        @endslot
        @slot('title')
            @lang('translation.expiredPackages')
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
                                <p class="card-title-desc">@lang('translation.expiredPackages')</p>
                            @endrole
                        </div>
                        <div class="col-md-6 d-flex justify-content-end align-items-center">
                            <!-- Button aligned to the top right -->
                            {{-- <a href="{{route('courses.add')}}" class="btn btn-outline-primary waves-effect waves-light">@lang('translation.addCourses')</a> --}}
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
                                    <th>@lang('translation.price')</th>
                                    <th>@lang('translation.startDate')</th>
                                    <th>@lang('translation.endDate')</th>
                                    <th>@lang('translation.isPopular')</th>
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
            @can('delete-package')
            {{-- delete package modal --}}
            <div class="modal fade" id="deletePackageModal" tabindex="-1" aria-labelledby="deletePackageModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deletePackageModalLabel"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="deletePackageForm">
                                @csrf
                                @method('DELETE')
                                <div class="mb-3">
                                    <p>
                                        @lang('translation.areYouSureDelete')
                                    </p>
                                </div>
                                <input type="hidden" name="packageId" class="form-control" id="delete-package-id">
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">@lang('translation.close')</button>
                            <button type="button" id="deletePackageButton"
                                class="btn btn-danger">@lang('translation.accept')</button>
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
            window.fetchCourses = function(query = '', page = 1) {
                $.ajax({
                    url: baseUrl + "/packages/expired?page=" +
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
        $(document).ready(function() {
            var deletePackageModal = document.getElementById('deletePackageModal');

            deletePackageModal.addEventListener('show.bs.modal', function(event) {
                document.getElementById('deletePackageForm').reset();
                var button = event.relatedTarget;
                var packageId = button.getAttribute('data-bs-packageid');
                var packageName = button.getAttribute('data-bs-packagename');
                var modalTitle = deletePackageModal.querySelector('.modal-title');
                var modalPackageId = document.getElementById('delete-package-id');

                modalTitle.textContent = packageName;
                modalPackageId.value = packageId;
            });
            $('#deletePackageButton').click(function(event) {
                event.preventDefault();
                var form = $('#deletePackageForm');
                var formData = form.serialize(); // Serialize form data

                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/packages/delete-package', // Change this to your actual route
                    data: formData,
                    success: function(response) {
                        if (response.status == 200) {
                            // Close the modal
                            $('#deletePackageModal').modal('hide');
                            fetchCourses();
                            fireToastr(response.message)
                        } else {
                            $('#deletePackageModal').modal('hide');
                            handleErrorResponse(response)
                        }
                    },
                    error: function(xhr, status, error) {
                        const response = xhr.responseJSON || {
                            message: 'An unexpected error occurred.'
                        };
                        $('#deletePackageModal').modal('hide');
                        handleErrorResponse(response)
                    }
                });
            });
        });
    </script>
    <script src="{{ URL::asset('assets/js/pages/bootstrap-toasts.init.js') }}"></script>
    <!-- Varying Modal Content js -->
@endsection
