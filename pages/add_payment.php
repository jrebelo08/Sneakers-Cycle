<?php

declare(strict_types=1);

require_once(__DIR__ . '/../session/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');

$session = new Session();
$username = $session->getName();
$db = getDatabaseConnection();

$user = User::getUserByUsername($db, $username);

if ($user) {
    if ($user->hasPaymentInfo()) {
        echo "You already have payment information added.";
    } else {
        if (!isset($_POST['submit'])) {
?>
            <link rel="stylesheet" type="text/css" href="../style/payment.css">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="payment-container">
                    <label for="paymentMethod">Payment Method:</label>
                    <select name="paymentMethod">
                        <option value="credit_card">Credit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="mbway">MBWay</option>
                    </select>
                    <br>
                    <label for="paymentInfo">Payment Information:</label>
                    <textarea name="paymentInfo" cols="30" rows="10"></textarea>
                    <br>
                    <input type="submit" name="submit" value="Add Payment Information">
                </div>
            </form>

<?php
        } else {
            $paymentMethod = $_POST['paymentMethod'];
            $paymentInfo = $_POST['paymentInfo'];

            if ($user->id) {
                $userId = $user->id;
                $stmt = $db->prepare('UPDATE User SET PaymentMethod = ?, PaymentInfo = ? WHERE UserId = ?');
                $stmt->bindParam(1, $paymentMethod);
                $stmt->bindParam(2, $paymentInfo);
                $stmt->bindParam(3, $userId);
                $stmt->execute();
                $user->setPaymentInfo($paymentInfo);
                $user->setPaymentMethod($paymentMethod);
                header('Location: profile.php');
                exit;
            } else {
                echo "User ID not found.";
            }
        }
    }
} else {
    echo "User not found.";
}
?>