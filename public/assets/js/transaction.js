import { get, post, del } from "./ajax";

let editTransactionModal;
let transactionTable;

document.addEventListener("DOMContentLoaded", function () {
  // Initialize modal
  editTransactionModal = new bootstrap.Modal(
    document.getElementById("editTransactionModal"),
  );

  // Initialize DataTable
  $(function () {
    transactionTable = $("#transactionsTable").DataTable({
      processing: true,
      serverSide: true,
      ajax: `${BASE_PATH}/transactions/load`,
      columns: [
        { data: "description" },
        {
          data: "amount",
          render: (data) => "$" + parseFloat(data).toFixed(2),
        },
        { data: "date" },
        { data: "category" },
        { data: "created_at" },
        { data: "updated_at" },
        {
          data: "id",
          sortable: false,
          render: (id) => `
            <div class="d-flex">
              <button class="ms-2 btn btn-primary delete-transaction-btn" data-id="${id}">
                <i class="bi bi-trash3-fill"></i>
              </button>
              <button class="ms-2 btn btn-outline-primary edit-transaction-btn" data-id="${id}">
                <i class="bi bi-pencil-fill"></i>
              </button>
            </div>
          `,
        },
      ],
      pageLength: 10,
      drawCallback: bindTransactionButtons, // Attach buttons after each redraw
    });
  });

  // Bind buttons inside DataTable
  function bindTransactionButtons() {
    // Delete
    $(".delete-transaction-btn")
      .off("click")
      .on("click", function () {
        const transactionId = $(this).data("id");
        if (confirm("Are you sure you want to delete this transaction?")) {
          del(`${BASE_PATH}/transactions/${transactionId}`).then(() => {
            transactionTable.ajax.reload(null, false);
          });
        }
      });

    // Edit (fetch transaction data and open modal)
    $(".edit-transaction-btn")
      .off("click")
      .on("click", function () {
        const transactionId = $(this).data("id");

        get(`${BASE_PATH}/transactions/${transactionId}`)
          .then((res) => res.json())
          .then((data) => openEditTransactionModal(editTransactionModal, data));
      });
  }

  // ✅ Update / Save button inside modal
  document
    .querySelector(".save-transaction-btn")
    .addEventListener("click", async (e) => {
      e.preventDefault();

      const modalEl = editTransactionModal._element;
      const transactionId = modalEl.dataset.id; // get ID from modal dataset

      const payload = {
        description: modalEl.querySelector('input[name="description"]').value,
        amount: parseFloat(modalEl.querySelector('input[name="amount"]').value),
        date: modalEl.querySelector('input[name="date"]').value,
        category_id: parseInt(
          modalEl.querySelector('select[name="category_id"]').value,
        ),
      };

      try {
        const response = await post(
          `${BASE_PATH}/transactions/${transactionId}`,
          payload,
          modalEl,
        );

        const data = await response.json();

        if (response.ok && data.success) {
          editTransactionModal.hide();
          transactionTable.ajax.reload(null, false);
        }
        // 422 validation errors are automatically handled by ajax.js
      } catch (err) {
        console.error("AJAX error", err);
      }
    });
});

// Open modal and populate fields
function openEditTransactionModal(
  modal,
  { id, description, amount, date, category_id },
) {
  const modalEl = modal._element;
  modalEl.querySelector('input[name="description"]').value = description;
  modalEl.querySelector('input[name="amount"]').value = amount;
  modalEl.querySelector('input[name="date"]').value = date.substring(0, 10);
  modalEl.querySelector('select[name="category_id"]').value = category_id;

  // Set transaction ID on modal
  modalEl.dataset.id = id;

  modal.show();
}
