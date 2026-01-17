import { get, post, del, ajax } from "./ajax";

document.addEventListener("DOMContentLoaded", function () {
  const editCategoryModal = new bootstrap.Modal(
    document.getElementById("editCategoryModal"),
  );

  $(document).ready(function () {
    const $table = $("#categoriesTable table").DataTable({
      serverSide: true,
      ajax: `${BASE_PATH}/categories/load`,
      columns: [
        { data: "name" },
        { data: "createdAt" },
        { data: "updatedAt" },
        {
          data: "id",
          sortable: false,
          render: function (id) {
            return `
            <div class="d-flex"> 
            <button class="ms-2 btn btn-primary delete-category-btn" data-id="${id}">
            <i class="bi bi-trash3-fill"></i>
            </button> 
            <button class="ms-2 btn btn-outline-primary edit-category-btn" data-id="${id}">
            <i class="bi bi-pencil-fill"></i>
            </button>
            
            </div>
            `;
          },
        },
      ],

      pagelength: 10,
      responsive: true,
      drawCallback: function () {
        bindCategoryButton();
      },
    });
  });
  // function to bind edit/delete buttons
  function bindCategoryButton() {
    const editCategoryModal = new bootstrap.Modal(
      document.getElementById("editCategoryModal"),
    );

    $(document).on("click", ".delete-category-btn", function () {
      const categoryId = $(this).data("id");

      get(`${BASE_PATH}/categories/${categoryId}`)
        .then((res) => res.json())
        .then((res) => openEditCategoryModal(editCategoryModal, res));
    });

    $(document).on("click", ".edit-category-btn", function () {
      const categoryId = $(this).data("id");
      if (confirm("Are you sure you want to delete this category?")) {
        del(`${BASE_PATH}/categories/${categoryId}`).then(() => {
          $("#categoryTable").DataTable().ajax.reload();
        });
      }
    });
  }

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
        editCategoryModal._element,
      ).then((response) => {
        if (response.ok) {
          editCategoryModal.hide();
        }
      });
    });

  document.querySelectorAll(".delete-category-btn").forEach((button) => {
    button.addEventListener("click", function (event) {
      const categoryId = event.currentTarget.getAttribute("data-id");

      if (confirm("Are you sure you want to delete this category?")) {
        del(`${BASE_PATH}/categories/${categoryId}`);
      }
    });
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
