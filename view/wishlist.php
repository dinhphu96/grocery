<?php


$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

?>

<section class="wishlist">

   <h1 class="title">products added</h1>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
      $select_wishlist->execute([$user_id]);
      if($select_wishlist->rowCount() > 0){
         while($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="index.php?action=wishlist" method="POST" class="box">
      <a href="index.php?action=wishlist&delete=<?= $fetch_wishlist['id']; ?>" class="fas fa-times" onclick="return confirm('delete this from wishlist?');"></a>
      <a href="index.php?action=view_page&pid=<?= $fetch_wishlist['pid']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_wishlist['image']; ?>" alt="">
      <div class="name"><?= $fetch_wishlist['name']; ?></div>
      <div class="price">$<?= $fetch_wishlist['price']; ?>/-</div>
      <input type="number" min="1" value="1" class="qty" name="p_qty">
      <input type="hidden" name="pid" value="<?= $fetch_wishlist['pid']; ?>">
      <input type="hidden" name="p_name" value="<?= $fetch_wishlist['name']; ?>">
      <input type="hidden" name="p_price" value="<?= $fetch_wishlist['price']; ?>">
      <input type="hidden" name="p_image" value="<?= $fetch_wishlist['image']; ?>">
      <input style="font-size: 15px;" type="submit" value="add to cart" name="add_to_cart" class="btn btn-info">
   </form>
   <?php
      $grand_total += $fetch_wishlist['price'];
      }
   }else{
      echo '<p class="empty">your wishlist is empty</p>';
   }
   ?>
   </div>

   <div class="wishlist-total">
      <p>grand total : <span>$<?= $grand_total; ?>/-</span></p>
      <a href="shop.php" class="option-btn">continue shopping</a>
      <a href="index.php?action=wishlist&delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>">delete all</a>
   </div>

</section>
