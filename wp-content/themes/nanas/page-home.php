<?php
/**
* Template Name: Home
*/
 get_header(); ?>
<section class="st-slider">
	<div id="myCarousel" class="carousel slide" data-ride="carousel">
		<!-- Indicators -->
		<ol class="carousel-indicators">
		<?php 
		$in = 0 ;
			if( have_rows('banner_slider') ):
			// loop through the rows of data
			while( have_rows('banner_slider') ): the_row();
		?>
			<li data-target="#myCarousel" data-slide-to="<?php echo $in; ?>" class="<?php if($in==0){ echo "active"; }?>"></li>
		<?php
		$in++;
		   endwhile;
		endif;
		?>	
		</ol>
		<!-- Wrapper for slides -->
		<div class="carousel-inner">
		<?php 
		$in = 0 ;
			if( have_rows('banner_slider') ):
			// loop through the rows of data
			while( have_rows('banner_slider') ): the_row();
			$in++;
			$img = get_sub_field('image');
		?>
			<div class="item <?php if($in==1){ echo "active"; }?>">
				<img src="<?php echo $img; ?>" alt="Slider">
				<div class="carousel-caption">
					<div class="logo_outer">
						<img src="<?php echo  get_option('nt_footerlogo'); ?>" alt="Logo">
					</div>
					<a href="<?php echo esc_url( home_url( '/menus' ) ); ?>" class="btn btn-default btn-st"><?php _e('Discover Our Place'); ?></a>
				</div>
			</div><!-- First Slide End Here -->
		<?php
		   endwhile;
		endif;
		?>		
		</div>
	</div>
	<div class="clr"></div>
</section><!-- Slider Section End Here -->
<section class="home_menu">
	<div class="container">
		<div class="row">
			<div class="col-md-12 no-padding">
				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="menu_outer">
						<div class="style-heading">
							<h1><?php _e('Food'); ?></h1>
							<?php 
							$thisCat = get_term_by('slug', 'food', 'menu_taxo');
							?>
						</div><!-- Heading Here -->
						<div class="menu_img">
							<img src="<?php bloginfo('template_url'); ?>/images/menu-one.jpg" alt="<?php echo $thisCat->name; ?>">
							<div class="overlay">
								<a href="<?php echo get_category_link($thisCat->term_id) ?>">></a>
							</div>
						</div><!-- Image Here -->
						<div class="about_menu">
							<p><?php echo substr($thisCat->description, 0, 150); ?></p>
							<a href="<?php echo get_category_link($thisCat->term_id) ?>"><?php _e('View Menu'); ?></a>
						</div><!-- Content Here -->
					</div>
				</div><!-- Col End Here -->
				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="menu_outer">
						<div class="style-heading">
							<h1><?php _e('Desert'); ?></h1>
						</div><!-- Heading Here -->
						<?php 
							$DesertCat = get_term_by('slug', 'desert', 'menu_taxo');
							?>
						<div class="menu_img">
							<img src="<?php bloginfo('template_url'); ?>/images/menu-two.jpg" alt="<?php echo $DesertCat->name; ?>">
							<div class="overlay">
								<a href="<?php echo get_category_link($DesertCat->term_id) ?>">></a>
							</div>
						</div><!-- Image Here -->
						<div class="about_menu">
							<p><?php echo substr($DesertCat->description, 0, 150); ?></p>
							<a href="<?php echo get_category_link($DesertCat->term_id) ?>"><?php _e('View Menu'); ?></a>
						</div><!-- Content Here -->
					</div>
				</div><!-- Col End Here -->
				<div class="col-md-4 col-sm-4 col-xs-12">
					<div class="menu_outer">
						<div class="style-heading">
							<h1><?php _e('Drinks'); ?></h1>
						</div><!-- Heading Here -->
						<?php 
							$DrinksCat = get_term_by('slug', 'drinks', 'menu_taxo');
							?>
						<div class="menu_img">
							<img src="<?php bloginfo('template_url'); ?>/images/menu_three.jpg" alt="<?php echo $DrinksCat->name; ?>">
							<div class="overlay">
								<a href="<?php echo get_category_link($DrinksCat->term_id) ?>">></a>
							</div>
						</div><!-- Image Here -->
						<div class="about_menu">
							<p><?php echo substr($DrinksCat->description, 0, 150); ?></p>
							<a href="<?php echo get_category_link($DrinksCat->term_id) ?>"><?php _e('View Menu'); ?></a>
						</div><!-- Content Here -->
					</div>
				</div><!-- Col End Here -->
			</div>
		</div>
	</div>
	<div class="clr"></div>
</section><!-- End Here Menu Sec -->
<section class="home_about">
	<div class="overlay"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="sm-heading">
					<h6><?php _e('About Us'); ?></h6>
				</div><!-- End Here -->
				<div class="style-heading">
					<h1><?php echo get_field('about_us_section_title'); ?></h1>
				</div><!-- End Here -->
				<div class="sec_text">
					<p><?php echo get_field('about_description'); ?></p>
				</div><!-- End Here -->
				<div class="btn-sec">
					<a href="<?php echo esc_url( home_url( '/about-us' ) ); ?>"><?php _e('Find Out More'); ?></a>
				</div><!-- End Here -->
			</div>
		</div>
	</div>
	<div class="clr"></div>
