@extends('layouts.master')
@section('title')
    @lang('translation.activeStudents')
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            @lang('translation.users')
        @endslot
        @slot('title')
            @lang('translation.activeStudents')
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <h4 class="card-title">@lang('translation.users')</h4>
                            <p class="card-title-desc">@lang('translation.activeStudents')</p>
                        </div>

                        <div class="col-md-6">
                            <div class="form-inline float-md-end mb-3">
                                <div class="search-box ms-2">
                                    <div class="position-relative">
                                        <input type="text" id="search" class="form-control rounded bg-light border-0"
                                            placeholder="@lang('translation.search')" style="width: 100%;direction: ltr">
                                        <i class="mdi mdi-magnify search-icon"></i>
                                    </div>
                                </div>

                            </div>
                        </div>


                    </div>
                    <!-- end row -->
                    <div class="table-responsive mb-4">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 50px;">
                                        #
                                    </th>
                                    <th scope="col">@lang('translation.name')</th>
                                    <th scope="col">@lang('translation.Email')</th>
                                    <th scope="col" style="width: 200px;">@lang('translation.actions')</th>
                                </tr>
                            </thead>
                            <tbody id="teachersTableBody">
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <div class="d-flex justify-content-center" id="paginationLinks">
                        {{-- {{ $courses->links('vendor.pagination.bootstrap-5') }} --}}
                    </div>
                </div>
            </div>

            @can('block-unblock-student')
                <div class="modal fade" id="blockModal" tabindex="-1" aria-labelledby="blockModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="blockModalLabel"></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="blockStudentForm">
                                    @csrf
                                    <div class="mb-3">
                                        <p>
                                            @lang('translation.areYouSureBlock')
                                        </p>
                                    </div>
                                    <input type="hidden" name="studentId" class="form-control" id="student-id">
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">@lang('translation.close')</button>
                                <button type="button" id="blockStudentButton"
                                    class="btn btn-danger">@lang('translation.accept')</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan

            @can('add-course-to-student')
                {{-- add free course or package modal --}}
                <div class="modal fade" id="addFreeCourseOrPackageModal" tabindex="-1"
                    aria-labelledby="addFreeCourseOrPackageModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addFreeCourseOrPackageModalLabel"></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="addFreeCourseOrPackageForm">
                                    @csrf
                                    <!-- First Select Box -->
                                    <div class="mb-3">
                                        <label for="typeSelect" class="form-label">@lang('translation.selectType')</label>
                                        <select class="form-select" name="type" id="typeSelect" required>
                                            <option value="">-- @lang('translation.select') --</option>
                                            <option value="course">@lang('translation.course')</option>
                                            <option value="package">@lang('translation.package')</option>
                                        </select>
                                    </div>
                                    <!-- Second Select Box -->
                                    <div class="mb-3">
                                        <label for="itemSelect" class="form-label">@lang('translation.selectItem')</label>
                                        <select class="form-select" name="itemId" id="itemSelect" required>
                                            <option value="">-- @lang('translation.select') --</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="studentId" class="form-control"
                                        id="add-free-course-or-package-student-id">
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">@lang('translation.close')</button>
                                <button type="button" id="addFreeCourseOrPackageButton"
                                    class="btn btn-success">@lang('translation.accept')</button>
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
                            <div class="toast-body" id="toastrBody">
                                @if (session('status'))
                                    {{ session('status') }}
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
            window.fetchData = function(query = '', page = 1) {
                $.ajax({
                    url: baseUrl + "/users/students/active?page=" +
                        page, // Replace with your route for search
                    method: 'GET',
                    data: {
                        query: query
                    },
                    success: function(data) {
                        $('#teachersTableBody').html(data.table_data);
                        $('#paginationLinks').html(data.pagination);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX error:', error); // Log any error
                    }
                });
            };

            fetchData(); // Initial call to fetch courses

            $('#search').on('keyup', function() {
                var query = $(this).val();
                fetchData(query);
            });

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[
                    1]; // Get the page number from the pagination link
                var query = $('#search').val(); // Get the current search query
                fetchData(query, page); // Fetch the new page with AJAX
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            var addFreeCourseOrPackageModal = document.getElementById('addFreeCourseOrPackageModal');

            addFreeCourseOrPackageModal.addEventListener('show.bs.modal', function(event) {
                document.getElementById('addFreeCourseOrPackageForm').reset();
                var button = event.relatedTarget;
                var studentId = button.getAttribute('data-bs-studentid');
                var StudentName = button.getAttribute('data-bs-studentname');
                var modalTitle = addFreeCourseOrPackageModal.querySelector(
                    '.modal-title');
                var modalStudentId = document.getElementById('add-free-course-or-package-student-id');

                modalTitle.textContent = "@lang('translation.addFreeCourseOrPackageTo')" + ' ' + StudentName;
                modalStudentId.value = studentId;
            });
            $('#typeSelect').change(function() {
                const selectedType = $(this).val();
                const itemSelect = $('#itemSelect');

                // Clear and disable the second select box while loading
                itemSelect.empty().append('<option value="">-- @lang('translation.loading') --</option>').prop(
                    'disabled', true);

                // Fetch options from the endpoint based on the selected type
                if (selectedType) {
                    $.ajax({
                        type: 'GET',
                        url: baseUrl +
                            '/users/students/get-courses-or-packages/' +
                            selectedType, // Adjust the endpoint URL to your needs
                        success: function(response) {
                            if (response.status === 200) {
                                // Populate the second select box
                                itemSelect.empty().append(
                                    '<option value="">-- @lang('translation.select') --</option>');
                                response.data.forEach(item => {
                                    itemSelect.append(
                                        `<option value="${item.id}">${item.name}</option>`
                                    );
                                });
                                itemSelect.prop('disabled', false);
                            } else {
                                fireErrorToastr(response.message || 'Failed to fetch items.');
                            }
                        },
                        error: function() {
                            fireErrorToastr('An error occurred while fetching items.');
                        }
                    });
                } else {
                    itemSelect.empty().append('<option value="">-- @lang('translation.select') --</option>').prop(
                        'disabled',
                        true);
                }
            });
            $('#addFreeCourseOrPackageButton').click(function() {
                const selectedType = $('#typeSelect').val();
                const selectedItem = $('#itemSelect').val();
                const studentId = $('#add-free-course-or-package-student-id').val();
                if (!selectedType || !selectedItem || !studentId) {
                    fireErrorToastr('@lang('translation.pleaseSelectBothFields')');
                    return;
                }
                var form = $('#addFreeCourseOrPackageForm');
                var formData = form.serialize(); // Serialize form data

                $.ajax({
                    type: 'POST',
                    url: baseUrl +
                        '/users/students/add-free-course-or-package', // Change this to your actual route
                    data: formData,
                    success: function(response) {
                        if (response.status == 200) {
                            // Close the modal
                            $('#addFreeCourseOrPackageModal').modal('hide');
                            fetchData();
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
            // Handle form submission
            $('#addFreeCourseOrPackageForm').submit(function(event) {
                event.preventDefault(); // Prevent default form submission

                const selectedType = $('#typeSelect').val();
                const selectedItem = $('#itemSelect').val();
                const studentId = $('#add-free-course-or-package-student-id').val();
                if (!selectedType || !selectedItem || !studentId) {
                    fireErrorToastr('Please select both fields.');
                    return;
                }
                var formData = $(this).serialize(); // Serialize form data
                // Submit the selected values to another endpoint
                $.ajax({
                    type: 'POST',
                    url: baseUrl +
                        '/users/students/add-free-course-or-package', // Adjust the endpoint URL to your needs
                    data: formData,
                    success: function(response) {
                        if (response.status === 200) {
                            fireToastr(response.message)
                            $('#selectModal').modal('hide'); // Close the modal
                        } else {
                            fireErrorToastr(response.message || 'Failed to submit selection.');
                        }
                    },
                    error: function() {
                        fireErrorToastr('An error occurred while submitting the selection.');
                    }
                });
            });
        });
    </script>
    <script>
        const currentLang = document.documentElement.lang || 'ar';

        function fireToastr(message = null) {
            const toastContainer = document.getElementById('toastContainer');
            if (currentLang == 'ar') {
                toastContainer.style.marginLeft = '1%';
            } else {
                toastContainer.style.marginRight = '1%';
            }
            const toastLiveExample3 = document.getElementById("toastr");
            if (message != null) {
                const toastBody = toastLiveExample3.querySelector('.toast-body');
                toastBody.innerHTML = message;
            }
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
    <script>
        $(document).ready(function() {
            var blockModal = document.getElementById('blockModal');

            blockModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var studentId = button.getAttribute('data-bs-studentid');
                var StudentName = button.getAttribute('data-bs-studentname');
                var modalTitle = blockModal.querySelector('.modal-title');
                var modalStudentId = document.getElementById('student-id');

                modalTitle.textContent = StudentName;
                modalStudentId.value = studentId;
            });

            // Handle the "Cancel" button click
            $('#blockStudentButton').click(function() {
                var form = $('#blockStudentForm');
                var formData = form.serialize(); // Serialize form data

                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/users/students/block', // Change this to your actual route
                    data: formData,
                    success: function(response) {
                        if (response.status == 200) {
                            // Close the modal
                            $('#blockModal').modal('hide');
                            fetchData();
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
        });
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
    <script src="{{ URL::asset('assets/js/pages/bootstrap-toasts.init.js') }}"></script>
    <!-- Varying Modal Content js -->
@endsection
