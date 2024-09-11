<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../templates/common.tpl.php');
    require_once(__DIR__ . '/../session/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/user.class.php');

    if (!($session->isLoggedIn())) {
        header('Location: ../index.php');
        exit();
    }

    $db = getDatabaseConnection();
    $user_type = $session->isLoggedIn() ? User::getUserTypeByUsername($db, $session->getName()) : null;

    if (($user_type != 'admin') && ($session->getName() != $session->getName())) {
        header('Location: ../index.php');
        exit();
    }

        ?>
        <?php drawHeader($session, false); ?>
        <link rel="stylesheet" href="../style/registers.css">
        <script src="../javascript/DynamicHeight.js" defer></script>
        <form action="../actions/action_change_email.php" method="post" enctype="multipart/form-data">
        <div  id="user-container">    
        <?php if ($user_type != 'admin') { ?>
            <span>Old password</span>
            <input type="password" name="old_password" class="user-info"  id="oldPassword">
            <?php } ?>
            <span>New email</span>
            <input type="email" name="new_email" class="user-info">
            <label for="confirm_email">Confirm email</label>
            <input type="email" name="confirm_email" class="user-info" id="margin-change">
            <input type="hidden" name="csrf" value="<?=$session->getCSRF()?>">
            <button class="submit-button" type="submit">Submit</button>
            </div>
        </form>
        <?php
?>