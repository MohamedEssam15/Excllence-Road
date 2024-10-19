/*
Template Name: Minible - Admin & Dashboard Template
Author: Themesbrand
Website: https://themesbrand.com/
Contact: themesbrand@gmail.com
File: Material design Init Js File
*/



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

    modifyModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var courseId = button.getAttribute('data-bs-courseid');
        var courseName = button.getAttribute('data-bs-coursename');
        var teacherCommision = button.getAttribute('data-bs-teachercommision');
        var isPopular = button.getAttribute('data-bs-ispopular');
        if (isPopular == 1) {
            $('#addToPopularCourses').prop('checked', true);
        } else {
            $('#addToPopularCourses').prop('checked', false);
        }

        var modalTitle = modifyModal.querySelector('.modal-title');
        var modalCourseId = document.getElementById('course-id');
        var modalteacherCommission = document.getElementById('teacherCommisionInput');

        modalTitle.textContent = courseName;
        modalCourseId.value = courseId;
        modalteacherCommission.value = teacherCommision;
    });

    // Handle the "Accept" button click
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
});

