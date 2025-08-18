<nav>
  <div class="nav-links">
    <a href="/category1">Category 1</a>
    <a href="/category2">Category 2</a>
  </div>

  <div class="user-dropdown" tabindex="0">
    <div class="user-info" aria-haspopup="true" aria-expanded="false">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="user-icon" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
      </svg>

      <?= htmlspecialchars($userData->getName() ?? 'User') ?>
    </div>

    <div class="dropdown-menu" role="menu">
      <form action="<?= BASE_PATH ?>/logout" method="post">
        <button type="submit" class="dropdown-item">Log Out</button>
      </form>
    </div>
  </div>
</nav>