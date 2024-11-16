@extends('layouts.master')
@section('title')
    @lang('translation.Dashboard')
@endsection
@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            @lang('translation.appName')
        @endslot
        @slot('title')
            @lang('translation.Dashboard')
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="float-end mt-2">
                        <div id="total-revenue-chart" data-colors='["--bs-primary"]'></div>
                    </div>
                    <div>
                        <h4 class="mb-1 mt-1">$<span
                                data-plugin="counterup">{{ number_format($totalRevenue['totalRevenue']) }}</span></h4>
                        <p class="text-muted mb-0">@lang('translation.totalRevenue')</p>
                    </div>
                    <p class="text-muted mt-3 mb-0"><span class="text-success me-1"><i
                                class="mdi mdi-arrow-up-bold me-1"></i>{{ number_format($totalRevenue['growthPercentage']) }}%</span>
                        @lang('translation.fromLastMonth')
                    </p>
                </div>
            </div>
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="float-end mt-2">
                        <div id="orders-chart" data-colors='["--bs-success"]'> </div>
                    </div>
                    <div>
                        <h4 class="mb-1 mt-1"><span
                                data-plugin="counterup">{{ number_format($totalOrders['totalOrders']) }}</span></h4>
                        <p class="text-muted mb-0">@lang('translation.ordersCompleted')</p>
                    </div>
                    <p class="text-muted mt-3 mb-0"><span class="text-success me-1"><i
                                class="mdi mdi-arrow-up-bold me-1"></i>{{ number_format($totalOrders['ordersGrowthPercentage']) }}%</span>
                        @lang('translation.fromLastMonth')
                    </p>
                </div>
            </div>
        </div> <!-- end col-->

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="float-end mt-2">
                        <div id="customers-chart" data-colors='["--bs-primary"]'> </div>
                    </div>
                    <div>
                        <h4 class="mb-1 mt-1"><span
                                data-plugin="counterup">{{ number_format($totalStudents['totalStudents']) }}</span></h4>
                        <p class="text-muted mb-0">@lang('translation.students')</p>
                    </div>
                    <p class="text-muted mt-3 mb-0"><span class="text-success me-1"><i
                                class="mdi mdi-arrow-up-bold me-1"></i>{{ number_format($totalStudents['growthPercentage']) }}%</span>
                        @lang('translation.fromLastMonth')
                    </p>
                </div>
            </div>
        </div> <!-- end col-->
        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="float-end mt-2">
                        <div id="teachers-chart" data-colors='["--bs-primary"]'> </div>
                    </div>
                    <div>
                        <h4 class="mb-1 mt-1"><span
                                data-plugin="counterup">{{ number_format($totalTeachers['totalTeachers']) }}</span></h4>
                        <p class="text-muted mb-0">@lang('translation.teachers')</p>
                    </div>
                    <p class="text-muted mt-3 mb-0"><span class="text-success me-1"><i
                                class="mdi mdi-arrow-up-bold me-1"></i>{{ number_format($totalTeachers['growthPercentage']) }}%</span>
                        @lang('translation.fromLastMonth')
                    </p>
                </div>
            </div>
        </div> <!-- end col-->

        {{-- <div class="col-md-6 col-xl-3">

            <div class="card">
                <div class="card-body">
                    <div class="float-end mt-2">
                        <div id="growth-chart" data-colors='["--bs-warning"]'></div>
                    </div>
                    <div>
                        <h4 class="mb-1 mt-1">+ <span data-plugin="counterup">12.58</span>%</h4>
                        <p class="text-muted mb-0">Growth</p>
                    </div>
                    <p class="text-muted mt-3 mb-0"><span class="text-success me-1"><i
                                class="mdi mdi-arrow-up-bold me-1"></i>10.51%</span> since last week
                    </p>
                </div>
            </div>
        </div> <!-- end col--> --}}
    </div> <!-- end row-->

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="float-end">
                        <div class="dropdown">
                            <p class="dropdown-toggle text-reset">
                                <span class="fw-semibold">@lang('translation.sortBy')</span> <span
                                    class="text-muted">@lang('translation.yearly')</span>
                            </p>


                        </div>
                    </div>
                    <h4 class="card-title mb-4">@lang('translation.compareBetweenPackagesAndCourses')</h4>



                    <div class="mt-3">
                        <div id="sales-analytics-chart" data-colors='["--bs-primary", "#dfe2e6", "--bs-warning"]'
                            class="apex-charts" dir="ltr"></div>
                    </div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->

    </div> <!-- end row-->

    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">@lang('translation.topTeachers')</h4>

                    <div data-simplebar style="max-height: 339px;">
                        <div class="table-responsive">
                            <table class="table table-borderless table-centered table-nowrap">
                                <tbody>
                                    @foreach ($topTeachers as $teacher)
                                        <tr>
                                            <td style="width: 20px;"><img src="{{ $teacher->getAvatarPath() }}"
                                                    class="avatar-xs rounded-circle " alt="..."></td>
                                            <td>
                                                <h6 class="font-size-15 mb-1 fw-normal">{{ $teacher->name }}</h6>

                                            </td>
                                            </td>
                                            <td class="text-muted fw-semibold text-end"><i
                                                    class="icon-xs icon me-2 text-success"
                                                    data-feather="trending-up"></i>{{ $teacher->teacher_courses_count }}
                                                @lang('translation.coursecountable')</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> <!-- enbd table-responsive-->
                    </div> <!-- data-sidebar-->
                </div><!-- end card-body-->
            </div> <!-- end card-->
        </div><!-- end col -->
        {{-- top selling section --}}
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="float-end">
                        <div class="dropdown">
                            <p class="dropdown-toggle text-reset">
                                <span class="fw-semibold">@lang('translation.sortBy')</span> <span
                                    class="text-muted">@lang('translation.topSeller')</span>
                            </p>
                        </div>
                    </div>

                    <h4 class="card-title mb-4">@lang('translation.topSellingCourses')</h4>
                    @foreach ($topCourses['topCourses'] as $index => $course)
                        <div class="row align-items-center g-0 mt-3">
                            <div class="col-sm-3 mt-1">
                                <p class="text-truncate mt-1 mb-0"><i
                                        class="mdi mdi-circle-medium text-{{ ['primary', 'secondary', 'success', 'danger', 'warning', 'purple', 'info', 'dark'][$index % 8] }} me-2"></i>
                                    {{ $course->translate()->name ?? $course->name }} </p>
                            </div>

                            <div class="col-sm-9">
                                <div class="progress mt-1" style="height: 6px;">
                                    <div class="progress-bar progress-bar bg-{{ ['primary', 'secondary', 'success', 'danger', 'warning', 'purple', 'info', 'dark'][$index % 8] }}"
                                        role="progressbar" title="{{ $course->orders_count }}"
                                        style="width: {{ ($course->orders_count / $topCourses['totalCoursesOrders']) * 100 }}%"
                                        aria-valuenow="{{ ($course->orders_count / $topCourses['totalCoursesOrders']) * 100 }}"
                                        aria-valuemin="0"
                                        aria-valuemax="{{ ($course->orders_count / $topCourses['totalCoursesOrders']) * 100 }}">
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end row-->
                    @endforeach

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end Col -->
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Latest Transaction</h4>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 20px;">
                                        <div class="form-check font-size-16">
                                            <input type="checkbox" class="form-check-input" id="customCheck1">
                                            <label class="form-check-label" for="customCheck1">&nbsp;</label>
                                        </div>
                                    </th>
                                    <th>Order ID</th>
                                    <th>Billing Name</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Payment Status</th>
                                    <th>Payment Method</th>
                                    <th>View Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="form-check font-size-16">
                                            <input type="checkbox" class="form-check-input" id="customCheck2">
                                            <label class="form-check-label" for="customCheck2">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td><a href="javascript: void(0);" class="text-body fw-bold">#MB2540</a> </td>
                                    <td>Neal Matthews</td>
                                    <td>
                                        07 Oct, 2019
                                    </td>
                                    <td>
                                        $400
                                    </td>
                                    <td>
                                        <span
                                            class="badge rounded-pill bg-success-subtle text-success font-size-12">Paid</span>
                                    </td>
                                    <td>
                                        <i class="fab fa-cc-mastercard me-1"></i> Mastercard
                                    </td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                            View Details
                                        </button>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="form-check font-size-16">
                                            <input type="checkbox" class="form-check-input" id="customCheck3">
                                            <label class="form-check-label" for="customCheck3">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td><a href="javascript: void(0);" class="text-body fw-bold">#MB2541</a> </td>
                                    <td>Jamal Burnett</td>
                                    <td>
                                        07 Oct, 2019
                                    </td>
                                    <td>
                                        $380
                                    </td>
                                    <td>
                                        <span
                                            class="badge rounded-pill bg-danger-subtle text-danger font-size-12">Chargeback</span>
                                    </td>
                                    <td>
                                        <i class="fab fa-cc-visa me-1"></i> Visa
                                    </td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                            View Details
                                        </button>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <div class="form-check font-size-16">
                                            <input type="checkbox" class="form-check-input" id="customCheck4">
                                            <label class="form-check-label" for="customCheck4">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td><a href="javascript: void(0);" class="text-body fw-bold">#MB2542</a> </td>
                                    <td>Juan Mitchell</td>
                                    <td>
                                        06 Oct, 2019
                                    </td>
                                    <td>
                                        $384
                                    </td>
                                    <td>
                                        <span
                                            class="badge rounded-pill bg-success-subtle text-success font-size-12">Paid</span>
                                    </td>
                                    <td>
                                        <i class="fab fa-cc-paypal me-1"></i> Paypal
                                    </td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check font-size-16">
                                            <input type="checkbox" class="form-check-input" id="customCheck5">
                                            <label class="form-check-label" for="customCheck5">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td><a href="javascript: void(0);" class="text-body fw-bold">#MB2543</a> </td>
                                    <td>Barry Dick</td>
                                    <td>
                                        05 Oct, 2019
                                    </td>
                                    <td>
                                        $412
                                    </td>
                                    <td>
                                        <span
                                            class="badge rounded-pill bg-success-subtle text-success font-size-12">Paid</span>
                                    </td>
                                    <td>
                                        <i class="fab fa-cc-mastercard me-1"></i> Mastercard
                                    </td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check font-size-16">
                                            <input type="checkbox" class="form-check-input" id="customCheck6">
                                            <label class="form-check-label" for="customCheck6">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td><a href="javascript: void(0);" class="text-body fw-bold">#MB2544</a> </td>
                                    <td>Ronald Taylor</td>
                                    <td>
                                        04 Oct, 2019
                                    </td>
                                    <td>
                                        $404
                                    </td>
                                    <td>
                                        <span
                                            class="badge rounded-pill bg-warning-subtle text-warning font-size-12">Refund</span>
                                    </td>
                                    <td>
                                        <i class="fab fa-cc-visa me-1"></i> Visa
                                    </td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="form-check font-size-16">
                                            <input type="checkbox" class="form-check-input" id="customCheck7">
                                            <label class="form-check-label" for="customCheck7">&nbsp;</label>
                                        </div>
                                    </td>
                                    <td><a href="javascript: void(0);" class="text-body fw-bold">#MB2545</a> </td>
                                    <td>Jacob Hunter</td>
                                    <td>
                                        04 Oct, 2019
                                    </td>
                                    <td>
                                        $392
                                    </td>
                                    <td>
                                        <span
                                            class="badge rounded-pill bg-success-subtle text-success font-size-12">Paid</span>
                                    </td>
                                    <td>
                                        <i class="fab fa-cc-paypal me-1"></i> Paypal
                                    </td>
                                    <td>
                                        <button type="button"
                                            class="btn btn-primary btn-sm btn-rounded waves-effect waves-light">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- end table-responsive -->
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
@endsection
@section('script')
    <!-- apexcharts -->
    <script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    <script src="{{ URL::asset('/assets/js/used/dashboard.init.js') }}"></script>
@endsection
