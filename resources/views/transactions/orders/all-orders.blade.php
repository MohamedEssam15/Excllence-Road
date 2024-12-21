@extends('layouts.master')
@section('title')
    @lang('translation.orders')
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            @lang('translation.transaction')
        @endslot
        @slot('title')
            @lang('translation.orders')
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <h4 class="card-title">@lang('translation.transaction')</h4>
                            <p class="card-title-desc">@lang('translation.orders')</p>
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
                                    <th scope="col">@lang('translation.orderNumber')</th>
                                    <th scope="col">@lang('translation.amount')</th>
                                    <th scope="col">@lang('translation.client')</th>
                                    <th scope="col">@lang('translation.paidFor')</th>
                                    <th scope="col">@lang('translation.paidForName')</th>
                                    <th scope="col">@lang('translation.paidDate')</th>
                                    <th scope="col">@lang('translation.discount')</th>
                                    <th scope="col">@lang('translation.discountType')</th>
                                    <th scope="col">@lang('translation.addedBy')</th>
                                    <th scope="col">@lang('translation.status')</th>
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


            <div class="modal fade" id="blockModal" tabindex="-1" aria-labelledby="blockModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="blockModalLabel"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="blockAdminForm">
                                @csrf
                                <div class="mb-3">
                                    <p>
                                        @lang('translation.areYouSureBlock')
                                    </p>
                                </div>
                                <input type="hidden" name="adminId" class="form-control" id="admin-id">
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">@lang('translation.close')</button>
                            <button type="button" id="blockAdminButton" class="btn btn-danger">@lang('translation.accept')</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="unblockModal" tabindex="-1" aria-labelledby="unblockModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="unblockModalLabel"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="unBlockAdminForm">
                                @csrf
                                <div class="mb-3">
                                    <p>
                                        @lang('translation.areYouSureAccept')
                                    </p>
                                </div>
                                <input type="hidden" name="adminId" class="form-control" id="unblocked-admin-id">
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">@lang('translation.close')</button>
                            <button type="button" id="unBlockAdminButton"
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
                    url: baseUrl + "/transactions/orders?page=" +
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
            var blockModal = document.getElementById('blockModal');

            blockModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var adminId = button.getAttribute('data-bs-adminid');
                var adminName = button.getAttribute('data-bs-adminname');
                var modalTitle = blockModal.querySelector('.modal-title');
                var modalAdminId = document.getElementById('admin-id');

                modalTitle.textContent = adminName;
                modalAdminId.value = adminId;
            });
            var unblockModal = document.getElementById('unblockModal');

            unblockModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var unBlockedAdminId = button.getAttribute('data-bs-adminid');
                var unBlockedAdminName = button.getAttribute('data-bs-adminname');
                var unBlockedModalTitle = unblockModal.querySelector('.modal-title');
                var unBlockedmodalAdminId = document.getElementById('unblocked-admin-id');
                console.log(unBlockedAdminId);
                unBlockedModalTitle.textContent = unBlockedAdminName;
                unBlockedmodalAdminId.value = unBlockedAdminId;
            });

            // Handle the "Cancel" button click
            $('#blockAdminButton').click(function() {
                var form = $('#blockAdminForm');
                var formData = form.serialize(); // Serialize form data

                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/users/admins/block', // Change this to your actual route
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
            $('#unBlockAdminButton').click(function() {
                var form = $('#unBlockAdminForm');
                var formData = form.serialize(); // Serialize form data

                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/users/admins/unblock', // Change this to your actual route
                    data: formData,
                    success: function(response) {
                        if (response.status == 200) {
                            // Close the modal
                            $('#unblockModal').modal('hide');
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
