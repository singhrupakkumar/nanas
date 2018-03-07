 <?php 
/**
* Template Name: Reservation
*/ 
get_header();?>
 <section class="request_sec">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="sm-heading">
              <h6><?php _e('Book a Table'); ?></h6>
            </div>
            <!-- End Here -->
            <div class="style-heading">
              <h1><?php _e('Request Reservation'); ?></h1>
            </div>
            <!-- End Here -->
            <div class="sec_text">
              <p><?php echo get_field('about_reservation'); ?></p>
            </div>
          </div>
        </div>
        <!-- End Here -->
        <div class="clr"></div>
      </div>
    </section>
    <!-- End Here Request Sec -->
    <section class="requestform_sec">
      <div class="overlay"></div>
      <div class="container">
        <div class="row">
          <div class="col-md-12">            
            <!-- End Here -->
            
            <div class="reqst_form">
					<?php echo do_shortcode('[book_form]');?>
              
           </div>            
          </div>
        </div>
      </div>
      <div class="clr"></div>
    </section>
    <!-- End Here --> 
<?php get_footer(); ?>	