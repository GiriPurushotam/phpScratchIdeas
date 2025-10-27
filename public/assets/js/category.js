import { get, post } from "./ajax";

document.addEventListener("DOMContentLoaded", function () {
  const editCategoryModal = new bootstrap.Modal(
    document.getElementById("editCategoryModal")
  );

  // Open modal and fetch category
  document.querySelectorAll(".edit-category-btn").forEach((button) => {
    button.addEventListener("click", function (event) {
      const categoryId = event.currentTarget.getAttribute("data-id");

      get(`${BASE_PATH}/categories/${categoryId}`).then((response) =>
        openEditCategoryModal(editCategoryModal, response)
      );
    });
  });

  document
    .querySelector(".save-category-btn")
    .addEventListener("click", function (event) {
      const categoryId = event.currentTarget.getAttribute("data-id");

      post(`${BASE_PATH}/categories/${categoryId}`, {
        name: editCategoryModal._element.querySelector('input[name="name"]')
          .value,
      }).then((response) => {
        console.log(response);
      });

      // fetch(`/categories/${categoryId}`, {
      //   method: "POST",
      //   body: JSON.stringify({
      //     name: editCategoryModal._element.querySelector('input[name="name"]')
      //       .value,
      //     ...getCsrfFields(),
      //   }),
      //   headers: {
      //     "Content-Type": "application/json",
      //     "X-Request-With": "XMLHttpRequest",
      //   },
      // })
      //   .then((response) => response.json())
      //   .then((response) => {
      //     console.log(response);
      //   });
    });
});

// function getCsrfFields() {
//   const csrfNameField = document.querySelector("#csrfName");
//   const csrfValueField = document.querySelector("#csrfValue");
//   const csrfNameKey = csrfNameField.getAttribute("name");
//   const csrfName = csrfNameField.content;
//   const csrfValueKey = csrfValueField.getAttribute("name");
//   const csrfValue = csrfValueField.content;

//   return {
//     [csrfNameKey]: csrfName,
//     [csrfValueKey]: csrfValue,
//   };
// }

function openEditCategoryModal(modal, { id, name }) {
  const nameInput = modal._element.querySelector('input[name="name"]');

  nameInput.value = name;

  modal._element
    .querySelector(".save-category-btn")
    .setAttribute("data-id", id);

  modal.show();
}
