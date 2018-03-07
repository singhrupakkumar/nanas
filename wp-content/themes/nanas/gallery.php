<?php
/**
* Template Name: Gallery
*/
 get_header();?> 
 <!-- End Here Request Sec -->
    <section class="gallery_sec">
      <div class="overlay"></div>
      <div class="container">
        <div class="row">
          <div class="col-md-12"> 
            <!-- End Here -->
            <div class="sm-heading">
              <h6><?php _e('Check Out Our'); ?></h6>
            </div>
            <div class="style-heading">
              <h1><?php _e('Image Gallery'); ?></h1>
            </div>
          </div>
          <!-- End Here --> 
        </div>
      </div>
      <div class="clr"></div>
    </section>
    <!-- End Here -->

    <section class="gallery-list">
      <div class="container">
        <div class="row">
          <div class="glry_list">
            <div class="itm_list" >
		<?php 
		$food = get_term_by('slug', 'food', 'menu_taxo');
		$drinks = get_term_by('slug', 'drinks', 'menu_taxo');
		$desert = get_term_by('slug', 'desert', 'menu_taxo');
		$location = get_term_by('slug', 'location', 'category');
		$staff = get_term_by('slug', 'staff', 'category');
		?>
            <button class="btn btn-default filter-button" data-filter="all">All</button>
            <button class="btn btn-default filter-button" data-filter="<?php echo $desert->slug; ?>"><?php echo $desert->name; ?></button>
            <button class="btn btn-default filter-button" data-filter="<?php echo $drinks->slug; ?>"><?php echo $drinks->name; ?></button>
            <button class="btn btn-default filter-button" data-filter="<?php echo $food->slug; ?>"><?php echo $food->name; ?></button>
            <button class="btn btn-default filter-button" data-filter="<?php echo $location->slug; ?>"><?php echo $location->name; ?></button>
            <button class="btn btn-default filter-button" data-filter="<?php echo $staff->slug; ?>"><?php echo $staff->name; ?></button>
         </div>
        </div>
        <br/>
			<?php 
			if( have_rows('gallery','menu_taxo_' . $desert->term_id) ):
			// loop through the rows of data
			while( have_rows('gallery','menu_taxo_' . $desert->term_id) ): the_row();
			$img = get_sub_field('image');
		   ?>
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter <?php echo $desert->slug; ?>">
                <img src="<?php echo $img;?>" class="img-responsive">
            </div>
			<?php
		   endwhile;
		endif;
		?>
		
			<?php 
			if( have_rows('gallery','menu_taxo_' . $drinks->term_id) ):
			// loop through the rows of data
			while( have_rows('gallery','menu_taxo_' . $drinks->term_id) ): the_row();
			$img = get_sub_field('image');
		   ?>
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter <?php echo $drinks->slug; ?>">
                <img src="<?php echo $img;?>" class="img-responsive">
            </div>
			<?php
		   endwhile;
		endif;
		?>
		
			<?php 
			if( have_rows('gallery','menu_taxo_' . $food->term_id) ):
			// loop through the rows of data
			while( have_rows('gallery','menu_taxo_' . $food->term_id) ): the_row();
			$img = get_sub_field('image');
		   ?>
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter <?php echo $food->slug; ?>">
                <img src="<?php echo $img;?>" class="img-responsive">
            </div>
			<?php
		   endwhile;
		endif;
		?>
		
			<?php 
			if( have_rows('gallery','category_' . $location->term_id) ):
			// loop through the rows of data
			while( have_rows('gallery','category_' . $location->term_id) ): the_row();
			$img = get_sub_field('image');
		   ?>
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter <?php echo $location->slug; ?>">
                <img src="<?php echo $img;?>" class="img-responsive">
            </div>
			<?php
		   endwhile;
		endif;
		?>
		
				<?php 
			if( have_rows('gallery','category_' . $staff->term_id) ):
			// loop through the rows of data
			while( have_rows('gallery','category_' . $staff->term_id) ): the_row();
			$img = get_sub_field('image');
		   ?>
            <div class="gallery_product col-lg-4 col-md-4 col-sm-4 col-xs-6 filter <?php echo $staff->slug; ?>">
                <img src="<?php echo $img;?>" class="img-responsive">
            </div>
			<?php
		   endwhile;
		endif;
		?>
        </div>
    </div>
</section>
<?php get_footer();?>