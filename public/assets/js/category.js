import { get, post, del } from "./ajax";

document.addEventListener("DOMContentLoaded", function () {
  const editCategoryModal = new bootstrap.Modal(
    document.getElementById("editCategoryModal")
  );

  // Open modal and fetch category
  document.querySelectorAll(".edit-category-btn").forEach((button) => {
    button.addEventListener("click", function (event) {
      const categoryId = event.currentTarget.getAttribute("data-id");

      get(`${BASE_PATH}/categories/${categoryId}`)
        .then((response) => response.json())
        .then((response) => openEditCategoryModal(editCategoryModal, response));
    });
  });

  document
    .querySelector(".save-category-btn")
    .addEventListener("click", function (event) {
      const categoryId = event.currentTarget.getAttribute("data-id");

      post(
        `${BASE_PATH}/categories/${categoryId}`,
        {
          name: editCategoryModal._element.querySelector('input[name="name"]')
            .value,
        },
        editCategoryModal._element
      ).then((response) => {
        if (response.ok) {
          editCategoryModal.hide();
        }
      });
    });

  document
    .querySelector(".delete-category-btn")
    .addEventListener("click", function (event) {
      const categoryId = event.currentTarget.getAttribute("data-id");

      if (confirm("Are you sure you want to delete this category?")) {
        del(`${BASE_PATH}/categories/${categoryId}`);
      }
    });
});

function openEditCategoryModal(modal, { id, name }) {
  const nameInput = modal._element.querySelector('input[name="name"]');

  nameInput.value = name;

  modal._element
    .querySelector(".save-category-btn")
    .setAttribute("data-id", id);

  modal.show();
}
