import { get, post, del, ajax } from "./ajax";

let editTransactionModal;
let transactionTable;

document.addEventListener("DOMContentLoaded", function () {
  const editTransactionModal = new bootstrap.Modal(
    document.getElementById("editTransactionModal"),
  );

  $(function () {
    transactionTable = $("#transactionsTable").DataTable({
      processing: true,
      serverSide: true,
      ajax: `${BASE_PATH}/transactions/load`,
      columns: [
        { data: "description" },
        { data: "amount" },
        { data: "data" },
        { data: "category" },
        { data: "created_at" },
        { data: "updated_at" },
        {
          data: "id",
          sortable: false,
          render: function (id) {
            return `
             <div class="d-flex flex">
        <button type="submit" class="btn btn-outline-primary delete-transaction-btn" data-id="${id}">
        <i class="bi bi-trash3-fill"> </i>
        </button>
        <button class="btn btn-outline-primary edit-transaction-btn" data-id="${id}">
        <i class="bi bi-pencil-fill"> </i>
        </button>
        </div>
            `;
          },
        },
      ],

      pageLength: 10,
      response: true,
      drawCallback: bindTransactionButton,
    });
  });

  function bindTransactionButton() {
    $(".edit-transaction-btn")
      .off("click")
      .on("click", function () {
        const transactionId = $(this).data("id");

        get(`${BASE_PATH}/transactions/${transactionId}`)
          .then((res) => res.json())
          .then((res) => openEditTransactionModal(editTransactionModal, res));
      });

    $(".delete-transaction-btn")
      .off("click")
      .on("click", function () {
        const transactionId = $(this).data("id");
        if (confirm("Are you sure you want to delete this transaction")) {
          del(`${BASE_PATH}/transctions/${transactionId}`).then(() => {
            transactionTable.ajax.reload(null, false);
          });
        }
      });
  }
});

function openEditTransactionModal(modal, { id, name }) {
  modal.show();
}
