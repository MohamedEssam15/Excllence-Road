/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!******************************************************!*\
  !*** ./resources/js/used/add-category-modal.init.js ***!
  \******************************************************/
/*
Template Name: Minible - Admin & Dashboard Template
Author: Themesbrand
Website: https://themesbrand.com/
Contact: themesbrand@gmail.com
File: Material design Init Js File
*/

var currentLang = document.documentElement.lang || 'ar';
function fireToastr() {
  var toastContainer = document.getElementById('toastContainer');
  if (currentLang == 'ar') {
    toastContainer.style.marginLeft = '1%';
  } else {
    toastContainer.style.marginRight = '1%';
  }
  var toastLiveExample3 = document.getElementById("toastr");
  var toast = new bootstrap.Toast(toastLiveExample3, {
    delay: 3000
  });
  toast.show();
}
function handleErrorResponse(response) {
  var errors = response.errors || [];
  var errorMessage = errors.length > 0 ? errors.join('<br>') // Join error messages with a line break for display
  : response.message || 'An unexpected error occurred'; // Fallback message

  fireErrorToastr(errorMessage);
}
function fireErrorToastr(message) {
  var toastContainer = document.getElementById('toastContainerError');
  if (currentLang == 'ar') {
    toastContainer.style.marginLeft = '1%';
  } else {
    toastContainer.style.marginRight = '1%';
  }
  var toastLiveExample3 = document.getElementById("toastrError");
  var toastBody = toastLiveExample3.querySelector('.toast-body');
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

  // Handle the "Accept" button click
  $('#modifyCourseButton').click(function () {
    var form = $('#modifyCourseForm');
    var formData = form.serialize(); // Serialize form data
    $.ajax({
      type: 'POST',
      url: baseUrl + '/courses/modify-course',
      // Change this to your actual route
      data: formData,
      success: function success(response) {
        // Assuming the server returns a JSON response
        if (response.status == 200) {
          // Close the modal
          $('#modifyModal').modal('hide');
          fetchCourses();
          fireToastr();
        } else {
          handleErrorResponse(response);
        }
      },
      error: function error(xhr, status, _error) {
        var response = xhr.responseJSON || {
          message: 'An unexpected error occurred.'
        };
        handleErrorResponse(response);
      }
    });
  });
});
/******/ })()
;