<?php

@include '../model/config.php';

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
  header('location: ./view/login.php');
};

if (isset($_POST['add_to_cart'])) {

  $pid = $_POST['pid'];
  $pid = filter_var($pid, FILTER_SANITIZE_SPECIAL_CHARS);
  $p_name = $_POST['p_name'];
  $p_name = filter_var($p_name, FILTER_SANITIZE_SPECIAL_CHARS);
  $p_price = $_POST['p_price'];
  $p_price = filter_var($p_price, FILTER_SANITIZE_SPECIAL_CHARS);
  $p_image = $_POST['p_image'];
  $p_image = filter_var($p_image, FILTER_SANITIZE_SPECIAL_CHARS);
  $p_qty = $_POST['p_qty'];
  $p_qty = filter_var($p_qty, FILTER_SANITIZE_SPECIAL_CHARS);

  $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
  $check_cart_numbers->execute([$p_name, $user_id]);

  if ($check_cart_numbers->rowCount() > 0) {
    $message = 'Already added to cart!';
  } else {

    $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
    $check_wishlist_numbers->execute([$p_name, $user_id]);

    if ($check_wishlist_numbers->rowCount() > 0) {
      $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
      $delete_wishlist->execute([$p_name, $user_id]);
    }

    $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
    $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
    $message = 'Added to cart!';
  }

  header('location: ./index.php?action=wishlist&message='.$message);
} else if (isset($_GET['delete'])) {
  $delete_id = $_GET['delete'];
  $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist` WHERE id = ?");
  $delete_wishlist_item->execute([$delete_id]);
  header('location: ./index.php?action=wishlist');
} else if (isset($_GET['delete_all'])) {

  $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
  $delete_wishlist_item->execute([$user_id]);
  header('location: ./index.php?action=wishlist');
} else {
  include "./view/wishlist.php";
}
