<?php

require_once(__DIR__ . '/../session/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../database/shipment_user.class.php');

function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371;
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2) * sin($dLat/2) + sin(deg2rad($lat1)) * sin(deg2rad($lat2)) * cos(deg2rad($dLat)) * cos(deg2rad($lat1));
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    $distance = $earthRadius * $c;
    return $distance;
}

function calculateShippingCost($weight, $distance) {
    $shippingRates = array(
        'local' => 5.00,
        'international' => 50.00
    );

    if ($distance < 500) {
        $distanceCategory = 'local';
    } else {
        $distanceCategory = 'international';
    }

    $shippingCost = $shippingRates[$distanceCategory];

    if ($weight > 10) {
        $shippingCost = 0.90 * $shippingCost;
    }

    return $shippingCost;
}

function drawCart($session) {

    $portugueseDistricts = array(
        'Aveiro' => array('latitude' => 40.6253, 'longitude' => -8.6442),
        'Beja' => array('latitude' => 38.0453, 'longitude' => -7.8442),
        'Braga' => array('latitude' => 41.5333, 'longitude' => -8.4333),
        'Bragança' => array('latitude' => 41.8333, 'longitude' => -6.9333),
        'Castelo Branco' => array('latitude' => 39.7333, 'longitude' => -7.5333),
        'Coimbra' => array('latitude' => 40.2333, 'longitude' => -8.5333),
        'Évora' => array('latitude' => 38.5333, 'longitude' => -7.9333),
        'Faro' => array('latitude' => 37.9833, 'longitude' => -7.9833),
        'Guarda' => array('latitude' => 40.5333, 'longitude' => -7.2333),
        'Leiria' => array('latitude' => 39.7333, 'longitude' => -8.5333),
        'Lisboa' => array('latitude' => 38.7333, 'longitude' => -9.1833),
        'Madeira' => array('latitude' => 32.6333, 'longitude' => -16.9833),
        'Porto' => array('latitude' => 41.1833, 'longitude' => -8.6333),
        'Santarém' => array('latitude' => 39.1833, 'longitude' => -8.9333),
        'Setúbal' => array('latitude' => 38.5333, 'longitude' => -8.9333),
        'Viana do Castelo' => array('latitude' => 41.7333, 'longitude' => -8.2333),
        'Vila Real' => array('latitude' => 41.1833, 'longitude' => -7.5333),
        'Viseu' => array('latitude' => 40.6333, 'longitude' => -7.9333)
    );
    
    $spanishCities = array(
        'Madrid' => array('latitude' => 40.4168, 'longitude' => -3.7038),
        'Barcelona' => array('latitude' => 41.3851, 'longitude' => 2.1763),
        'Valencia' => array('latitude' => 39.4637, 'longitude' => -0.3753)
    );
    
    $franceCities = array(
        'Paris' => array('latitude' => 48.8567, 'longitude' => 2.3508),
        'Lyon' => array('latitude' => 45.7644, 'longitude' => 4.8352),
        'Marseille' => array('latitude' => 43.2963, 'longitude' => 5.3695)
    );
    
    $germanyCities = array(
        'Berlin' => array('latitude' => 52.5200, 'longitude' => 13.4050),
        'Munich' => array('latitude' => 48.1372, 'longitude' => 11.5820),
        'Hamburg' => array('latitude' => 53.5503, 'longitude' => 9.9937)
    );
    
    $italyCities = array(
        'Rome' => array('latitude' => 41.8539, 'longitude' => 12.4924),
        'Milan' => array('latitude' => 45.4649, 'longitude' => 9.1899),
        'Venice' => array('latitude' => 45.4349, 'longitude' => 12.3294)
    );
    
    $ukCities = array(
        'London' => array('latitude' => 51.5074, 'longitude' => -0.1278),
        'Manchester' => array('latitude' => 53.4805, 'longitude' => -2.2447),
        'Birmingham' => array('latitude' => 52.4869, 'longitude' => -1.8903)
    );
    
    $db = getDatabaseConnection();
    $cart = $session->getCart();
    $user = User::getUserByUsername($db, $session->getName());
    $shipmentUser = ShipmentUserInfo::getShipmentInfoUserID($db, $user->id);
    $shippingCity = $shipmentUser->shippingCity;
    $weight = 2 * count($cart);
    $shippingCost = 0;

    if (array_key_exists($shippingCity, $portugueseDistricts)) {
        $latitude = $portugueseDistricts[$shippingCity]['latitude'];
        $longitude = $portugueseDistricts[$shippingCity]['longitude'];
    } elseif (array_key_exists($shippingCity, $spanishCities)) {
        $latitude = $spanishCities[$shippingCity]['latitude'];
        $longitude = $spanishCities[$shippingCity]['longitude'];
    } elseif (array_key_exists($shippingCity, $franceCities)) {
        $latitude = $franceCities[$shippingCity]['latitude'];
        $longitude = $franceCities[$shippingCity]['longitude'];
    } elseif (array_key_exists($shippingCity, $germanyCities)) {
        $latitude = $germanyCities[$shippingCity]['latitude'];
        $longitude = $germanyCities[$shippingCity]['longitude'];
    } elseif (array_key_exists($shippingCity, $italyCities)) {
        $latitude = $italyCities[$shippingCity]['latitude'];
        $longitude = $italyCities[$shippingCity]['longitude'];
    } elseif (array_key_exists($shippingCity, $ukCities)) {
        $latitude = $ukCities[$shippingCity]['latitude'];
        $longitude = $ukCities[$shippingCity]['longitude'];
    }

    $item_ids = []; 

    if (empty($cart)) {
        echo "<p>Your cart is empty.</p>";
    } else {
        echo "<h1>My Cart</h1>";
        echo "<section class='cart-products'>";
        echo "<ul id='cart-list'>"; 
        foreach ($cart as $item) {
            echo "<li>"; 
            echo "<form id='itemDele' action='../actions/action_cart_removeItem.php' method='post'>";
            echo "<div class='cart-product'>";
            echo '<img class="product-image" src="' . $item->getImageUrl() . '" alt="Product Image">';
            echo "<div class='product-info'>";
            echo "<h2 class='product-name'>Model:{$item->itemName}</h2>";
            echo "<p class='product-brand'>Brand:{$item->itemBrand}</p>";
            echo "<p class='product-price'>Price: {$item->itemPrice}$</p>";
            echo "<p class='product-category'>Category: {$item->itemCategory}</p>";
            echo "</div>";
            echo "<input type='hidden' name='item_json' value='" . htmlspecialchars(json_encode($item)) . "'>";
            echo '<input type="hidden" name="csrf" value="' . $session->getCSRF() . '">';
            echo "<button class='delete-btn' type='submit'> <i class='fa-solid fa-trash'></i> </button>"; 
            echo "</div>";
            echo "</form>";
            echo "</li>"; 
        }
        echo "</ul>"; 
                
        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item->itemPrice;
        }
        $discount = 0;
        $numberOfItems = count($cart);
        if ($numberOfItems == 2) {
            $discount = $totalAmount * 0.10; 
        } elseif ($numberOfItems == 3) {
            $discount = $totalAmount * 0.20; 
        } elseif ($numberOfItems >= 4) {
            $discount = $totalAmount * 0.35; 
        }
    
        $totalAmount -= $discount;
    

        $distance = calculateDistance($latitude, $longitude, $user->latitude, $user->longitude);
        $shippingCost = calculateShippingCost($weight, $distance);
        echo "<div class='infos-checkout'>";
        echo "<p> <strong>Total:</strong> " . number_format($totalAmount, 2) . "$</p>";
        if ($discount > 0) {
            echo "<p>Discount: " . number_format($discount, 2) . "$</p>";
        }
        if ($numberOfItems === 1) {
            echo "<p>Buy one more item to obtain a 10% discount.</p>";
        }
        
        $userId = $session->getId();
        $db = getDatabaseConnection();
        $shipmentUserInfo = ShipmentUserInfo::getShipmentInfoUserID($db, $userId);
        if ($shipmentUserInfo) {
            echo "<p>Shipping Address: " . $shipmentUserInfo->shippingAddress . ", " . $shipmentUserInfo->shippingCity . " " . $shipmentUserInfo->shippingZipCode . " " . $shipmentUserInfo->shippingCountry . "</p>";
        }

        echo "<p>Payment Method: " . $user->paymentMethod . "</p>";
        echo "<p>Payment Info: " . $user->paymentInfo . "</p>";

        echo "<p>Estimated Shipping Cost: $" . number_format($shippingCost, 2) . "</p>";
        echo "<p>Total with Shipping: $" . number_format($totalAmount + $shippingCost, 2) . "</p>";


        echo "<form action='../actions/action_checkout.php' method='post'>";
        echo "<button class='checkout-btn' type='submit'>Checkout</button>";
        echo "<input type='hidden' name='shippingCost' value='" . $shippingCost . "'>";
        echo "<input type='hidden' name='totalAmount' value='" . $totalAmount . "'>";
        echo '<input type="hidden" name="csrf" value="' . $session->getCSRF() . '">';
        echo "</form>";
        echo "</div>";

        echo "</section>";
    }
}
?>