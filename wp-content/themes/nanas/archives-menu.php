<?php 

/*

Template Name: Menu Archives

4

*/



get_header();

?>

 <!-- End Here Request Sec -->

    <section class="foodmenu_sec">

      <div class="overlay"></div>

      <div class="container">

        <div class="row">

          <div class="col-md-12"> 

            <!-- End Here -->

            <div class="style-heading">

              <h1><?php _e('Food menu'); ?></h1>

            </div>

          </div>

          <!-- End Here -->

          

          <div class="col-md-12 col-sm-12">

            <div class="food_item">

         

									  

					<div class="food_item regular slider">

							<?php

							$taxonomy = 'menu_taxo';

								$terms = get_terms(array(

									'taxonomy' => $taxonomy,

									'hide_empty' => true,

								) ); // Get all terms of a taxonomy

								if ( $terms && !is_wp_error( $terms ) ) :

								foreach ( $terms as $term ) { 

								?>



							<div class="list_sec">

								 <a href="#<?php echo $term->slug; ?>" class="page-scroll"><img src="<?php echo z_taxonomy_image_url( $term->term_id, 'medium', TRUE ) ?>" alt="<?php echo $term->name; ?>" >

												<h3><?php echo $term->name; ?></h3>

								 </a>

							</div >

								<?php 

								} 

								endif;

								?>

					</div>

              

              

            </div>

          </div>

          <!-- End Here --> 

        </div>

      </div>

      <div class="clr"></div>

    </section>

    <!-- End Here -->

	

		<?php
		$i = 0;
			$taxonomy = 'menu_taxo';

				$terms = get_terms(array(

					'taxonomy' => $taxonomy,

					'hide_empty' => true,

					) ); // Get all terms of a taxonomy

		if ( $terms && !is_wp_error( $terms ) ) :

		foreach ( $terms as $term ) { 				

		?>

    

    <section class="foodmenu-content" id="<?php echo $term->slug; ?>">

      <div class="container">

        <div class="col-sm-12">

          <div class="foodmenu_slect">

            <div class="startrs-head">

              <div class="food_bg"> <img src="<?php bloginfo('template_url'); ?>/images/title-banner.png" alt=""> </div>

              <h1><?php echo $term->name; ?></h1>

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

                    'terms' => $term->slug

                )

            ),

            'post_type' => 'menu',

            'orderby' => 'title,'

        );

        $products = new WP_Query( $args );

		 while ( $products->have_posts() ) {

            $products->the_post();

		$pid = get_the_ID(); 

		 $content = substr( get_the_content(),0, 100);

		$content = apply_filters('the_content', $content);

		$content = str_replace(']]>', ']]>', $content); 

		 $tumb = get_the_post_thumbnail_url($pid);

		 $img = get_template_directory_uri()."/images/No_image.png";

		 $image = $tumb?$tumb:$img;

		  ?>

            <div class="menu_outer">

              <div class="menu_img"> <a href="#"><img src="<?php echo $image; ?>" alt="<?php the_title(); ?>"> </a></div>

              <div class="about_menu">

                <h5><?php the_title(); ?>  <?php _e('ONLY....'); ?>&#x00024;<?php echo get_post_meta($pid,'menu_price',true); ?></h5>

				<div class="section-content menus_lst<?php echo $i; ?>"><?php
					echo $content;
					?>
				<span class="more readmore<?php echo $i; ?>" data-val="0" id="m<?php echo $i; ?>">Read More</span>	
				</div>
				<div class="section-content menus_lst1<?php echo $i; ?>" style="display:none;"><?php
					echo get_the_content();
					?>
				<span class="less readmore<?php echo $i; ?>" data-val="1" id="m<?php echo $i; ?>">Less</span>	
				</div>
				
				<script>
					jQuery(document).ready(function(){
						
					jQuery(".readmore<?php echo $i; ?>").click(function(e){
					   e.preventDefault();
					   var val= jQuery(this).attr("data-val");
					   if(val=="1"){
						jQuery(".menus_lst1<?php echo $i; ?>").hide(); 
						jQuery(".menus_lst<?php echo $i; ?>").show(500);
					   }else{
						jQuery(".menus_lst<?php echo $i; ?>").hide(500);
						jQuery(".menus_lst1<?php echo $i; ?>").show();    
					   }
					});	

				
					});
				</script>

              </div>

            </div>

            <!-- End Here -->

		 <?php 
		 $i++;
		 } ?>	

          

          </div>

        </div>

      </div>

    </section>

    <!-- End Here -->

    <?php 

		} 

	endif;

	?>

    <section class="foodmenu-check">

      <div class="container">

        <div class="col-md-6 col-sm-6">

		<?php 

		$desert = get_term_by('slug', 'desert', 'menu_taxo');

		$drink = get_term_by('slug', 'drinks', 'menu_taxo');

		?>

          <div class="btn-sec"> <a href="<?php echo get_category_link($desert->term_id) ?>" class="pull-right"><?php _e('View Desert Menu'); ?> </a> </div>

        </div>

        <div class="col-md-6 col-sm-6">

          <div class="btn-sec"> <a href="<?php echo get_category_link($drink->term_id) ?>" class="pull-left"><?php _e('View Drinks Menu'); ?></a> </div>

        </div>

      </div>
	  

    </section>


<style>
.about_menu p { height: 50px;
overflow: hidden;
}
</style>

<?php get_footer();?>