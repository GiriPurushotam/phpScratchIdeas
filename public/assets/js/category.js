document.addEventListener("DOMContentLoaded", function () {
  const editCategoryModal = new bootstrap.Modal(
    document.getElementById("editCategoryModal")
  );

  document.querySelectorAll(".edit-category-btn").forEach((button) => {
    button.addEventListener("click", function (event) {
      const categoryId = event.currentTarget.getAttribute("data-id");

      // 🔥 AJAX call to fetch category data
      fetch(`${BASE_PATH}/categories/${categoryId}`, {
        method: "GET",
        headers: {
          Accept: "application/json",
        },
      })
        .then((response) => {
          if (!response.ok) {
            throw new Error("Category not found");
          }
          return response.json();
        })
        .then((data) => {
          openEditCategoryModal(editCategoryModal, {
            id: data.id,
            name: data.name,
          });
        })
        .catch((error) => {
          console.error("Error fetching category:", error);
        });
    });
  });

  document
    .querySelector(".save-category-btn")
    .addEventListener("click", function (event) {
      const categoryId = event.currentTarget.getAttribute("data-id");
      const name = document.querySelector('input[name="name"]').value;

      // TODO: Post update to the category
      console.log("Saving category:", categoryId, name);
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
