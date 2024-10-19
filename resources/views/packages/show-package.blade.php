@extends('layouts.master')
@section('title')
    @lang('translation.showPackage')
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            @lang('translation.packages')
        @endslot
        @slot('title')
            @lang('translation.showPackage')
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                {{-- <div class="mb-4 text-right">
                    <img src="{{ $package->getCoverPhotoPath() }}" alt="Package Image" class=""
                        style="max-width: 40%; height: auto;">
                </div> --}}
                {{-- <img class="img-fluid" src="{{ $package->getCoverPhotoPath() }}" width="2000" height="400"
                    alt="Card image cap"> --}}
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title">@lang('translation.packages')</h4>
                            <p class="card-title-desc">@lang('translation.showPackage')</p>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end align-items-center">
                            <img src="{{ $package->getCoverPhotoPath() }}" alt="Package Image" class=""
                                style="max-width: 20%; height: auto;">
                        </div>
                    </div>
                    <br>
                    <div class="mt-4">
                        <!-- Display package details -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-arName-input">@lang('translation.arName')</label>
                                    <input type="text" name="arName" class="form-control" id="formrow-arName-input"
                                        value="{{ $package->translate('ar')->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-enName-input">@lang('translation.enName')</label>
                                    <input type="text" name="enName" class="form-control" id="formrow-enName-input"
                                        value="{{ $package->translate('en')->name }}" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Arabic and English Description -->
                        <div class="mb-3">
                            <label for="arDescription" class="form-label">@lang('translation.arDescription')</label>
                            <textarea class="form-control" id="arDescription" name="arDescription" rows="3" readonly>{{ $package->translate('ar')->description }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="enDescription" class="form-label">@lang('translation.enDescription')</label>
                            <textarea class="form-control" id="enDescription" name="enDescription" rows="3" readonly>{{ $package->translate('en')->description }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-price-input">@lang('translation.price')</label>
                                    <input type="text" name="price" class="form-control" id="formrow-price-input"
                                        value="{{ $package->price }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-startDate-input">@lang('translation.startDate')</label>
                                    <input type="text" name="startDate" class="form-control" id="formrow-startDate-input"
                                        value="{{ $package->start_date }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-endDate-input">@lang('translation.endDate')</label>
                                    <input type="text" name="endDate" class="form-control" id="formrow-endDate-input"
                                        value="{{ $package->end_date }}" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Popular Package -->
                        <div class="form-check form-switch form-switch-lg mb-3">
                            <input type="checkbox" class="form-check-input" id="addToPopularPackages"
                                {{ $package->is_popular ? 'checked' : '' }} disabled>
                            <label class="form-check-label" for="customSwitchsizelg">@lang('translation.addToPopularPackages')</label>
                        </div>



                        <!-- Courses Section as in Add/Edit -->
                        <div class="mb-3">
                            <h4 class="card-title">@lang('translation.Courses')</h4>
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    @foreach ($package->courses as $course)
                                        <input type="text" class="form-control" style="direction: ltr"
                                            value="{{ $course->translate(config('app.locale'))->name }}" readonly>
                                    @endforeach
                                </div>
                            </div>
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

        function fireToastr() {
            const toastContainer = document.getElementById('toastContainer');
            if (currentLang == 'ar') {
                toastContainer.style.marginLeft = '1%';
            } else {
                toastContainer.style.marginRight = '1%';
            }
            const toastLiveExample3 = document.getElementById("toastr");
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
    <!-- Varying Modal Content js -->
    <script src="{{ URL::asset('assets/js/pages/bootstrap-toasts.init.js') }}"></script>
@endsection