</section><!-- End Here -->
<section class="event_sec">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="sm-heading">
					<h6><?php _e('Check Out Our'); ?></h6>
				</div><!-- End Here -->
				<div class="style-heading">
					<h1><?php _e('Specials & Events'); ?></h1>
				</div><!-- End Here -->
			</div>
		</div><!-- End Here -->
		<div class="clr"></div>
		<div class="row">
			<div class="col-md-12 no-padding">
				<div class="col-md-9 col-sm-9">
				<?php
			
				$args = array('post_type' => 'menu','tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'menu_taxo',
                    'field' => 'slug',
                    // 'terms' => 'white-wines'
                    'terms' =>'specials-events'
                ))
 ,'posts_per_page' => 3);
								$loop = new WP_Query( $args );
							
								 while ( $loop->have_posts() ) : $loop->the_post(); 
								 $id = get_the_ID();
								 $content = substr( get_the_content(),0, 250);
									$content = apply_filters('the_content', $content);
									$content = str_replace(']]>', ']]>', $content);

								?>
					<div class="menu_outer">
						<div class="menu_img">
							<img src="<?php echo get_the_post_thumbnail_url($id); ?>" alt="<?php the_title(); ?>">
						</div>
						<div class="about_menu">
							<h5><?php the_title(); ?> ONLY $<?php echo  get_post_meta($id,'menu_price',true); ?></h5>
							<?php echo $content;  ?>
						</div>
					</div><!-- End Here -->
				<?php
					endwhile; 
						wp_reset_postdata();
				?>	
					
				</div>
				<div class="col-md-3 col-sm-3">
					<div class="side_event">
						<div class="top_text">
							<h6>MAKE RESERVATIONS</h6>
							<p>If you would like to reserve a<br />table please call us at:</p>
						</div>
						<div class="middle_sec">
							<h1><?php echo get_option('nt_phone'); ?></h1>
						</div>
						<div class="bottom-sec">
							<p>...or reserve it online by filling<br /> in the following form:</p>
							<a href="<?php echo esc_url( home_url( '/reservations' ) ); ?>">Fill In Form</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clr"></div>
		<div class="row">
			<div class="col-md-12">
				<div class="btn-sec">
					<a href="<?php echo esc_url( home_url( '/about-us' ) ); ?>"><?php _e('Find Out More'); ?></a>
				</div>
			</div>
		</div>
	</div>
	<div class="clr"></div>
</section><!-- End Here -->
<section class="portfolio_sec">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 no-padding">
				<div class="main_port">
					<div class="port_outer">
						<?php 
						 $i = 0;
							if( have_rows('gallery') ):
							// loop through the rows of data
							while( have_rows('gallery') ): the_row();
							$i++;
							$img = get_sub_field('image');
						if( $i >6 )
							{
								break;
							}
						
						?>
						<img src="<?php echo $img; ?>" alt="">
						<?php
					
							endwhile;
									endif;
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="clr"></div>
</section><!-- End Here -->
<section class="testimonial_sec">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="sm-heading">
					<h6><?php _e('Testimonials From Our'); ?></h6>
				</div><!-- End Here -->
				<div class="style-heading">
					<h1><?php _e('Customers & Friends'); ?></h1>
				</div><!-- End Here -->
			</div>
		</div><!-- End Here -->
		<div class="row">
			<div class="col-md-12">
				<ul class="bxslider">
					<?php
								$args = array( 'post_type' => 'testimonial', 'posts_per_page' => -1);
								$loop = new WP_Query( $args );
								 while ( $loop->have_posts() ) : $loop->the_post(); 
								 $id = get_the_ID();
								?>
					<li>
						<div class="testimonial_text">
						<?php the_content();?>
						</div>
						<div class="testimonial_meta">
							<h3><?php echo get_post_meta($id,'testimonial_author',true);?></h3>
							<em>review from <span>Around Here</span></em>
						</div>
					</li><!-- Slide End Here -->
					<?php 
						endwhile; 
						wp_reset_postdata();
					?>
			
				</ul>
			</div>
		</div><!-- End Here -->
	</div>
	<div class="clr"></div>
</section><!-- End Here -->
<section class="three_col">
	<div class="container">
		<div class="row">
			<div class="col-md-12 no-padding">
				<div class="col-md-4 col-sm-4">
					<div class="style-heading">
						<h1><?php _e('Want to contact us?'); ?></h1>
					</div>
					<div class="col-title">
						<h5><?php _e('Send us Feedback'); ?></h5>
					</div>
					<div class="col-text">
						<p><?php echo get_field('send_us_feedback'); ?></p>
					</div>
					<div class="btn-sec">
						<a href="<?php echo esc_url( home_url( '/contact-us' ) ); ?>"><img src="<?php bloginfo('template_url'); ?>/images/read-more.png"></a>
					</div>
				</div>
				<div class="col-md-4 col-sm-4">
					<div class="style-heading">
						<h1><?php _e('From the blog'); ?></h1>
					</div>
					<div class="col-title">
						<h5><?php _e('LATEST BLOG ARTICLES'); ?></h5>
					</div>
					<div class="col-text">
						<p><?php echo get_field('latest_blog_articles'); ?></p>
					</div>
					<div class="btn-sec">
						<a href="<?php echo esc_url( home_url( '/blog' ) ); ?>"><img src="<?php bloginfo('template_url'); ?>/images/read-more.png"></a> 
					</div>
				</div>
				<div class="col-md-4 col-sm-4">
					<div class="style-heading">
						<h1><?php _e('Stay in the loop'); ?></h1>
					</div>
					<div class="col-title">
						<h5><?php _e('ABOUT US'); ?></h5>
					</div>
					<div class="col-text">
						<p><?php echo get_field('about_us'); ?></p>
					</div>
					<div class="btn-sec">
						<a href="<?php echo esc_url( home_url( '/about-us' ) ); ?>"><img src="<?php bloginfo('template_url'); ?>/images/read-more.png"></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="clr"></div>
</section><!-- End Here -->
<?php get_footer(); ?>