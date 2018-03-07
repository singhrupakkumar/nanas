<?php

/**

* Template Name: Contact Us

*/

 get_header();?> 

 <!-- End Here Request Sec -->

    <section class="maps">
<?php echo get_field('map'); ?>
    </section>

    <section class="gallery_sec">

      <div class="overlay"></div>

      <div class="container">

        <div class="row">

          <div class="col-md-12"> 

            <!-- End Here -->

            <div class="sm-heading">

              <h6><?php _e('SEND US A MESSAGE'); ?></h6>

            </div>

            <div class="style-heading">

              <h1><?php _e('Contact us now'); ?></h1>

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

			<div class="col-md-9"> 
				<div class="contct_frm">
				   <?php echo do_shortcode('[contact-form-7 id="143" title="Contact form 1"]'); ?> 
					
				</div> 
			</div>

			<div class="col-md-3"> 
<div class="addrss_info">
			<h4>ADDRESS:</h4>

				<address>
				<?php echo get_option('nt_address'); ?>
				</address>
                

			</div>  
            
            <div class="addrss_info">
			<h4>Opening Hours:</h4>

				<address>

				<?php echo get_option('nt_opening'); ?>
				</address>
                

			</div> 
            
            <div class="addrss_info">
			<h4>Phone Number:</h4>

				<address>
				<?php echo get_option('nt_phone'); ?>
				</address>
                

			</div> 

			  

			</div>

        </div>

    </div>

	</section>

<?php get_footer();?>