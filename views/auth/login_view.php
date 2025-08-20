<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/login.css">
    <title>Login</title>
</head>

<body>
    <div class="login-card">
        <h2>Login</h2>

        <?php if (!empty($errors)): ?>
            <div class="errors" style="color: red; margin-bottom: 1em;">
                <ul>
                    <?php foreach ($errors as $fieldErrors): ?>
                        <?php foreach ($fieldErrors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_PATH ?>/login" method="POST" autocomplete="off">
            <?= $csrf['fields'] ?? '' ?>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="you@gmail.com"
                value="<?= isset($old['email']) ? htmlspecialchars($old['email']) : '' ?>" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="*********" required>

            <button type="submit">Log In</button>
        </form>
    </div>
</body>

</html>