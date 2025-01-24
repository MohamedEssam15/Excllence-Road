/*
Template Name: Minible - Admin & Dashboard Template
Author: Themesbrand
Website: https://themesbrand.com/
Contact: themesbrand@gmail.com
File: Material design Init Js File
*/



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
function handleErrorResponse(response) {
    const errors = response.errors || [];
    const errorMessage = errors.length > 0
        ? errors.join('<br>') // Join error messages with a line break for display
        : response.message || 'An unexpected error occurred'; // Fallback message

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
        delay: 3000
    });
    toast.show();
}
$(document).ready(function () {
    var modifyModal = document.getElementById('modifyModal');
    var addDiscountModal = document.getElementById('addDiscountModal');
    var removeDiscountModal = document.getElementById('removeDiscountModal');
    var deleteCourseModal = document.getElementById('deleteCourseModal');

    modifyModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var courseId = button.getAttribute('data-bs-courseid');
        var courseName = button.getAttribute('data-bs-coursename');
        var teacherCommision = button.getAttribute('data-bs-teachercommision');
        var isPopular = button.getAttribute('data-bs-ispopular');
        var isMobileOnly = button.getAttribute('data-bs-ismobileonly');
        if (isPopular == 1) {
            $('#addToPopularCourses').prop('checked', true);
        } else {
            $('#addToPopularCourses').prop('checked', false);
        }
        if (isMobileOnly == 1) {
            $('#isMobileOnly').prop('checked', true);
        } else {
            $('#isMobileOnly').prop('checked', false);
        }

        var modalTitle = modifyModal.querySelector('.modal-title');
        var modalCourseId = document.getElementById('course-id');
        var modalteacherCommission = document.getElementById('teacherCommisionInput');

        modalTitle.textContent = courseName;
        modalCourseId.value = courseId;
        modalteacherCommission.value = teacherCommision;
    });

    $('#modifyCourseButton').click(function () {
        var form = $('#modifyCourseForm');
        var formData = form.serialize(); // Serialize form data
        $.ajax({
            type: 'POST',
            url: baseUrl + '/courses/modify-course',  // Change this to your actual route
            data: formData,
            success: function (response) {
                // Assuming the server returns a JSON response
                if (response.status == 200) {
                    // Close the modal
                    $('#modifyModal').modal('hide');
                    fetchCourses();
                    fireToastr()
                } else {
                    handleErrorResponse(response)
                }
            },
            error: function (xhr, status, error) {
                const response = xhr.responseJSON || { message: 'An unexpected error occurred.' };
                handleErrorResponse(response)
            }
        });
    });

    addDiscountModal.addEventListener('show.bs.modal', function (event) {
        document.getElementById('addDiscountForm').reset();
        var button = event.relatedTarget;
        var courseId = button.getAttribute('data-bs-courseid');
        var courseName = button.getAttribute('data-bs-coursename');

        var modalTitle = addDiscountModal.querySelector('.modal-title');
        var modalCourseId = document.getElementById('add-discount-course-id');

        modalTitle.textContent = courseName;
        modalCourseId.value = courseId;
    });

    $('#addDiscountButton').click(function (event) {
        event.preventDefault();
        var form = $('#addDiscountForm');
        var formData = form.serialize(); // Serialize form data

        $.ajax({
            type: 'POST',
            url: baseUrl + '/courses/add-discount',  // Change this to your actual route
            data: formData,
            success: function (response) {
                // Assuming the server returns a JSON response
                if (response.status == 200) {
                    // Close the modal
                    $('#addDiscountModal').modal('hide');
                    fetchCourses();
                    fireToastr(response.message)
                } else {
                    handleErrorResponse(response)
                }
            },
            error: function (xhr, status, error) {
                const response = xhr.responseJSON || { message: 'An unexpected error occurred.' };
                handleErrorResponse(response)
            }
        });
    });
    $('#addDiscountForm').submit(function (event) {
        event.preventDefault();
        var formData = $(this).serialize(); // Serialize form data
        $.ajax({
            type: 'POST',
            url: baseUrl + '/courses/add-discount',  // Change this to your actual route
            data: formData,
            success: function (response) {
                // Assuming the server returns a JSON response
                if (response.status == 200) {
                    // Close the modal
                    $('#addDiscountModal').modal('hide');
                    fetchCourses();
                    fireToastr(response.message)
                } else {
                    handleErrorResponse(response)
                }
            },
            error: function (xhr, status, error) {
                const response = xhr.responseJSON || { message: 'An unexpected error occurred.' };
                handleErrorResponse(response)
            }
        });
    });
    removeDiscountModal.addEventListener('show.bs.modal', function (event) {
        document.getElementById('removeDiscountForm').reset();
        var button = event.relatedTarget;
        var courseId = button.getAttribute('data-bs-courseid');
        var courseName = button.getAttribute('data-bs-coursename');
        var modalTitle = removeDiscountModal.querySelector('.modal-title');
        var modalCourseId = document.getElementById('remove-discount-course-id');

        modalTitle.textContent = courseName;
        modalCourseId.value = courseId;
    });
    $('#removeDiscountButton').click(function (event) {
        event.preventDefault();
        var form = $('#removeDiscountForm');
        var formData = form.serialize(); // Serialize form data

        $.ajax({
            type: 'POST',
            url: baseUrl + '/courses/remove-discount',  // Change this to your actual route
            data: formData,
            success: function (response) {
                if (response.status == 200) {
                    // Close the modal
                    $('#removeDiscountModal').modal('hide');
                    fetchCourses();
                    fireToastr(response.message)
                } else {
                    $('#removeDiscountModal').modal('hide');
                    handleErrorResponse(response)
                }
            },
            error: function (xhr, status, error) {
                const response = xhr.responseJSON || { message: 'An unexpected error occurred.' };
                $('#removeDiscountModal').modal('hide');
                handleErrorResponse(response)
            }
        });
    });

    deleteCourseModal.addEventListener('show.bs.modal', function (event) {
        document.getElementById('removeDiscountForm').reset();
        var button = event.relatedTarget;
        var courseId = button.getAttribute('data-bs-courseid');
        var courseName = button.getAttribute('data-bs-coursename');
        var modalTitle = deleteCourseModal.querySelector('.modal-title');
        var modalCourseId = document.getElementById('delete-course-id');

        modalTitle.textContent = courseName;
        modalCourseId.value = courseId;
    });
    $('#deleteCourseButton').click(function (event) {
        event.preventDefault();
        var form = $('#deleteCourseForm');
        var formData = form.serialize(); // Serialize form data

        $.ajax({
            type: 'POST',
            url: baseUrl + '/courses/delete-course',  // Change this to your actual route
            data: formData,
            success: function (response) {
                if (response.status == 200) {
                    // Close the modal
                    $('#deleteCourseModal').modal('hide');
                    fetchCourses();
                    fireToastr(response.message)
                } else {
                    $('#deleteCourseModal').modal('hide');
                    handleErrorResponse(response)
                }
            },
            error: function (xhr, status, error) {
                const response = xhr.responseJSON || { message: 'An unexpected error occurred.' };
                $('#deleteCourseModal').modal('hide');
                handleErrorResponse(response)
            }
        });
    });
});

