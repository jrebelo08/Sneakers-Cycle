<?php
declare(strict_types=1);

require_once(__DIR__ . '/../session/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/shipment_user.class.php');
require_once(__DIR__ . '/../database/user.class.php');

$session = new Session();
$username = $session->getName();
$db = getDatabaseConnection();

$user = User::getUserByUsername($db, $username);

if ($user) {
    if (!isset($_POST['submit'])) {
        $shipmentInfo = ShipmentUserInfo::getShipmentInfoUserID($db, $user->id);
        ?>
        <link rel="stylesheet" type="text/css" href="../style/payment.css">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="shipping-container">
            <label for="shippingCountry">Shipping Country:</label>
            <select id="country" name="shippingCountry">
                <option value="">Select Country</option>
                <option value="Portugal">Portugal</option>
                <option value="Spain">Spain</option>
                <option value="France">France</option>
                <option value="Germany">Germany</option>
                <option value="Italy">Italy</option>
                <option value="United Kingdom">United Kingdom</option>
            </select>
            <br>
            <label for="shippingCity">Shipping City:</label>
            <select id="city" name="shippingCity">
            </select>
            <br>
            <label for="shippingZipCode">Shipping Zip Code:</label>
            <input type="text" name="shippingZipCode" value="<?php echo $shipmentInfo ? $shipmentInfo->shippingZipCode : ''; ?>">
            <br>
            <label for="shippingAddress">Shipping Address:</label>
            <input type="text" name="shippingAddress" value="<?php echo $shipmentInfo ? $shipmentInfo->shippingAddress : ''; ?>">
            <br>
            <input type="submit" name="submit" value="Update Shipping Information">
    </div>
        </form>

        <script src="../javascript/shipping.js">
        </script>
        <?php
    } else {
        $shippingCountry = $_POST['shippingCountry'];
        $shippingCity = $_POST['shippingCity'];
        $shippingZipCode = $_POST['shippingZipCode'];
        $shippingAddress = $_POST['shippingAddress'];
        
        if ($user->id) {
            $userId = $user->id;
            $shipmentInfo = ShipmentUserInfo::getShipmentInfoUserID($db, $userId);
            if ($shipmentInfo) {
                $stmt = $db->prepare('UPDATE ShipmentUserInfo SET ShippingAddress = ?, ShippingCity = ?, ShippingZipCode = ?, ShippingCountry = ? WHERE UserId = ?');
                $stmt->execute([$shippingAddress, $shippingCity, $shippingZipCode, $shippingCountry, $userId]);
            } else {
                $stmt = $db->prepare('INSERT INTO ShipmentUserInfo (UserId, ShippingAddress, ShippingCity, ShippingZipCode, ShippingCountry) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([$userId, $shippingAddress, $shippingCity, $shippingZipCode, $shippingCountry]);
            }

            header('Location: profile.php');
            exit;
        } else {
            echo "User ID not found.";
        }
    }
} else {
    echo "User not found.";
}
?>