@extends('layouts.master')
@section('title')
    @lang('translation.addAdmin')
@endsection
@section('css')
    <!-- plugin css -->
    <link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            @lang('translation.addAdmin')
        @endslot
        @slot('title')
            @lang('translation.addAdmin')
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title">@lang('translation.admins')</h4>
                            @role('admin')
                                <p class="card-title-desc">@lang('translation.addAdmin')</p>
                            @endrole
                        </div>
                        <div class="col-md-6 d-flex justify-content-end align-items-center">
                            <!-- Button aligned to the top right -->
                            {{-- <a href="{{route('courses.add')}}" class="btn btn-outline-primary waves-effect waves-light">@lang('translation.addCourses')</a> --}}
                        </div>
                    </div>
                    <div class="mt-4">
                        <form action="{{ route('users.admin.store') }}" class="outer-repeater" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="formrow-name-input">@lang('translation.name')</label>
                                        <input type="text" name="name" class="form-control" id="formrow-name-input">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="formrow-email-input">@lang('translation.email')</label>
                                        <input type="email" name="email" class="form-control" id="formrow-email-input">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="formFileLg" class="form-label">@lang('translation.profileImage')</label>
                                <input class="form-control form-control-lg" id="formFileLg" name="profileImage"
                                    type="file">
                            </div>
                            <hr style="border: 1x solid #000; opacity: 1; margin: 20px 0;">
                            <h6 class="card-title">@lang('translation.permissions')</h6>

                            <div class="row"> <!-- courses permissions section -->
                                <div class="form-check form-switch form-switch-lg mb-2">
                                    <input type="checkbox" class="form-check-input" id="categorySwitch-courses"
                                        onchange="togglePermissionsDiv('courses', this)">
                                    <label class="form-check-label card-title"
                                        for="categorySwitch-courses">@lang('translation.Courses')</label>
                                </div>
                                <div id="permissionsDiv-courses" style="display: none;">
                                    <div class="row mb-1">
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="active-courses"
                                                    value="active-courses" onchange="updatePermissions(this, 'courses')">
                                                <label class="form-check-label"
                                                    for="active-courses">@lang('translation.activeCourses')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="edit-course"
                                                    value="edit-course" onchange="updatePermissions(this, 'courses')">
                                                <label class="form-check-label" for="edit-course">@lang('translation.editCourse')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="discount"
                                                    value="discount" onchange="updatePermissions(this, 'courses')">
                                                <label class="form-check-label" for="discount">@lang('translation.discount')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="pending-courses"
                                                    value="pending-courses" onchange="updatePermissions(this, 'courses')">
                                                <label class="form-check-label"
                                                    for="pending-courses">@lang('translation.pendingCourses')</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row  mb-1">
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="accept-reject-courses"
                                                    value="accept-reject-courses"
                                                    onchange="updatePermissions(this, 'courses')">
                                                <label class="form-check-label"
                                                    for="accept-reject-courses">@lang('translation.AcceptReject')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="expired-courses"
                                                    value="expired-courses" onchange="updatePermissions(this, 'courses')">
                                                <label class="form-check-label"
                                                    for="expired-courses">@lang('translation.expiredCourses')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="rejected-courses"
                                                    value="rejected-courses"
                                                    onchange="updatePermissions(this, 'courses')">
                                                <label class="form-check-label"
                                                    for="rejected-courses">@lang('translation.cancelledCourses')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="return-course"
                                                    value="return-course" onchange="updatePermissions(this, 'courses')">
                                                <label class="form-check-label"
                                                    for="return-course">@lang('translation.returnBackCourse')</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row  mb-1">
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="delete-course"
                                                    value="delete-course" onchange="updatePermissions(this, 'courses')">
                                                <label class="form-check-label"
                                                    for="delete-course">@lang('translation.deleteCourse')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="course-info"
                                                    value="course-info" onchange="updatePermissions(this, 'courses')">
                                                <label class="form-check-label"
                                                    for="course-info">@lang('translation.courseInfo')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr style="border: 1x solid #000; opacity: 1; margin: 20px 0;">
                            <div class="row"> <!-- packages permissions section -->
                                <div class="form-check form-switch form-switch-lg mb-2">
                                    <input type="checkbox" class="form-check-input" id="categorySwitch-packages"
                                        onchange="togglePermissionsDiv('packages', this)">
                                    <label class="form-check-label card-title"
                                        for="categorySwitch-packages">@lang('translation.packages')</label>
                                </div>
                                <div id="permissionsDiv-packages" style="display: none;">
                                    <div class="row mb-1">
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="active-packages"
                                                    value="active-packages"
                                                    onchange="updatePermissions(this, 'packages')">
                                                <label class="form-check-label"
                                                    for="active-packages">@lang('translation.activePackages')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="edit-package"
                                                    value="edit-package" onchange="updatePermissions(this, 'packages')">
                                                <label class="form-check-label"
                                                    for="edit-package">@lang('translation.editPackage')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="show-package"
                                                    value="show-package" onchange="updatePermissions(this, 'packages')">
                                                <label class="form-check-label"
                                                    for="show-package">@lang('translation.showPackage')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="expired-packages"
                                                    value="expired-packages"
                                                    onchange="updatePermissions(this, 'packages')">
                                                <label class="form-check-label"
                                                    for="expired-packages">@lang('translation.expiredPackages')</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row  mb-1">
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="reactive-package"
                                                    value="reactive-package"
                                                    onchange="updatePermissions(this, 'packages')">
                                                <label class="form-check-label"
                                                    for="reactive-package">@lang('translation.reactivePackage')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="add-package"
                                                    value="add-package" onchange="updatePermissions(this, 'packages')">
                                                <label class="form-check-label"
                                                    for="add-package">@lang('translation.addPackages')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr style="border: 1x solid #000; opacity: 1; margin: 20px 0;">
                            <div class="row"> <!-- categories permissions section -->
                                <div class="form-check form-switch form-switch-lg mb-2">
                                    <input type="checkbox" class="form-check-input" id="categorySwitch-categories"
                                        onchange="togglePermissionsDiv('categories', this)">
                                    <label class="form-check-label card-title"
                                        for="categorySwitch-categories">@lang('translation.categories')</label>
                                </div>
                                <div id="permissionsDiv-categories" style="display: none;">
                                    <div class="row mb-1">
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="add-category"
                                                    value="add-category" onchange="updatePermissions(this, 'categories')">
                                                <label class="form-check-label"
                                                    for="add-category">@lang('translation.addCategory')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="edit-category"
                                                    value="edit-category"
                                                    onchange="updatePermissions(this, 'categories')">
                                                <label class="form-check-label"
                                                    for="edit-category">@lang('translation.editCategory')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr style="border: 1x solid #000; opacity: 1; margin: 20px 0;">
                            <div class="row"> <!-- Feature Content permissions section -->
                                <div class="form-check form-switch form-switch-lg mb-2">
                                    <input type="checkbox" class="form-check-input" id="categorySwitch-feature-content"
                                        onchange="togglePermissionsDiv('feature-content', this)">
                                    <label class="form-check-label card-title"
                                        for="categorySwitch-feature-content">@lang('translation.featureContent')</label>
                                </div>
                                <div id="permissionsDiv-feature-content" style="display: none;">
                                    <div class="row mb-1">
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="add-feature-content"
                                                    value="add-feature-content"
                                                    onchange="updatePermissions(this, 'feature-content')">
                                                <label class="form-check-label"
                                                    for="add-feature-content">@lang('translation.addFeatureContent')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input"
                                                    id="delete-feature-content" value="delete-feature-content"
                                                    onchange="updatePermissions(this, 'feature-content')">
                                                <label class="form-check-label"
                                                    for="delete-feature-content">@lang('translation.deleteFeatureContent')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr style="border: 1x solid #000; opacity: 1; margin: 20px 0;">
                            <div class="row"> <!-- users permissions section -->
                                <div class="form-check form-switch form-switch-lg mb-2">
                                    <input type="checkbox" class="form-check-input" id="categorySwitch-users"
                                        onchange="togglePermissionsDiv('users', this)">
                                    <label class="form-check-label card-title"
                                        for="categorySwitch-users">@lang('translation.users')</label>
                                </div>
                                <div id="permissionsDiv-users" style="display: none;">
                                    <div class="row mb-1">
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="teachers"
                                                    value="teachers" onchange="updatePermissions(this, 'users')">
                                                <label class="form-check-label" for="teachers">@lang('translation.teachers')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="active-teachers"
                                                    value="active-teachers" onchange="updatePermissions(this, 'users')">
                                                <label class="form-check-label"
                                                    for="active-teachers">@lang('translation.activeTeachers')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="pending-teachers"
                                                    value="pending-teachers" onchange="updatePermissions(this, 'users')">
                                                <label class="form-check-label"
                                                    for="pending-teachers">@lang('translation.pendingTeachers')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input"
                                                    id="accept-reject-teacher" value="accept-reject-teacher"
                                                    onchange="updatePermissions(this, 'users')">
                                                <label class="form-check-label"
                                                    for="accept-reject-teacher">@lang('translation.acceptRejectTeacher')</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row  mb-1">
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="blocked-teachers"
                                                    value="blocked-teachers" onchange="updatePermissions(this, 'users')">
                                                <label class="form-check-label"
                                                    for="blocked-teachers">@lang('translation.blockedTeachers')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input"
                                                    id="block-unblock-teacher" value="block-unblock-teacher"
                                                    onchange="updatePermissions(this, 'users')">
                                                <label class="form-check-label"
                                                    for="block-unblock-teacher">@lang('translation.blockUnblockTeacher')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="show-teacher"
                                                    value="show-teacher" onchange="updatePermissions(this, 'users')">
                                                <label class="form-check-label"
                                                    for="show-teacher">@lang('translation.showTeacher')</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-1 mt-3">
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="students"
                                                    value="students" onchange="updatePermissions(this, 'users')">
                                                <label class="form-check-label" for="students">@lang('translation.students')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="active-students"
                                                    value="active-students" onchange="updatePermissions(this, 'users')">
                                                <label class="form-check-label"
                                                    for="active-students">@lang('translation.activeStudents')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="blocked-students"
                                                    value="blocked-students" onchange="updatePermissions(this, 'users')">
                                                <label class="form-check-label"
                                                    for="blocked-students">@lang('translation.blockedStudents')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input"
                                                    id="block-unblock-student" value="block-unblock-student"
                                                    onchange="updatePermissions(this, 'users')">
                                                <label class="form-check-label"
                                                    for="block-unblock-student">@lang('translation.blockUnblockStudent')</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row  mb-1">
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input"
                                                    id="add-course-to-student" value="add-course-to-student"
                                                    onchange="updatePermissions(this, 'users')">
                                                <label class="form-check-label"
                                                    for="add-course-to-student">@lang('translation.addFreeCourseOrPackage')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr style="border: 1x solid #000; opacity: 1; margin: 20px 0;">
                            <div class="row"> <!-- transaction permissions section -->
                                <div class="form-check form-switch form-switch-lg mb-2">
                                    <input type="checkbox" class="form-check-input" id="categorySwitch-transactions"
                                        onchange="togglePermissionsDiv('transactions', this)">
                                    <label class="form-check-label card-title"
                                        for="categorySwitch-transactions">@lang('translation.transaction')</label>
                                </div>
                                <div id="permissionsDiv-transactions" style="display: none;">
                                    <div class="row mb-1">
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="orders"
                                                    value="orders" onchange="updatePermissions(this, 'transactions')">
                                                <label class="form-check-label" for="orders">@lang('translation.orders')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="teacher-revenue"
                                                    value="teacher-revenue"
                                                    onchange="updatePermissions(this, 'transactions')">
                                                <label class="form-check-label"
                                                    for="teacher-revenue">@lang('translation.teachersTransactions')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="top-seller"
                                                    value="top-seller" onchange="updatePermissions(this, 'transactions')">
                                                <label class="form-check-label"
                                                    for="top-seller">@lang('translation.topSeller')</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="contact-us"
                                                    value="contact-us" onchange="updatePermissions(this, 'transactions')">
                                                <label class="form-check-label"
                                                    for="contact-us">@lang('translation.contactUsMessages')</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="permissions" id="permissionsArray">
                            <div class="d-flex flex-wrap gap-3 mt-3">
                                <button type="submit"
                                    class="btn btn-primary waves-effect waves-light w-md">@lang('translation.submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>





            {{-- toastr --}}
            <div id="toastContainer" class="position-fixed top-0 end-0 " style="z-index: 1060;margin-top: 5%;">
                <div id="toastr" class="toast overflow-hidden" role="alert" aria-live="assertive"
                    aria-atomic="true">
                    <div class="align-items-center text-white
                            bg-success border-0">
                        <div class="d-flex">
                            <div class="toast-body">
                                @if (session('status'))
                                    {{ session('status') }}.
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
        let categoriesPermissions = {}; // Object to store permissions for each category

        function togglePermissionsDiv(category, switchElement) {
            const permissionsDiv = document.getElementById(`permissionsDiv-${category}`);
            if (switchElement.checked) {
                permissionsDiv.style.display = 'block';
                categoriesPermissions[category] = [];
                categoriesPermissions[category].push(category);
            } else {
                permissionsDiv.style.display = 'none';
                // Clear permissions for the category if the switch is off
                categoriesPermissions[category] = [];
                clearCategoryPermissions(category);
                document
                    .querySelectorAll(`#permissionsDiv-${category} input[type="checkbox"]`)
                    .forEach((checkbox) => (checkbox.checked = false));
                updatePermissionsArray();
            }
        }

        function clearCategoryPermissions(category) {
            delete categoriesPermissions[category]; // Remove the category from the object
            updatePermissionsArray();
        }

        function updatePermissions(checkbox, category) {
            if (!categoriesPermissions[category]) {
                categoriesPermissions[category] = [];
            }
            if (checkbox.checked) {
                categoriesPermissions[category].push(checkbox.value);
            } else {
                categoriesPermissions[category] = categoriesPermissions[category].filter(
                    (permission) => permission !== checkbox.value
                );
            }
            updatePermissionsArray();
        }

        function updatePermissionsArray() {

            const mergedPermissions = Object.values(categoriesPermissions).flat();
            document.getElementById('permissionsArray').value = JSON.stringify(mergedPermissions);
        }
    </script>
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
    <script src="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jquery-repeater/jquery-repeater.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/form-repeater.int.js') }}"></script>
@endsection
