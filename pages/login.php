<?php require_once('../templates/common.tpl.php') ?>

<?php
  drawHeader(false);
?>

<main>
  <section class="login-section">
  <div class="login-container">
    <h2>Login</h2>
    <?php drawLoginForm($session); ?>
    </div>
  </section>
</main>

<?php
?>
