<?php

$title = 'Transactions';
ob_start();

?>

<div class="text-end mb-3">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newTransactionModal">
        <i class="bi bi-plus-circle me-1"></i> New Transaction
    </button>
</div>

<div class="modal fade" id="newTransactionModal" tabindex="-1" area-hidden="true">
    <div class="modal-dialog">
        <form action="<?= BASE_PATH ?>/transactions" method="POST" id="transactionForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismis="modal" aria-label="close"></button>
                </div>

                <div class="modal-body d-flex flex-column align-items-center">
                    <?= $csrf['fields'] ?? '' ?>
                    <div class="mb-3 text-center">
                        <label for="transactionName" class="form-label"">Transaction Name</label>
                        <input type=" text" id="transactionName" name="name" required class="form-control" placeholder="Enter Transaction Name">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Close
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Create
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>



<div class="mt-4">
    <table id="transactionsTable" class="table table-striped">
        <thead>
            <tr>
                <th>Category</th>
                <th>Description</th>
                <th>Date</th>
                <th>Amount</th>
            </tr>
        </thead>
    </table>
</div>





<?php

$content = ob_get_clean();
include __DIR__ . '/transactions_layout.php';

?>