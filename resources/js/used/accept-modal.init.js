/*
Template Name: Minible - Admin & Dashboard Template
Author: Themesbrand
Website: https://themesbrand.com/
Contact: themesbrand@gmail.com
File: Material design Init Js File
*/



// placement toast
const currentLang = document.documentElement.lang || 'ar';
function fireToastr() {
    const toastContainer = document.getElementById('acceptToastContainer');
    if (currentLang == 'ar') {
        toastContainer.style.marginLeft = '1%';
    } else {
        toastContainer.style.marginRight = '1%';
    }
    const toastLiveExample3 = document.getElementById("acceptToastr");
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
    const toastContainer = document.getElementById('acceptToastContainerError');
    if (currentLang == 'ar') {
        toastContainer.style.marginLeft = '1%';
    } else {
        toastContainer.style.marginRight = '1%';
    }
    const toastLiveExample3 = document.getElementById("acceptToastrError");
    const toastBody = toastLiveExample3.querySelector('.toast-body');
    toastBody.innerHTML = message;
    var toast = new bootstrap.Toast(toastLiveExample3, {
        delay: 3000
    });
    toast.show();
}
$(document).ready(function () {
    var acceptModal = document.getElementById('acceptModal');

    acceptModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var courseId = button.getAttribute('data-bs-courseid');
        var courseName = button.getAttribute('data-bs-coursename');

        var modalTitle = acceptModal.querySelector('.modal-title');
        var modalCourseId = document.getElementById('course-id');

        modalTitle.textContent = courseName;
        modalCourseId.value = courseId;
    });

    // Handle the "Accept" button click
    $('#acceptCourseButton').click(function () {
        var form = $('#acceptCourseForm');
        var formData = form.serialize(); // Serialize form data

        $.ajax({
            type: 'POST',
            url: baseUrl + '/courses/accept-course',  // Change this to your actual route
            data: formData,
            success: function (response) {
                console.log(response.status)
                // Assuming the server returns a JSON response
                if (response.status == 200) {
                    // Close the modal
                    $('#acceptModal').modal('hide');
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

