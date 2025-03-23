<?php
session_start();

if (!isset($_SESSION["uid"])) {
    die("Error: User not logged in");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once("ffdbConn.php");

    $uid = $_SESSION["uid"];
    $totalPrice = $_POST['totalPrice'] ?? 0;
    $cartData = $_POST['cartData'] ?? '[]';
    $cartArray = json_decode($cartData, true);

    $addressLine1 = $_POST['addressLine1'] ?? '';
    $city = $_POST['city'] ?? '';
    $postCode = $_POST['postCode'] ?? '';
    $country = $_POST['country'] ?? '';
    $m_Number = $_POST['m_Number'] ?? '';

    try {
        $pdo->beginTransaction();

        $checkStmt = $pdo->prepare("SELECT aid FROM address WHERE uid = ?");
        $checkStmt->execute([$uid]);

        if ($checkStmt->rowCount() > 0) {
            $updateStmt = $pdo->prepare("UPDATE address SET addressLine1 = ?, city = ?, postcode = ?, country = ?, m_Number = ? WHERE uid = ?");
            $updateStmt->execute([$addressLine1, $city, $postCode, $country, $m_Number, $uid]);
        } else {
            $insertStmt = $pdo->prepare("INSERT INTO address (addressLine1, city, postcode, country, uid, m_Number) VALUES (?, ?, ?, ?, ?, ?)");
            $insertStmt->execute([$addressLine1, $city, $postCode, $country, $uid, $m_Number]);
        }

        $orderStmt = $pdo->prepare("INSERT INTO orders (orderAmount, orderDate, uid) VALUES (?, ?, ?)");
        $orderStmt->execute([$totalPrice, date("Y-m-d"), $uid]);
        $orderID = $pdo->lastInsertId();

        $detailStmt = $pdo->prepare("INSERT INTO orderdetails (orderID, pid, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($cartArray as $pid => $item) {
            if (is_array($item)) {
                $detailStmt->execute([$orderID, $pid, $item['quantity'], $item['price']]);
            }
        }

        $pdo->commit();
        $_SESSION['order_success'] = "Your order has been successful!";
        header("Location: home.php");
        exit;

    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "An error occurred, Please try again later, you have not been charged!";
    }
}
?>