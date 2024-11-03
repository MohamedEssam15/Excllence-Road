@extends('layouts.master')
@section('title')
    @lang('translation.Profile')
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            @lang('translation.users')
        @endslot
        @slot('title')
            @lang('translation.profile')
        @endslot
    @endcomponent

    <div class="row mb-4">
        <div class="col-xl-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-center">
                        <div class="clearfix"></div>
                        <div>
                            <img src="{{ $user->getAvatarPath() }}" alt=""
                                class="avatar-lg rounded-circle img-thumbnail">
                        </div>
                        <h5 class="mt-3 mb-1">{{ $user->name }}</h5>
                        <p class="text-muted">{{ ucfirst($user->getRoleNames()->first()) }}</p>

                    </div>

                    <hr class="my-4">

                    <div class="text-muted">
                        {{-- <h5 class="font-size-16">About</h5>
                        <p>Hi I'm Marcus,has been the industry's standard dummy text To an English person, it will seem like
                            simplified English, as a skeptical Cambridge.</p> --}}
                        <div class="table-responsive mt-4">
                            <div>
                                <p class="mb-1">@lang('translation.name') :</p>
                                <h5 class="font-size-16">{{ $user->name }}</h5>
                            </div>
                            <div class="mt-4">
                                <p class="mb-1">@lang('translation.phone') :</p>
                                <h5 class="font-size-16">{{ $user->phone ?? __('translation.notFound') }}</h5>
                            </div>
                            <div class="mt-4">
                                <p class="mb-1">@lang('translation.Email') :</p>
                                <h5 class="font-size-16">{{ $user->email }}</h5>
                            </div>
                            <div class="mt-4">
                                <p class="mb-1">@lang('translation.courseCount') :</p>
                                <h5 class="font-size-16">{{ $user->teacher_courses_count }}</h5>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card mb-0">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#about" role="tab">
                            <i class="uil uil-user-circle font-size-20"></i>
                            <span class="d-none d-sm-block">@lang('translation.certificates')</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#monthlyRevenue" role="tab">
                            <i class="uil uil-user-circle font-size-20"></i>
                            <span class="d-none d-sm-block">@lang('translation.monthlyRevenue')</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#latestRevenue" role="tab">
                            <i class="uil uil-user-circle font-size-20"></i>
                            <span class="d-none d-sm-block">@lang('translation.latestRevenue')</span>
                        </a>
                    </li>
                </ul>

                <!-- Tab content -->
                <div class="tab-content p-4">
                    <div class="tab-pane active" id="about" role="tabpanel">
                        <div>
                            <div>
                                <h5 class="font-size-16 mb-4">@lang('translation.certificates')</h5>

                                <div class="table-responsive">
                                    <table class="table table-nowrap table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">@lang('translation.certificateName')</th>
                                                <th scope="col" style="width: 120px;">@lang('translation.actions')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 01;
                                            @endphp
                                            @if (isset($user->attachments[0]))
                                                @foreach ($user->attachments as $attachment)
                                                    <tr>
                                                        <th scope="row">{{ $i++ }}</th>
                                                        <td>
                                                            <p href="#" class="text-reset">{{ $attachment->name }}</p>
                                                        </td>
                                                        <td>
                                                            <a class="px-2 text-success download-btn"
                                                                title="@lang('translation.download')"
                                                                data-attachment-id="{{ $attachment->id }}"
                                                                data-attachment-name="{{ $user->name . '-' . $attachment->name }}">
                                                                <i class="fas fa-cloud-download-alt"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="10" class="text-center">@lang('translation.noCertificates')</td>
                                                </tr>
                                            @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="monthlyRevenue" role="tabpanel">
                        <div>
                            <div>
                                <h5 class="font-size-16 mb-4">@lang('translation.monthlyRevenue')</h5>

                                <div class="table-responsive">
                                    <table class="table table-nowrap table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">@lang('translation.date')</th>
                                                <th scope="col">@lang('translation.monthRevenue')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $i = 01;
                                            @endphp
                                            @if (isset($monthlyUserRevenue[0]))
                                                @foreach ($monthlyUserRevenue as $revenue)
                                                    <tr>
                                                        <th scope="row">{{ $i++ }}</th>
                                                        <td>
                                                            <p href="#" class="text-reset">
                                                                {{ $revenue->year . '/' . $revenue->month }}
                                                            </p>
                                                        </td>
                                                        <td>
                                                            {{ $revenue->total_revenue }} @lang('translation.currency')
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="10" class="text-center">@lang('translation.noRevenue')</td>
                                                </tr>
                                            @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="latestRevenue" role="tabpanel">
                        <div>
                            <div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <h5 class="font-size-16 mb-4">@lang('translation.latestRevenue')</h5>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-inline float-md-end mb-3">
                                            <a href="{{ route('transactions.teacher.all.revenue', $user->id) }}"
                                                class="btn btn-outline-primary waves-effect waves-light">@lang('translation.allRevenue')</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-nowrap table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">@lang('translation.orderNumber')</th>
                                                <th scope="col">@lang('translation.from')</th>
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
                                                    <td colspan="10" class="text-center">@lang('translation.noCertificates')</td>
                                                </tr>
                                            @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
@endsection

@section('script')
    <script>
        $(document).on('click', '.download-btn', function(e) {
            e.preventDefault();
            var attachmentId = $(this).data('attachment-id');
            var attachmentName = $(this).data('attachment-name');

            $.ajax({
                url: baseUrl + '/users/teachers/download-certificate/' + attachmentId,
                method: 'GET',
                xhrFields: {
                    responseType: 'blob' // This allows handling file downloads
                },
                success: function(data) {
                    var downloadUrl = window.URL.createObjectURL(data);
                    var a = document.createElement('a');
                    a.href = downloadUrl;
                    a.download = attachmentName; // You can dynamically set the filename here
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                },
                error: function(xhr) {
                    console.log('Failed to download file: ' + xhr.responseText);
                }
            });
        });
    </script>
@endsection
