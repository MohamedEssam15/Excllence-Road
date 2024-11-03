@extends('layouts.master')
@section('title')
    @lang('translation.revenue')
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            @lang('translation.teachers')
        @endslot
        @slot('title')
            @lang('translation.revenue')
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <h4 class="card-title">@lang('translation.teacherName') : {{ $teacher->name }}</h4>
                            <p class="card-title-desc">@lang('translation.revenue')</p>
                        </div>

                        <div class="col-md-6">
                            <div class="form-inline float-md-end mb-3">
                                {{-- <div class="search-box ms-2">
                                    <div class="position-relative">
                                        <input type="text" id="search" class="form-control rounded bg-light border-0"
                                            placeholder="@lang('translation.search')" style="width: 100%;direction: ltr">
                                        <i class="mdi mdi-magnify search-icon"></i>
                                    </div>
                                </div> --}}

                            </div>
                        </div>


                    </div>
                    <!-- end row -->
                    <div class="table-responsive mb-4">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">@lang('translation.orderNumber')</th>
                                    <th scope="col">@lang('translation.from')</th>
                                    <th scope="col">@lang('translation.name')</th>
                                    <th scope="col">@lang('translation.orderDate')</th>
                                    <th scope="col">@lang('translation.revune')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 01;
                                @endphp
                                @if (isset($userRevenueDetials[0]))
                                    @foreach ($userRevenueDetials as $revenue)
                                        <tr>
                                            <th scope="row">{{ $i++ }}</th>
                                            <td>
                                                <p href="#" class="text-reset">
                                                    {{ $revenue->order->order_number }}
                                                </p>
                                            </td>
                                            <td>
                                                @if ($revenue->order->is_package)
                                                    @lang('translation.package')
                                                @else
                                                    @lang('translation.course')
                                                @endif
                                            </td>
                                            <td>
                                                {{ $revenue->order->product->translate()->name }}
                                            </td>
                                            <td>
                                                {{ $revenue->created_at->format('Y-m-d g:i A') }}
                                            </td>
                                            <td>
                                                {{ $revenue->revenues }} @lang('translation.currency')
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="10" class="text-center">@lang('translation.noRevunue')</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <div class="d-flex justify-content-center" id="paginationLinks">
                        {{ $userRevenueDetials->links('vendor.pagination.bootstrap-5') }}
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
