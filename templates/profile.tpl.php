<?php
declare(strict_types=1);

require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/shipment_user.class.php');
require_once(__DIR__ . '/../session/session.php');

$session = new Session();


function drawProfile(User $user, Session $session): void {
  $db = getDatabaseConnection();

  $my_type = $session->isLoggedIn() ? User::getUserTypeByUsername($db, $session->getName()) : null;

  $shipmentInfo = ShipmentUserInfo::getShipmentInfoUserID($db, $user->id);

  ?>
  <link rel="stylesheet" href="../style/profile.css">
  <section id="profile-info">
      <div class="profile-container">
      <div id="profile-img">
          <img src="../imgs/profilepick.png"></img>
      </div>
      <div class="info-container">
      <div id="profile-username">
        <span id="username"><?= $user->username ?></span>
        <div class="a-div">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#0056B3" class="bi bi-pencil" viewBox="0 0 16 16">
            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
          </svg>
        <a id="a-username" href="change_username.php?username=<?= $user->username ?>">Change</a>
        </div>
      </div>
      
      
      <div id="profile-email">
        <span id="username"><?= $user->email ?></span>
        <div class="a-div">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#0056B3" class="bi bi-pencil" viewBox="0 0 16 16">
            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
          </svg>
          <a id="a-email" href="change_email.php?username=<?= $user->username ?>">Change</a>
        </div>
      </div>
      <?php if (($my_type === 'seller' || $my_type === 'buyer/seller') && $user->items_listed >= 1): ?>
      <div id="my-items">
        <span id="username">My Items:</span>
        <div class="b-div">
          <a id="a-items" href="user_items.php?username=<?= $user->username ?>"><i class="fa-solid fa-eye"></i>View my posted items</a>
        </div>
       </div>
       <?php endif; ?>

      <div id="profile-password">
        <div class="span">
          <span id="bold"><strong>Password:</strong></span> 
          <span id="content">(Hidden for security)</span>
        </div>
          <div class="a-div">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#0056B3" class="bi bi-pencil" viewBox="0 0 16 16">
              <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
            </svg>
          <a id="a-pass" href="change_password.php?username=<?= $user->username ?>">Change</a>
          </div>
      </div>

      <div id="profile-payment">
          <span id="bold"><strong>Payment Information:</strong></span>
          <?php if ($user->hasPaymentInfo()): ?>
              <span id="content">Payment information is available</span>
              <div class="a-div">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#0056B3" class="bi bi-pencil" viewBox="0 0 16 16">
                  <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                </svg>
              <a id="a-payment"href="edit_payment.php?username=<?= $user->username ?>">Change</a>
              </div>
          <?php else: ?>
              <span id="content">No payment information added</span>
              <div class="a-div">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#0056B3" class="bi bi-pencil" viewBox="0 0 16 16">
          <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
        </svg>
              <a id="a-payment" href="add_payment.php?username=<?= $user->username ?>">Add</a>
      </div>
          <?php endif; ?>
      </div>

      <div id="profile-shipping">
            <span id="bold"><strong>Shipping Information:</strong></span>
            <?php if ($shipmentInfo !== null): ?>
                <span id="content">Shipping information is available</span>
                <div class="a-div">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#0056B3" class="bi bi-pencil" viewBox="0 0 16 16">
            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
          </svg>
                <a id="a-shipping" href="add_shipping.php?username=<?= $user->username ?>">Change</a>
                </div>
            <?php else: ?>
                <span id="content">No shipping information added</span>
                <div class="a-div">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#0056B3" class="bi bi-pencil" viewBox="0 0 16 16">
            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
          </svg>
                <a id= "a-shipping" href="add_shipping.php?username=<?= $user->username ?>">Add</a>
        </div>
            <?php endif; ?>
        </div>
      </div>
      </div>
        <form action="../actions/action_delete_user.php" method="post" id="delete-user-form">
            <input type="hidden" name="userId" value="<?= $user->id ?>">
            <div class="button-container">
            <button type="submit" class="delete-account" onclick="return confirm('Are you sure you want to delete your account?')"><span class="text">Delete account</span><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z"></path></svg></span></button>
            </div>
        </form>
      </section>
      
      <?php if ($my_type == 'admin'): ?>
          <div class="admin-moderation">
              <span class="bold">Admin Moderation</span>
              <div class="admin-manage">
                <div class="first-a">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#0056B3" class="bi bi-dot" viewBox="0 0 16 16">
                  <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/>
                </svg>
              <a id="a-admin" href="item_management.php">Manage items</a>
              </div>
              <div class="second-a">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#0056B3" class="bi bi-dot" viewBox="0 0 16 16">
                <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3"/>
              </svg>
              <a id="a-admin" href="user_management.php">Manage users</a>
            </div>
              </div>
          </div>
      <?php endif; ?>
 
  <?php
}
?>




<?php function drawRegisterForm($session) { ?>
  <link rel="stylesheet" href="../style/login.css">
  <form action="../actions/action_register.php" method="post" class="login-form"> 
    <div class="form-group">
      <label for="username">Username</label>
      <input id="user-input" type="text" name="username" id="username" required>
    </div>
    <div class="form-group">
      <label for="email">Email address</label>
      <input type="email" name="email" id="email" required>
    </div>
    <div class="form-group">
      <label for="password">Enter a password</label>
      <input type="password" name="password" id="password" required>
    </div>
    <div class="form-group">
      <label for="password2">Confirm the password</label>
      <input type="password" name="confirm-password" id="confirm-password" required>
    </div>
    <input type="hidden" name="csrf" value="<?=$session->getCSRF()?>">
    <button type="submit" class="btn btn-primary">Register</button>
  </form>

  <section id="messages">
      <?php 
      $messages = $session->getMessages();
      foreach ($messages as $message) {
          echo "<div class='login-register-alert'>{$message['text']}</div>";
      }
      ?>
  </section>

<?php } ?>