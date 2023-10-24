<?php
$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
   header('location: ./view/login.php');
};
?>

<section class="shopping-cart">

   <h1 class="title">Products added</h1>
   <div class="container">
      <div class="row">
         <div class="col-lg-12 col-md-12 col-sm-12">
            <div style="font-size: 17px;" class="shoping__cart__table">

               <?php
               $grand_total = 0;
               $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
               $select_cart->execute([$user_id]);
               if ($select_cart->rowCount() > 0) {
               ?>
                  <table class="table">
                     <thead>
                        <tr>
                           <th></th>
                           <th class="float-left">Products</th>
                           <th>Price</th>
                           <th class="text-center">Quantity</th>
                           <th>Total</th>
                           <th class="text-center">Remove</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                        while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                           <form action="index.php?action=handle_product" method="POST" class="box">
                              <tr>
                                 <td style="width:6%;">
                                    <input hidden name="cart_id" value="<?= $fetch_cart['id']; ?>" type="number" min="1" />
                                    <img style="width: 100%;height: 55px;" class="img-fluid" src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="" />
                                 </td>
                                 <td style="width: 20%;padding-top: 2%;">
                                    <a style="text-decoration: none;" href="index.php?action=view_page&pid=<?= $fetch_cart['pid']; ?>">
                                       <h5 class="float-left pl-3"><?= $fetch_cart['name']; ?></h5>
                                    </a>
                                 </td>
                                 <td style="padding-top: 2%;" class="shoping__cart__price">$<?= $fetch_cart['price']; ?></td>
                                 <td class="shoping__cart__quantity text-center">
                                    <input style="width: 20%;" type="number" min="1" value="<?= $fetch_cart['quantity']; ?>" class="qty" name="p_qty" />
                                    <input style="width: 25%;font-size: 15px;" type="submit" value="update" name="update_qty" class="btn btn-warning" />
                                 </td>
                                 <td class="shoping__cart__total">
                                    <?php
                                    echo '$' . $fetch_cart['price'] * $fetch_cart['quantity'];
                                    ?>
                                 </td>
                                 <td class="mx-auto">
                                    <a style="font-size: 15px;" href="index.php?action=handle_product&delete=<?= $fetch_cart['id']; ?>" onclick="return confirm('Delete this from cart?');" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a>
                                 </td>
                              </tr>
                           </form>
                     <?php
                           $grand_total += $fetch_cart['price'] * $fetch_cart['quantity'];
                        }
                     } else {
                        echo '<p class="empty">your cart is empty</p>';
                     }
                     ?>
                     </tbody>
                  </table>
            </div>
         </div>
      </div>
   </div>

   <div class="cart-total">
      <p>Grand total : <span>$<?= $grand_total; ?></span></p>
      <a style="text-decoration: none;" href="./index.php" class="option-btn">continue shopping</a>
      <a style="text-decoration: none;" href="./index.php?action=handle_product&delete_all" class="delete-btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>">delete all</a>
      <a style="font-size: 20px;" href="./index.php?action=checkout" class="btn <?= ($grand_total > 0) ? '' : 'disabled'; ?>">proceed to checkout</a>
   </div>

</section>