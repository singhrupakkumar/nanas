<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>
</section>
<footer class="st_footer">
	<div class="container">
		<div class="row">
			<div class="col-md-12 no-padding">
				<div class="col-md-3 col-sm-3">
					<div class="col-outer">
						<div class="foot_title">
							<h2><?php _e('ABOUT US'); ?></h2>
						</div>
						<div class="col_inner">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
								<img src="<?php echo  get_option('nt_footerlogo'); ?>" alt="Foot Logo">
							</a>
						</div>
					</div>
				</div><!-- End Here -->
				<div class="col-md-3 col-sm-3">
					<div class="col-outer">
						<div class="foot_title">
							<h2><?php _e('LATEST POSTS'); ?></h2>
						</div>
						<div class="col_inner">
							<ul class="blog_post"> 
								<?php
								$args = array( 'post_type' => 'post','orderby'=>'post_date','order'=>'DESC','posts_per_page' => 3);
								$loop = new WP_Query( $args );
								 while ( $loop->have_posts() ) : $loop->the_post(); 
								 $id = get_the_ID();
								?>
		
				
							<li>
									<div class="post_img">
										<img src="<?php echo get_the_post_thumbnail_url($id); ?>" title="Image">
										<div class="overlay">
											<a href="JavaScript:Void(0);">></a>
										</div>
									</div>
									<div class="post_text">
										<?php the_title(); ?></br>
										<span><?php echo get_the_date(); ?></span>
									</div>
							</li>
							<?php 
								endwhile; 
								wp_reset_postdata();
							?>
							</ul>
						</div>
					</div>
				</div><!-- End Here -->
				<div class="col-md-3 col-sm-3">
					<div class="col-outer">
						<div class="foot_title">
							<h2><?php _e('Fresh Comments'); ?></h2>
						</div>
						<div class="col_inner">
							<ul class="recentcomments">
<?php $args = array(
	'author_email' => '',
	'author__in' => '',
	'author__not_in' => '',
	'include_unapproved' => '',
	'fields' => '',
	'ID' => '',
	'comment__in' => '',
	'comment__not_in' => '',
	'karma' => '',
	'number' => '',
	'offset' => '',
	'orderby' => '',
	'order' => 'DESC',
	'parent' => '',
	'post_author__in' => '',
	'post_author__not_in' => '',
	'post_ID' => '', // ignored (use post_id instead)
	'post_id' => 0,
	'post__in' => '',
	'post__not_in' => '',
	'post_author' => '',
	'post_name' => '',
	'post_parent' => '',
	'post_status' => '',
	'post_type' => 'menu',
	'status' => 'all',
	'type' => '',
        'type__in' => '',
        'type__not_in' => '',
	'user_id' => '',
	'search' => '',
	'count' => false,
	'meta_key' => '',
	'meta_value' => '',
	'meta_query' => '',
	'date_query' => null, // See WP_Date_Query
);
$comments = get_comments( $args ); 

foreach($comments as $comment) :
?>

								<li>
									<span><?php echo($comment->comment_author); ?></span><sub>On</sub><a href="JavaScript:Void(0);"><?php echo get_the_title($comment->comment_post_ID); ?></a>
								</li>
								<?php 
								endforeach;
								?>
								
							</ul>
						</div>
					</div>
				</div><!-- End Here -->
				<div class="col-md-3 col-sm-3">
					<div class="col-outer">
						<div class="foot_title">
							<h2><?php _e('INSTAGRAM'); ?></h2>
						</div>
						<div class="col_inner">
							<ul class="insta_post">
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
								<li><a href="JavaScript:Void(0);"><figure><img src="<?php echo get_the_post_thumbnail_url($id); ?>" alt="<?php the_title(); ?>"><div class="overlay"><a href="JavaScript:Void(0);">></a>
										</div></figure></a>
								</li>
							<?php
								endwhile; 
								wp_reset_postdata();
							?>
							</ul>
						</div>
					</div>
				</div><!-- End Here -->
			</div>
		</div>
	</div>
	<div class="clr"></div>
</footer><!-- End Here -->
<section class="foot_menu">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
			 <?php wp_nav_menu( array( 'menu' => 'footer',
                    'container_class'=>'menu_outer',
                    'container_id'=>'',
                     'menu_id' => 'menu',
                     'menu_class' => '',

              ) ); ?>
			</div>
		</div>
	</div>
	<div class="clr"></div>
</section><!-- End Here -->
<section class="copyright">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<ul class="social_link">
					<li><a href="<?php echo  get_option('nt_facebook_social_link'); ?>"><i class="fa fa-facebook-square" aria-hidden="true"></i></a></li>
					<li><a href="<?php echo  get_option('nt_twitter_social_link'); ?>"><i class="fa fa-twitter-square" aria-hidden="true"></i></a></li>
					<li><a href="<?php echo  get_option('nt_dribbble_social_link'); ?>"><i class="fa fa-dribbble" aria-hidden="true"></i></a></li>
					<li><a href="<?php echo  get_option('nt_googleplus_social_link'); ?>"><i class="fa fa-google-plus-square" aria-hidden="true"></i></a></li>
					<li><a href="<?php echo  get_option('nt_linkedin_social_link'); ?>"><i class="fa fa-linkedin-square" aria-hidden="true"></i></a></li>
				</ul>
				<p><?php echo  get_option('nt_footer_text'); ?> <?php bloginfo('name'); ?> <?php _e('Made by'); ?> <a href="https://futureworktechnologies.com/"> <?php _e('Future Work Technologies'); ?></a></p>
			</div>
		</div>
	</div>
	<div class="clr"></div>
</section><!-- End Here -->
</main>

<?php wp_footer(); ?>
<script>
 $(document).on('ready', function() {
      $(".regular").slick({
        dots: true,
        infinite: true,
        slidesToShow: 4,
        slidesToScroll: 1,
		 autoplay: true,
  	autoplaySpeed: 2000,
      });
	  
	$('.lang a').each(function(){
		  $(this).click(function(){
		$(".lang a.active").removeClass("active");
			  $(this).addClass('active');
		  });

	});


	  });
</script>
<script type="text/javascript">
$(window).load(function() {
	$(".loader").fadeOut("slow");
	//$( 'a[ title="English"]' ).addClass( 'active' );
	
})
</script>
</body>
</html>