<div class="home-bg">

   <section class="home">

      <div class="content">
         <span>Don't panic, go organice</span>
         <h3>Reach For A Healthier You With Organic Foods</h3>
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto natus culpa officia quasi, accusantium explicabo?</p>
         <a href="index.php?action=about" class="btn">about us</a>
      </div>

   </section>

</div>

<section class="home-category">

   <h1 class="title">Shop by category</h1>

   <div class="box-container">

      <div class="box">
         <img src="images/cat-1.png" alt="">
         <a href="index.php?action=category&category=fruits" class="btn btn-lg btn-success">Fruits</a>
      </div>

      <div class="box">
         <img src="images/cat-2.png" alt="">
         <a href="index.php?action=category&category=meat" class="btn btn-lg btn-success">Meat</a>
      </div>

      <div class="box">
         <img src="images/cat-3.png" alt="">
         <a href="index.php?action=category&category=vegitables" class="btn btn-lg btn-success">Vegetables</a>
      </div>

      <div class="box">
         <img src="images/cat-4.png" alt="">
         <a href="index.php?action=category&category=fish" class="btn btn-lg btn-success">Fish</a>
      </div>

   </div>

</section>

<section class="products">

   <h1 class="title">Latest products</h1>

   <div class="box-container">

      <?php
      $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
      $select_products->execute();
      if ($select_products->rowCount() > 0) {
         while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
      ?>
            <form action="index.php?action=handle_product" class="box" method="POST">
               <div style="z-index: 100;" class="price">$<span><?= $fetch_products['price']; ?></span></div>
               <!-- <a href="index.php?action=view_page&pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a> -->
               <a class="" href="index.php?action=view_page&pid=<?= $fetch_products['id']; ?>"><img src="uploaded_img/<?= $fetch_products['image']; ?>" alt=""></a>
               <a href="index.php?action=view_page&pid=<?= $fetch_products['id']; ?>"><div class="name"><?= $fetch_products['name']; ?></div></a>
               <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
               <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
               <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
               <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
               <input type="submit" value="add to wishlist" class="btn btn-lg btn-warning" name="add_to_wishlist">
               <input type="submit" value="add to cart" class="btn btn-lg btn-primary" name="add_to_cart">
            </form>
      <?php
         }
      } else {
         echo '<p class="empty">no products added yet!</p>';
      }
      ?>

   </div>

</section>