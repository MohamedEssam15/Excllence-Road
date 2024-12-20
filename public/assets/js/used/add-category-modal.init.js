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
  var message = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
  var toastContainer = document.getElementById('toastContainer');
  if (currentLang == 'ar') {
    toastContainer.style.marginLeft = '1%';
  } else {
    toastContainer.style.marginRight = '1%';
  }
  var toastLiveExample3 = document.getElementById("toastr");
  if (message != null) {
    var toastBody = toastLiveExample3.querySelector('.toast-body');
    toastBody.innerHTML = message;
  }
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
  var addCategoryModal = document.getElementById('addCategoryModal');
  addCategoryModal.addEventListener('show.bs.modal', function (event) {});

  // Handle the "Accept" button click
  $('#addCategoryButton').click(function () {
    var form = $('#addCategoryForm');
    var formData = form.serialize(); // Serialize form data
    $.ajax({
      type: 'POST',
      url: baseUrl + '/categories/store',
      // Change this to your actual route
      data: formData,
      success: function success(response) {
        // Assuming the server returns a JSON response
        if (response.status == 200) {
          // Close the modal
          $('#addCategoryModal').modal('hide');
          fetchCourses();
          fireToastr(response.message);
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
  var modifyCategoryModal = document.getElementById('modifyCategoryModal');
  modifyCategoryModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var categoryId = button.getAttribute('data-bs-categoryid');
    var arName = button.getAttribute('data-bs-arname');
    var enName = button.getAttribute('data-bs-enname');
    var categoryName = button.getAttribute('data-bs-categoryname');
    var modalTitle = modifyCategoryModal.querySelector('.modal-title');
    var modalCategoryId = document.getElementById('category-id');
    var modalArCategoryNameInput = document.getElementById('arCategoryNameUpdateInput');
    var modalEnCategoryNameInput = document.getElementById('enCategoryNameUpdateInput');
    console.log(categoryId, arName, enName, categoryName);
    modalTitle.textContent = categoryName;
    modalCategoryId.value = categoryId;
    modalArCategoryNameInput.value = arName;
    modalEnCategoryNameInput.value = enName;
  });

  // Handle the "modify" button click
  $('#modifyCategoryButton').click(function () {
    var form = $('#modifyCategoryForm');
    var formData = form.serialize(); // Serialize form data
    $.ajax({
      type: 'POST',
      url: baseUrl + '/categories/update',
      // Change this to your actual route
      data: formData,
      success: function success(response) {
        // Assuming the server returns a JSON response
        if (response.status == 200) {
          // Close the modal
          $('#modifyCategoryModal').modal('hide');
          fetchCourses();
          fireToastr(response.message);
        } else {
          handleErrorResponse(response);
        }
      },
      error: function error(xhr, status, _error2) {
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