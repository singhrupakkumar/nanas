 <?php 
/**
* Template Name: About
*/ 
get_header();?>

 <!-- End Here Request Sec -->
    <section class="about_us_sec">
      <div class="overlay"></div>
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="sm-heading">
              <h6><?php _e('About Us');?></h6>
            </div>
            <!-- End Here -->
            <div class="style-heading">
              <h1><?php _e('Tradition &amp; Passion');?></h1>
            </div>
          </div>
          <!-- End Here --> 
          
        </div>
      </div>
      <div class="clr"></div>
    </section>
    <!-- End Here -->
    
    <section class="about-content">
      <div class="container">
        <div class="col-sm-12">
          <div class="col-sm-6">
            <div class="out_story">
              <div class="style-heading">
                <h1><?php _e('Our Short History');?></h1>
                <p><?php _e("HOW WE'VE STARTED OUR SMALL FIRM.");?></p>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="our_stry_content">
              <?php echo get_field('image_top_description'); ?>
            </div>
          </div>
        </div>
      </div>
    </section>
    
    <section class="about-content">
      <div class="container">
        <div class="col-sm-12">        
          <div class="col-sm-6 pull-right">
            <div class="list_story  ">
              <div class="style-heading">
               <img src="<?php echo get_the_post_thumbnail_url(); ?>" class="img-responsive" >
              </div>
            </div>
          </div>
          <div class="col-sm-6 pull-left">
            <div class="our_stry_content ">
             <?php echo get_field('image_left_description'); ?>
            </div>
          </div>          
        </div>
      </div>
    </section>

<?php get_footer(); ?>	