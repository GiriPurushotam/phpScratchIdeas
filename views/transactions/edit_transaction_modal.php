<div class="modal fade" id="editTransactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Transaction</h5>
                <button type="button" data-bs-dismiss="modal" class="btn-close" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-outline form-white mb-4">
                    <label class="form-label">Description</label>
                    <input type="text" name="description" required class="form-control form-control-lg" placeholder="">
                </div>

                <div class="form-outline form-white mb-4">
                    <label class="form-label">Amount</label>
                    <input type="text" name="amount" required class="form-control form-control-lg" placeholder="">
                </div>

                <div class="form-outline form-white mb-4">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" required class="form-control form-control-lg" placeholder="">
                </div>

                <div class="form-outline form-white mb-4">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-select">
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category->getId() ?>"><?= $category->getName() ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Close
                </button>
                <button type="button" class="btn btn-success save-transaction-btn">
                    <i class="bi bi-check-circle me-1"></i> Save
                </button>
            </div>
        </div>
    </div>
</div>