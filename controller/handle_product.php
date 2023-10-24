<?php

@include '../model/config.php';

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location: ./view/login.php');
};


if (isset($_POST['add_to_wishlist'])) {

    if (isset($_POST['pid'])) {

        $pid = $_POST['pid'];
        $pid = filter_var($pid, FILTER_SANITIZE_SPECIAL_CHARS);
        $p_name = $_POST['p_name'];
        $p_name = filter_var($p_name, FILTER_SANITIZE_SPECIAL_CHARS);
        $p_price = $_POST['p_price'];
        $p_price = filter_var($p_price, FILTER_SANITIZE_SPECIAL_CHARS);
        $p_image = $_POST['p_image'];
        $p_image = filter_var($p_image, FILTER_SANITIZE_SPECIAL_CHARS);

        $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
        $check_wishlist_numbers->execute([$p_name, $user_id]);


        if ($check_wishlist_numbers->rowCount() > 0) {
            $message = 'Already exists in the wishlist!';
            header("Location: ./index.php?message=" . $message);
        } else {
            $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
            $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
            $message = 'Added to wishlist!';
            header("Location: ./index.php?message=" . $message);
        }
    }
}


if (isset($_POST['add_to_cart'])) {

    $pid = $_POST['pid'];
    $pid = filter_var($pid, FILTER_SANITIZE_SPECIAL_CHARS);
    $p_name = $_POST['p_name'];
    $p_name = filter_var($p_name, FILTER_SANITIZE_SPECIAL_CHARS);
    $p_price = $_POST['p_price'];
    $p_price = filter_var($p_price, FILTER_SANITIZE_SPECIAL_CHARS);
    $p_image = $_POST['p_image'];
    $p_image = filter_var($p_image, FILTER_SANITIZE_SPECIAL_CHARS);


    $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
    $check_cart_numbers->execute([$p_name, $user_id]);

    if ($check_cart_numbers->rowCount() > 0) {
        $message = 'Already exists in the shopping cart!';
        header("Location: ./index.php?message=" . $message);
    } else {

        $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
        $check_wishlist_numbers->execute([$p_name, $user_id]);

        $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
        $insert_cart->execute([$user_id, $pid, $p_name, $p_price, 1, $p_image]);
        $message = 'Added to cart!';
        header("Location:./index.php?message=" . $message);
    }
}


if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
    $delete_cart_item->execute([$delete_id]);
    header('location: ./index.php?action=cart');
}

if (isset($_GET['delete_all'])) {
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart_item->execute([$user_id]);
    header('location: ./index.php?action=cart');
}

if (isset($_POST['update_qty'])) {
    $cart_id = $_POST['cart_id'];
    $p_qty = $_POST['p_qty'];
    $p_qty = filter_var($p_qty, FILTER_SANITIZE_SPECIAL_CHARS);
    $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
    $update_qty->execute([$p_qty, $cart_id]);
    $message = 'Cart quantity updated';
    header('location: ./index.php?action=cart&message=' . $message);
}


if (isset($_POST['order'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_SPECIAL_CHARS);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_SPECIAL_CHARS);
    $method = $_POST['method'];
    $method = filter_var($method, FILTER_SANITIZE_SPECIAL_CHARS);
    $address = 'flat no. ' . $_POST['flat'] . ' ' . $_POST['street'] . ' ' . $_POST['city'] . ' ' . $_POST['state'] . ' ' . $_POST['country'] . ' - ' . $_POST['pin_code'];
    $address = filter_var($address, FILTER_SANITIZE_SPECIAL_CHARS);
    $placed_on = date('d-M-Y');

    $cart_total = 0;
    $cart_products[] = '';

    $cart_query = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
    $cart_query->execute([$user_id]);
    if ($cart_query->rowCount() > 0) {
        while ($cart_item = $cart_query->fetch(PDO::FETCH_ASSOC)) {
            $cart_products[] = $cart_item['name'] . ' ( ' . $cart_item['quantity'] . ' )';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        };
    };

    $total_products = implode(', ', $cart_products);

    $order_query = $conn->prepare("SELECT * FROM `orders` WHERE name = ? AND number = ? AND email = ? AND method = ? AND address = ? AND total_products = ? AND total_price = ?");
    $order_query->execute([$name, $number, $email, $method, $address, $total_products, $cart_total]);

    if ($cart_total == 0) {
        $message[] = 'your cart is empty';
    } elseif ($order_query->rowCount() > 0) {
        $message[] = 'order placed already!';
    } else {
        $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES(?,?,?,?,?,?,?,?,?)");
        $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $cart_total, $placed_on]);
        $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
        $delete_cart->execute([$user_id]);
        $message = 'order placed successfully!';
        header('location: ./index.php?action=order&message=' . $message);
    }
}
