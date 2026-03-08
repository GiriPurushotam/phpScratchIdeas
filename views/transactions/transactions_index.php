<?php

$title = 'Transactions';
ob_start();

?>

<div class="text-end mb-3">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newTransactionModal">
        <i class="bi bi-plus-circle me-1"></i> New Transaction
    </button>
</div>

<div class="modal fade" id="newTransactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= BASE_PATH ?>/transactions" method="POST" id="transactionForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Transaction</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                </div>

                <div class="modal-body d-flex flex-column align-items-center">
                    <?= $csrf['fields'] ?? '' ?>
                    <div class="mb-3 text-center">
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category->getId() ?>">
                                    <?= htmlspecialchars($category->getName()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3 text-center">
                        <label for="transactionDescription" class="form-label">Description</label>
                        <input type=" text" id="transactionDescription" name="description" required class="form-control" placeholder="Description">
                    </div>
                    <div class="mb-3 text-center">
                        <label for="transactionDate" class="form-label">Date</label>
                        <input type="date" id="transactionDate" name="date" required class="form-control">
                    </div>
                    <div class="mb-3 text-center">
                        <label for="transactionAmount" class="form-label">Amount</label>
                        <input type="number" step="0.01" id="transactionAmount" name="amount" required class="form-control">
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

<?php include __DIR__ . '/edit_transaction_modal.php'; ?>

<div class="mt-4">
    <table id="transactionsTable" class="table table-striped">
        <thead>
            <tr>
                <th>Category</th>
                <th>Description</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Created At</th>
                <th>Updated At</th>
            </tr>
        </thead>
    </table>
</div>





<?php

$content = ob_get_clean();
include __DIR__ . '/transactions_layout.php';

?>