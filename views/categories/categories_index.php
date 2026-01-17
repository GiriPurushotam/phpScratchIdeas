<?php

$title = 'Categories';
ob_start();

?>

<div class="text-end mb-3">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newCategoryModal">
        <i class="bi bi-plus-circle me-1"></i> New Category
    </button>
</div>

<div class="modal fade" id="newCategoryModal" tabindex="-1" area-hidden="true">
    <div class="modal-dialog">
        <form action="<?= BASE_PATH ?>/categories" method="POST" id="categoryForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismis="modal" aria-label="close"></button>
                </div>

                <div class="modal-body d-flex flex-column align-items-center">
                    <?= $csrf['fields'] ?? '' ?>
                    <div class="mb-3 text-center">
                        <label for="categoryName" class="form-label"">Category Name</label>
                        <input type=" text" id="categoryName" name="name" required class="form-control" placeholder="Enter Category Name">
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

<?php include __DIR__ . '/edit_category_modal.php'; ?>

<?php if (!empty($categories)): ?>
    <div class="mt-4" id="categoriesTable">
        <table class="table table-striped" id="categoryTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody> </tbody>
        </table>
    </div>

<?php endif; ?>



<?php

$content = ob_get_clean();
include __DIR__ . '/categories_layout.php';

?>