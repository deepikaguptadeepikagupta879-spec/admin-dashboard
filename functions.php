<?php

require_once "config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function clean($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, "UTF-8");
}

function redirect($page)
{
    header("Location: " . $page);
    exit;
}

function loggedIn()
{
    return isset($_SESSION["user"]);
}

function requireLogin()
{
    if (!loggedIn()) {
        redirect("login.php");
    }
}

function requireAdmin()
{
    if (
        !loggedIn() ||
        $_SESSION["user"]["role"] != "admin"
    ) {
        redirect("login.php");
    }
}

function loginUser($email, $password, $pdo)
{
    $stmt = $pdo->prepare(
        "SELECT * FROM users
         WHERE email=?
         LIMIT 1"
    );

    $stmt->execute([$email]);

    $user = $stmt->fetch();

    if ($user && $password == $user["password"]) {

        $_SESSION["user"] = [

            "id" => $user["id"],
            "name" => $user["name"],
            "email" => $user["email"],
            "role" => $user["role"]

        ];

        return true;
    }

    return false;
}

function logoutUser()
{
    $_SESSION = [];
    session_destroy();
}

function userId()
{
    return $_SESSION["user"]["id"] ?? 0;
}

function userName()
{
    return $_SESSION["user"]["name"] ?? "";
}

function userRole()
{
    return $_SESSION["user"]["role"] ?? "";
}

function formatPrice($price)
{
    return "₹" . number_format($price, 2);
}

function getCategories($pdo)
{
    $stmt = $pdo->query(
        "SELECT *
         FROM categories
         ORDER BY name ASC"
    );

    return $stmt->fetchAll();
}

function getProducts($pdo)
{
    $stmt = $pdo->query(

        "SELECT
            products.*,
            categories.name AS category_name

         FROM products

         LEFT JOIN categories

         ON products.category_id=categories.id

         ORDER BY products.id DESC"

    );

    return $stmt->fetchAll();
}

function getProductById($pdo, $id)
{
    $stmt = $pdo->prepare(

        "SELECT *
         FROM products
         WHERE id=?
         LIMIT 1"

    );

    $stmt->execute([$id]);

    return $stmt->fetch();
}

function searchProducts($pdo, $search)
{
    $stmt = $pdo->prepare(

        "SELECT *

         FROM products

         WHERE name LIKE ?
         OR brand LIKE ?
         OR processor LIKE ?
         OR ram LIKE ?
         OR storage LIKE ?

         ORDER BY id DESC"

    );

    $value = "%" . $search . "%";

    $stmt->execute([

        $value,
        $value,
        $value,
        $value,
        $value

    ]);

    return $stmt->fetchAll();
}

function getProductsByCategory($pdo, $category)
{
    $stmt = $pdo->prepare(

        "SELECT *

         FROM products

         WHERE category_id=?

         ORDER BY id DESC"

    );

    $stmt->execute([$category]);

    return $stmt->fetchAll();
}

function totalProducts($pdo)
{
    return $pdo->query(
        "SELECT COUNT(*) FROM products"
    )->fetchColumn();
}

function totalUsers($pdo)
{
    return $pdo->query(
        "SELECT COUNT(*) FROM users"
    )->fetchColumn();
}

function totalOrders($pdo)
{
    return $pdo->query(
        "SELECT COUNT(*) FROM orders"
    )->fetchColumn();
}

function totalRevenue($pdo)
{
    return $pdo->query(
        "SELECT COALESCE(SUM(total),0) FROM orders"
    )->fetchColumn();
}

function addToCart($productId)
{
    if (!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = [];
    }

    if (isset($_SESSION["cart"][$productId])) {

        $_SESSION["cart"][$productId]++;

    } else {

        $_SESSION["cart"][$productId] = 1;

    }
}

function updateCart($productId, $quantity)
{
    if (isset($_SESSION["cart"][$productId])) {

        $_SESSION["cart"][$productId] = $quantity;

    }
}

function removeFromCart($productId)
{
    if (isset($_SESSION["cart"][$productId])) {

        unset($_SESSION["cart"][$productId]);

    }
}

function cartCount()
{
    if (!isset($_SESSION["cart"])) {

        return 0;

    }

    return array_sum($_SESSION["cart"]);
}

function clearCart()
{
    unset($_SESSION["cart"]);
}

function getCartItems($pdo)
{
    $items = [];

    if (empty($_SESSION["cart"])) {
        return $items;
    }

    $ids = array_keys($_SESSION["cart"]);

    $placeholders = implode(",", array_fill(0, count($ids), "?"));

    $stmt = $pdo->prepare(

        "SELECT *

         FROM products

         WHERE id IN ($placeholders)"

    );

    $stmt->execute($ids);

    $products = $stmt->fetchAll();

    foreach ($products as $product) {

        $product["quantity"] = $_SESSION["cart"][$product["id"]];

        $product["subtotal"] =
            $product["price"] * $product["quantity"];

        $items[] = $product;
    }

    return $items;
}

function cartTotal($pdo)
{
    $total = 0;

    foreach (getCartItems($pdo) as $item) {

        $total += $item["subtotal"];

    }

    return $total;
}

function uploadProductImage($file)
{
    if (
        isset($file) &&
        $file["error"] == 0
    ) {

        $extension = strtolower(

            pathinfo(
                $file["name"],
                PATHINFO_EXTENSION
            )

        );

        $fileName = time() . "_" . rand(1000,9999) . "." . $extension;

        $path = UPLOAD_PATH . $fileName;

        move_uploaded_file(
            $file["tmp_name"],
            $path
        );

        return $path;
    }

    return "";
}

function deleteProductImage($image)
{
    if (
        !empty($image) &&
        file_exists($image)
    ) {

        unlink($image);

    }
}

function placeOrder($pdo, $userId, $address, $paymentMethod)
{
    try {

        $items = getCartItems($pdo);

        if (empty($items)) {
            return false;
        }

        $total = cartTotal($pdo);

        $orderNo = "ORD" . date("YmdHis");

        $pdo->beginTransaction();

        $stmt = $pdo->prepare(

            "INSERT INTO orders
            (
                order_no,
                user_id,
                total,
                payment_method,
                address
            )

            VALUES
            (?,?,?,?,?)"

        );

        $stmt->execute([

            $orderNo,
            $userId,
            $total,
            $paymentMethod,
            $address

        ]);

        $orderId = $pdo->lastInsertId();

        $itemStmt = $pdo->prepare(

            "INSERT INTO order_items
            (
                order_id,
                product_id,
                quantity,
                price
            )

            VALUES
            (?,?,?,?)"

        );

        foreach ($items as $item) {

            $itemStmt->execute([

                $orderId,
                $item["id"],
                $item["quantity"],
                $item["price"]

            ]);

        }

        $pdo->commit();

        clearCart();

        return $orderNo;

    } catch (Exception $e) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return false;
    }
}

function getOrders($pdo)
{
    $stmt = $pdo->query(

        "SELECT
            orders.*,
            users.name

         FROM orders

         LEFT JOIN users

         ON orders.user_id = users.id

         ORDER BY orders.id DESC"

    );

    return $stmt->fetchAll();
}

function getOrderItems($pdo, $orderId)
{
    $stmt = $pdo->prepare(

        "SELECT
            order_items.*,
            products.name,
            products.image

         FROM order_items

         LEFT JOIN products

         ON order_items.product_id = products.id

         WHERE order_items.order_id = ?"

    );

    $stmt->execute([$orderId]);

    return $stmt->fetchAll();
}
