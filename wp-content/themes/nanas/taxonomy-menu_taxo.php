<?php
get_header();
//single_cat_title();
 $cat = get_term_by('name', single_cat_title('',false), 'menu_taxo');
 $slug = $cat->slug;
?>
<!-- End Here Request Sec -->
    <section class="foodmenu_sec">
      <div class="overlay"></div>
      <div class="container">
        <div class="row">
          <div class="col-md-12"> 
            <!-- End Here -->
            <div class="style-heading">
              <h1><?php single_cat_title(); ?></h1>
            </div>
          </div>
          <!-- End Here -->
         
        </div>
      </div>
      <div class="clr"></div>
    </section>
    <!-- End Here -->

    <section class="foodmenu-content">
      <div class="container">
        <div class="col-sm-12">
          <div class="foodmenu_slect">
            <div class="startrs-head">
              <div class="food_bg"> <img src="<?php bloginfo('template_url'); ?>/images/title-banner.png" alt=""> </div>
              <h1><?php single_cat_title(); ?></h1>
            </div>
          </div>
          <div class="col-md-9 col-sm-9 col-sm-offset-2">
		  <?php
		     $args = array(
            'posts_per_page' => -1,
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'menu_taxo',
                    'field' => 'slug',
                    // 'terms' => 'white-wines'
                    'terms' => $slug
                )
            ),
            'post_type' => 'menu',
            'orderby' => 'title,'
        );
        $products = new WP_Query( $args );
		 while ( $products->have_posts() ) {
            $products->the_post();
		$pid = get_the_ID(); 
		$content = substr( get_the_content(),0, 200);
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]>', $content);
		 $tumb = get_the_post_thumbnail_url($pid);
		 $img = get_template_directory_uri()."/images/No_image.png";
		 $image = $tumb?$tumb:$img;
		  ?>
            <div class="menu_outer">
              <div class="menu_img"> <a href="<?php echo get_post_permalink($pid); ?>"><img src="<?php echo $image; ?>" alt="<?php the_title(); ?>"> </a></div>
              <div class="about_menu">
                <h5><?php the_title(); ?>  <?php _e('ONLY.................................$'); ?><?php echo get_post_meta($pid,'menu_price',true); ?></h5>
				<?php echo $content; ?>
              </div>
            </div>
            <!-- End Here -->
		 <?php } ?>	
          
          </div>
        </div>
      </div>
    </section>
    <!-- End Here -->
<?php get_footer(); ?>