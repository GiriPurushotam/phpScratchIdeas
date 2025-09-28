document.addEventListener("DOMContentLoaded", function () {
  const editCategoryModal = new bootstrap.Modal(
    document.getElementById("editCategoryModal")
  );

  // Open modal and fetch category
  document.querySelectorAll(".edit-category-btn").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const id = e.currentTarget.dataset.id;

      fetch(`${BASE_PATH}/categories/${id}`, {
        headers: { Accept: "application/json" },
      })
        .then((res) =>
          res.ok ? res.json() : Promise.reject("Category not found")
        )
        .then((data) => openEditCategoryModal(editCategoryModal, data))
        .catch(console.error);
    });
  });

  // Save category with CSRF
  document
    .querySelector(".save-category-btn")
    .addEventListener("click", (e) => {
      const btn = e.currentTarget;
      const id = btn.dataset.id;
      const modalEl = editCategoryModal._element;

      const payload = {
        name: modalEl.querySelector('input[name="name"]').value,
        csrf_name: modalEl.querySelector('input[name="csrf_name"]').value,
        csrf_value: modalEl.querySelector('input[name="csrf_value"]').value,
      };

      fetch(`${BASE_PATH}/categories/${id}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      })
        .then(async (res) => {
          const data = await res.json().catch(() => null);
          if (!res.ok) throw data || "Save failed";
          console.log("Category updated:", data);
          editCategoryModal.hide();
        })
        .catch(console.error);
    });
});

// Fill modal fields
function openEditCategoryModal(modal, { id, name }) {
  const el = modal._element;
  el.querySelector('input[name="name"]').value = name;
  el.querySelector(".save-category-btn").dataset.id = id;
  modal.show();
}
