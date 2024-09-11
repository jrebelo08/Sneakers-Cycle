<?php

declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../session/session.php');

$session = new Session();

$db = getDatabaseConnection();

$currentUserId = $session->getId() ?? null;

$users = User::getAllUsersFromDatabase($db);

if ($currentUserId !== null) {
    $users = array_filter($users, function ($user) use ($currentUserId) {
        return $user->id !== $currentUserId;
    });
}
?>

<head>
    <link rel="stylesheet" type="text/css" href="../style/management.css">
   
</head>

<body class="user-mng-body">


    <h1 id="user-mng">User List</h1>
    <?php if (!empty($users)) : ?>
        <ul class="user-list">
            <?php foreach ($users as $user) : ?>
                <li>
                    <a class="a-usermng" href="edit_user.php?id=<?= $user->id ?>"><?= $user->username ?></a>
                    <form action="../actions/action_delete_user.php" method="post" style="display: inline;">
                        <input type="hidden" name="userId" value="<?= $user->id ?>">
                        <div class="button-container">
                            <button type="submit" class="delete-account" onclick="return confirm('Are you sure you want to delete your account?')"><span class="text">Delete account</span><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <path d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z"></path>
                      
                                    </svg></span></button>
                        </div>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>No users found.</p>
    <?php endif; ?>
</body>

</html>