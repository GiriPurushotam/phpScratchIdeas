import { get, post, del } from "./ajax";

let editTransactionModal;
let transactionTable;
let uploadReceiptModal; // ✅ now properly initialized below

document.addEventListener("DOMContentLoaded", function () {
  // ✅ Fixed — separated from editTransactionModal
  editTransactionModal = new bootstrap.Modal(
    document.getElementById("editTransactionModal"),
  );
  uploadReceiptModal = new bootstrap.Modal(
    document.getElementById("uploadReceiptModal"),
  );

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
          // ✅ Fixed row.id → id
          render: (id) => `
            <div class="d-flex">
              <button class="ms-2 btn btn-primary delete-transaction-btn" data-id="${id}">
                <i class="bi bi-trash3-fill"></i>
              </button>
              <button class="ms-2 btn btn-outline-primary edit-transaction-btn" data-id="${id}">
                <i class="bi bi-pencil-fill"></i>
              </button>
              <button class="ms-2 btn btn-outline-primary open-receipt-upload-btn" data-id="${id}">
                <i class="bi bi-upload"></i>
              </button>
            </div>
          `,
        },
      ],
      pageLength: 10,
      drawCallback: bindTransactionButtons,
    });
  });

  function bindTransactionButtons() {
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

    $(".edit-transaction-btn")
      .off("click")
      .on("click", function () {
        const transactionId = $(this).data("id");

        get(`${BASE_PATH}/transactions/${transactionId}`)
          .then((res) => res.json())
          .then((data) => openEditTransactionModal(editTransactionModal, data));
      });

    // ✅ Added — opens upload modal with the correct transaction id
    $(".open-receipt-upload-btn")
      .off("click")
      .on("click", function () {
        const transactionId = $(this).data("id");

        uploadReceiptModal._element
          .querySelector(".upload-receipt-btn")
          .setAttribute("data-id", transactionId);

        uploadReceiptModal.show();
      });
  }

  document
    .querySelector(".save-transaction-btn")
    .addEventListener("click", async (e) => {
      e.preventDefault();

      const modalEl = editTransactionModal._element;
      const transactionId = modalEl.dataset.id;

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
      } catch (err) {
        console.error("AJAX error", err);
      }
    });

  // ✅ Added — handles the actual file upload
  document
    .querySelector(".upload-receipt-btn")
    .addEventListener("click", function (event) {
      const transactionId = event.currentTarget.getAttribute("data-id");
      const formData = new FormData();
      const files =
        uploadReceiptModal._element.querySelector('input[type="file"]').files;

      for (let i = 0; i < files.length; i++) {
        formData.append("receipt", files[i]);
      }

      post(
        `${BASE_PATH}/transactions/${transactionId}/receipts`,
        formData,
        uploadReceiptModal._element,
      ).then((response) => {
        if (response.ok) {
          transactionTable.ajax.reload(null, false);
          uploadReceiptModal.hide();
        }
      });
    });
});

function openEditTransactionModal(
  modal,
  { id, description, amount, date, category_id },
) {
  const modalEl = modal._element;
  modalEl.querySelector('input[name="description"]').value = description;
  modalEl.querySelector('input[name="amount"]').value = amount;
  modalEl.querySelector('input[name="date"]').value = date.substring(0, 10);
  modalEl.querySelector('select[name="category_id"]').value = category_id;

  modalEl.dataset.id = id;

  modal.show();
}
