document.addEventListener("DOMContentLoaded", function () {
  const editCategoryModal = new bootstrap.Modal(
    document.getElementById("editCategoryModal")
  );

  // Open modal and fetch category
  document.querySelectorAll(".edit-category-btn").forEach((button) => {
    button.addEventListener("click", function (event) {
      const categoryId = event.currentTarget.getAttribute("data-id");

      fetch(`${BASE_PATH}/categories/${categoryId}`)
        .then((response) => response.json())
        .then((response) => openEditCategoryModal(editCategoryModal, response));
    });
  });

  // Save category with CSRF
  document
    .querySelector(".save-category-btn")
    .addEventListener("click", function (event) {
      const categoryId = event.currentTarget.getAttribute("data-id");
      const csrfName = editCategoryModal._element.querySelector(
        'input[name="csrf_name"]'
      ).value;
      const csrfValue = editCategoryModal._element.querySelector(
        'input[name="csrf_value"]'
      ).value;

      fetch(`${BASE_PATH}/categories/${categoryId}`, {
        method: "POST",
        body: JSON.stringify({
          name: editCategoryModal._element.querySelector('input[name="name"]')
            .value,
          csrf_name: csrfName,
          csrf_value: csrfValue,
        }),
        headers: {
          "Content-Type": "application/json",
        },
      })
        .then((response) => response.json())
        .then((data) => {
          console.log("Server Response Data:", data);
        });
    });
});

// Fill modal fields
function openEditCategoryModal(modal, { id, name }) {
  const el = modal._element;
  el.querySelector('input[name="name"]').value = name;
  el.querySelector(".save-category-btn").dataset.id = id;
  modal.show();
}
