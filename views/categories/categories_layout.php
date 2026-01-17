<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'My App' ?></title>
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/navbar.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.6/css/dataTables.dataTables.css" />


    <?php if (isset($csrf)): ?>
        <meta id="csrfName" name="<?= htmlspecialchars($csrf['keys']['name']) ?>" content="<?= htmlspecialchars($csrf['name']) ?>">
        <meta id="csrfValue" name="<?= htmlspecialchars($csrf['keys']['value']) ?>" content="<?= htmlspecialchars($csrf['value']) ?>">

    <?php endif; ?>

</head>

<body>
    <?php include __DIR__ . '/../layout_navbar.php' ?>

    <div class="container mt-4">
        <?= $content ?? '' ?>
    </div>

    <!-- Jquery for DataTables -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


    <!-- DataTables Js -->
    <script src="https://cdn.datatables.net/2.3.6/js/dataTables.js"></script>


    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>


    <script>
        const BASE_PATH = "<?= BASE_PATH ?>";
    </script>

    <!-- Custom Js -->
    <script type="module" src="<?= BASE_PATH ?>/assets/js/category.js"></script>
</body>

</html>