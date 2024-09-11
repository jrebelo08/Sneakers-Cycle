<?php

declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../session/session.php');
require_once(__DIR__ . '/../database/user.class.php');

$session = new Session();

$db = getDatabaseConnection();

$userId = (int)$_GET['id'];

$user = User::getUserById($db, $userId);

$userTypes = ['buyer', 'buyer/seller', 'admin'];

?>
<link rel="stylesheet" href="../style/registers.css">
<form action="../actions/action_edit_user.php" method="post">
    <div class="user-container">
        <input type="hidden" name="id" value="<?= $user->id ?>">
        <span>Username</span><input class="user-info" type="text" name="username" value="<?= $user->username ?>"><br>
        <span>Email</span><input class="user-info" type="email" name="email" value="<?= $user->email ?>"><br>
        <div class="type-container">
            <span>User Type</span>
            <select name="type">
                <?php foreach ($userTypes as $type) : ?>
                    <option value="<?= $type ?>" <?= $type === $user->type ? ' selected' : '' ?>><?= ucfirst($type) ?></option>
                <?php endforeach; ?>
            </select><br>
        </div>
        <input type="hidden" name="csrf" value="<?= $session->getCSRF() ?>">
        <input class="submit-button" type="submit" value="Update">
    </div>
</form>