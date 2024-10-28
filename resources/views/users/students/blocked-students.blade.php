@extends('layouts.master')
@section('title')
    @lang('translation.blockedStudents')
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            @lang('translation.users')
        @endslot
        @slot('title')
            @lang('translation.blockedStudents')
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <h4 class="card-title">@lang('translation.users')</h4>
                            <p class="card-title-desc">@lang('translation.blockedStudents')</p>
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


            <div class="modal fade" id="acceptModal" tabindex="-1" aria-labelledby="acceptModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="acceptModalLabel"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="acceptStudentForm">
                                @csrf
                                <div class="mb-3">
                                    <p>
                                        @lang('translation.areYouSureAccept')
                                    </p>
                                </div>
                                <input type="hidden" name="studentId" class="form-control" id="student-id">
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">@lang('translation.close')</button>
                            <button type="button" id="acceptStudentButton"
                                class="btn btn-success">@lang('translation.accept')</button>
                        </div>
                    </div>
                </div>
            </div>


            {{-- toastr --}}
            <div id="toastContainer" class="position-fixed top-0 end-0 " style="z-index: 1060;margin-top: 5%;">
                <div id="toastr" class="toast overflow-hidden" role="alert" aria-live="assertive" aria-atomic="true">
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
            window.fetchData = function(query = '', page = 1) {
                $.ajax({
                    url: baseUrl + "/users/students/blocked?page=" +
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
            var acceptModal = document.getElementById('acceptModal');

            acceptModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var studentId = button.getAttribute('data-bs-studentid');
                var StudentName = button.getAttribute('data-bs-studentname');
                var modalTitle = acceptModal.querySelector('.modal-title');
                var modalStudentId = document.getElementById('student-id');

                modalTitle.textContent = StudentName;
                modalStudentId.value = studentId;
            });

            // Handle the "Cancel" button click
            $('#acceptStudentButton').click(function() {
                var form = $('#acceptStudentForm');
                var formData = form.serialize(); // Serialize form data

                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/users/students/reactive', // Change this to your actual route
                    data: formData,
                    success: function(response) {
                        if (response.status == 200) {
                            // Close the modal
                            $('#acceptModal').modal('hide');
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
