<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="utf-8">
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title><?php wp_title(''); echo ' :: ';  bloginfo( 'name' ); ?></title>

<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/images/favicon.ico" />
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script type="text/javascript" src="js/assets/html5shiv.min.js"></script>
<script type="text/javascript" src="js/assets/respond.min.js"></script>
<![endif]-->
<style>
<?php echo get_option('nt_custom_css'); ?>

.loader {
	position: fixed;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	z-index: 9999;
	background: url('<?php echo get_template_directory_uri(); ?>/images/bx_loader.gif') 50% 50% no-repeat rgb(249,249,249);
}		

</style>

<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">
<div class="loader"></div>
<main class="st-wrapper">
<header class="st-header">
	<nav class="navbar navbar-default st_navbar">
		<div class="container">
			<div class="row">
            <div class="col-sm-12">
            <div class="lang">
             <?php echo do_shortcode('[gtranslate]'); ?>
            </div>
            </div>
				<div class="col-md-12">
					<div class="menu_wrapper">					
					 <?php wp_nav_menu( array( 'theme_location' => 'primary',
                    'container_class'=>'menu_left',
                     'menu_id' => 'menu',
                     'menu_class' => 'nav navbar-nav',

                      ) ); ?>

						<div class="menu_center">
							<!-- Brand and toggle get grouped for better mobile display -->
							<div class="navbar-header">
								<button type="button"  class="navbar-toggle collapsed" data-toggle="collapse" data-target="#" aria-expanded="false">
									<span class="sr-only">Toggle Navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="navbar-brand"><img src="<?php echo  get_option('nt_logo'); ?>" alt="Logo"></a>
							</div>
						</div><!-- End Here Menu Center -->
						
					<?php wp_nav_menu( array( 'menu' => 'Right-header',
                    'container_class'=>'menu_right',
                     'menu_id' => 'menu',
                     'menu_class' => 'nav navbar-nav',

                      ) );
					  ?>
					 
					</div>
				</div>
			</div>
		</div>
	</nav>
	<div class="clr"></div>
</header><!-- Header Section End Here -->

<section class="st-content">