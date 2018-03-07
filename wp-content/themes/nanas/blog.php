<?php 
/**
* Template Name: Blog 
*
*/
get_header();?>
<!-- End Here Request Sec -->
    <div class="blog_sec">
      <div class="overlay"></div>
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="sm-heading">
              <h6><?php _e('Welcome to'); ?></h6>
            </div>
            <!-- End Here -->
            <div class="style-heading">
              <h1><?php _e('The Kitchen Chatter'); ?></h1>
            </div>
          </div>
          <!-- End Here --> 
          
        </div>
      </div>
      <div class="clr"></div>
    </div>
    <!-- End Here -->
    
    <div class="blog_post">
    <div class="container">
      <div class="col-sm-9">
		<?php echo do_shortcode('[blog]'); ?>
      </div>
      <div class="col-sm-3">
        <div class="left_sdbr">
          <h2><?php _e('Search Blog'); ?></h2>
		  
          <div class="search_inpt">
            <div class="icn_serch"></div>
			<form role="form" role="search" method="get">
				<input type="search" class="form-control search_box" name="term" autocomplete="off" placeholder="Search Here">
			<button type="submit" class="search-submit fa fa-search" value="Search">&nbsp;</button>
			</form>
            
          </div>
        </div>
        <div class="archives_sec">
          <h3><?php _e('Categories'); ?></h3>
          <ul>
		  <?php

							$taxonomy = 'category';

								$terms = get_terms(array(

									'taxonomy' => $taxonomy,

									'hide_empty' => true,

								) ); // Get all terms of a taxonomy

								if ( $terms && !is_wp_error( $terms ) ) :

								foreach ( $terms as $term ) { 
							   $args = array(

            'posts_per_page' => -1,

            'tax_query' => array(

                'relation' => 'AND',

                array(

                    'taxonomy' => 'category',

                    'field' => 'slug',

                    // 'terms' => 'white-wines'

                    'terms' => $term->slug

                )

            ),

            'post_type' => 'post',

            'orderby' => 'title,'

        );	
								
					$posts = get_posts($args); 
					$count = count($posts); 
								?>
            <li><a href="javascript:void(0);"><?php echo $term->name; ?> <span>(<?php echo $count; ?>)</span></a></li>
			<?php
			}
			endif;
			?>
     
          </ul>
        </div>
        <div class="freash_sec">
          <h3><?php _e('Fresh Posts'); ?></h3>
		  
          <ul>
		  <?php
			$args = array( 'post_type' => 'post','orderby'=>'post_date','order'=>'DESC','posts_per_page' => 3);
			$loop = new WP_Query( $args );
			 while ( $loop->have_posts() ) : $loop->the_post(); 
				$id = get_the_ID();
			?>
            <li>
              <div class="frsh_lft"> <img src="<?php echo get_the_post_thumbnail_url($id); ?>" alt="<?php the_title(); ?>" width="40" height="40"></div>
              
              <div class="frsh_rght">
              <h4><?php the_title(); ?></h4>
              <span><?php echo get_the_date(); ?></span>
              </div> 
            </li>
            
           <?php 
				endwhile; 
			wp_reset_postdata();
			?>
           
          </ul>
        </div>
        
         <div class="instagm_sec">
         <h3><?php _e('Instagram'); ?></h3>
         <div class="clearfix">
		 <?php
								$args = array('post_type' => 'post','tax_query' => array(
												'relation' => 'AND',
												array(
													'taxonomy' => 'category',
													'field' => 'slug',
													// 'terms' => 'white-wines'
													'terms' =>'instagram'
												))
								 ,'posts_per_page' =>9);
								$loop = new WP_Query( $args );
							
								 while ( $loop->have_posts() ) : $loop->the_post(); 
								 $id = get_the_ID();
								?>
         <div class="insta_box">
         	 <img src="<?php echo get_the_post_thumbnail_url($id); ?>" alt="<?php the_title(); ?>" width="100%" >
         </div>
		 <?php
			endwhile; 
			wp_reset_postdata();
		?>
     
         </div>
         
         </div>
        
      </div>
    </div>
    </div>
<?php get_footer(); ?>	