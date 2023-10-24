<?php
@include 'config.php';

if (isset($_GET['message'])) {

   echo '
   <div style="font-size:18px" class="message alert alert-primary" role="alert">'
      . $_GET['message'] .
      '<i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>';
}

?>

<header class="header">

   <div class="flex">

      <a href="index.php?action=admin_page" class="logo">Groco<span>.</span></a>

      <nav class="navbar">
         <a href="index.php">Home</a>
         <a href="index.php?action=shop">Shop</a>
         <a href="index.php?action=order">Orders</a>
         <a href="index.php?action=about">About</a>
         <a href="index.php?action=contact">Contact</a>
      </nav>

      <div class="icons">
         <div id="menu-btn" class="fas fa-bars"></div>
         <div id="user-btn" class="fas fa-user"></div>
         <a href="index.php?action=search_page" class="fas fa-search"></a>
         <?php
         $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $count_cart_items->execute([$user_id]);
         $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
         $count_wishlist_items->execute([$user_id]);
         ?>
         <a href="index.php?action=wishlist"><i class="fas fa-heart"></i><span>(<?= $count_wishlist_items->rowCount(); ?>)</span></a>
         <a href="index.php?action=cart"><i class="fas fa-shopping-cart"></i><span>(<?= $count_cart_items->rowCount(); ?>)</span></a>
      </div>

      <div class="profile">
         <?php
         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
         $select_profile->execute([$user_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
         <p><?= $fetch_profile['name']; ?></p>
         <a href="index.php?action=user_profile_update" class="btn">update profile</a>
         <a href="index.php?action=logout" class="delete-btn">logout</a>
         <div class="flex-btn">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
      </div>

   </div>

</header>