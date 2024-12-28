@extends('layouts.master')
@section('title')
    @lang('translation.activePackages')
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            @lang('translation.packages')
        @endslot
        @slot('title')
            @lang('translation.activePackages')
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title">@lang('translation.packages')</h4>
                            <p class="card-title-desc">@lang('translation.activePackages')</p>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end align-items-center">

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
                                    <th>@lang('translation.discount')</th>
                                    <th>@lang('translation.discountType')</th>
                                    <th>@lang('translation.newPrice')</th>
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
            @can('discount')
                {{-- add discount modal --}}
                <div class="modal fade" id="addDiscountModal" tabindex="-1" aria-labelledby="addDiscountModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addDiscountModalLabel"></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                    <input type="hidden" name="packageId" class="form-control" id="add-discount-package-id">
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
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="removeDiscountForm">
                                    @csrf
                                    <div class="mb-3">
                                        <p>
                                            @lang('translation.areYouSureRemoveDiscount')
                                        </p>
                                    </div>
                                    <input type="hidden" name="packageId" class="form-control" id="remove-discount-package-id">
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
                <div id="toastr" class="toast overflow-hidden" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="align-items-center text-white
                            bg-success border-0">
                        <div class="d-flex">
                            <div class="toast-body">
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
            window.fetchPackages = function(query = '', page = 1) {
                $.ajax({
                    url: baseUrl + "/packages/active?page=" +
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

            fetchPackages(); // Initial call to fetch packages

            $('#search').on('keyup', function() {
                var query = $(this).val();
                fetchPackages(query);
            });

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[
                    1]; // Get the page number from the pagination link
                var query = $('#search').val(); // Get the current search query
                fetchPackages(query, page); // Fetch the new page with AJAX
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
            var addDiscountModal = document.getElementById('addDiscountModal');
            var removeDiscountModal = document.getElementById('removeDiscountModal');


            addDiscountModal.addEventListener('show.bs.modal', function(event) {
                document.getElementById('addDiscountForm').reset();
                var button = event.relatedTarget;
                var packageId = button.getAttribute('data-bs-packageid');
                var packageName = button.getAttribute('data-bs-packagename');

                var modalTitle = addDiscountModal.querySelector('.modal-title');
                var modalPackageId = document.getElementById('add-discount-package-id');

                modalTitle.textContent = packageName;
                modalPackageId.value = packageId;
            });

            $('#addDiscountButton').click(function(event) {
                event.preventDefault();
                var form = $('#addDiscountForm');
                var formData = form.serialize(); // Serialize form data

                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/packages/add-discount', // Change this to your actual route
                    data: formData,
                    success: function(response) {
                        // Assuming the server returns a JSON response
                        if (response.status == 200) {
                            // Close the modal
                            $('#addDiscountModal').modal('hide');
                            fetchPackages();
                            fireToastr(response.message)
                        } else {
                            $('#addDiscountModal').modal('hide');
                            handleErrorResponse(response)
                        }
                    },
                    error: function(xhr, status, error) {
                        const response = xhr.responseJSON || {
                            message: 'An unexpected error occurred.'
                        };
                        $('#addDiscountModal').modal('hide');
                        handleErrorResponse(response)
                    }
                });
            });
            $('#addDiscountForm').submit(function(event) {
                event.preventDefault();
                var formData = $(this).serialize(); // Serialize form data
                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/packages/add-discount', // Change this to your actual route
                    data: formData,
                    success: function(response) {
                        // Assuming the server returns a JSON response
                        if (response.status == 200) {
                            // Close the modal
                            $('#addDiscountModal').modal('hide');
                            fetchPackages();
                            fireToastr(response.message)
                        } else {
                            $('#addDiscountModal').modal('hide');
                            handleErrorResponse(response)
                        }
                    },
                    error: function(xhr, status, error) {
                        const response = xhr.responseJSON || {
                            message: 'An unexpected error occurred.'
                        };
                        $('#addDiscountModal').modal('hide');
                        handleErrorResponse(response)
                    }
                });
            });
            removeDiscountModal.addEventListener('show.bs.modal', function(event) {
                document.getElementById('removeDiscountForm').reset();
                var button = event.relatedTarget;
                var packageId = button.getAttribute('data-bs-packageid');
                var packageName = button.getAttribute('data-bs-packagename');
                var modalTitle = removeDiscountModal.querySelector('.modal-title');
                var modalPackageId = document.getElementById('remove-discount-package-id');

                modalTitle.textContent = packageName;
                modalPackageId.value = packageId;
            });
            $('#removeDiscountButton').click(function(event) {
                event.preventDefault();
                var form = $('#removeDiscountForm');
                var formData = form.serialize(); // Serialize form data

                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/packages/remove-discount', // Change this to your actual route
                    data: formData,
                    success: function(response) {
                        if (response.status == 200) {
                            // Close the modal
                            $('#removeDiscountModal').modal('hide');
                            fetchPackages();
                            fireToastr(response.message)
                        } else {
                            $('#removeDiscountModal').modal('hide');
                            handleErrorResponse(response)
                        }
                    },
                    error: function(xhr, status, error) {
                        const response = xhr.responseJSON || {
                            message: 'An unexpected error occurred.'
                        };
                        $('#removeDiscountModal').modal('hide');
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
            fireToastr(@json(session('status')))
        </script>
    @endif
    <script src="{{ URL::asset('assets/js/pages/bootstrap-toasts.init.js') }}"></script>
    <!-- Varying Modal Content js -->
@endsection
