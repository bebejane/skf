<!doctype html>
<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

	<head>
		<meta charset="utf-8">

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

		<title><?php wp_title(''); ?></title>

		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/library/icons/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/library/icons/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/library/icons/favicon-16x16.png">
		<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/library/icons/site.webmanifest">
		<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/library/icons/favicon.ico">
		<meta name="msapplication-TileColor" content="#e35632">
		<meta name="msapplication-config" content="<?php echo get_template_directory_uri(); ?>/library/icons/browserconfig.xml">
		<meta name="theme-color" content="#ffffff">

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

				<!-- Google Analytics -->
		<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		ga('create', '<?php the_field('ga_id','options'); ?>', 'auto');
		ga('send', 'pageview');
		</script>
		<!-- End Google Analytics -->

		<?php wp_head(); ?>

		<style type="text/css">
			body {
				background-color: <?php the_field('bg_color','option'); ?>;
			}
			.section-color, .text strong, body.open-nav li a, form label, h3 {
				color: <?php the_field('headline_color','option'); ?>;
			}
			.line {
				 border-color: <?php the_field('headline_color','option'); ?> !important;
			}
			.thumb figure, .big-image figure, ul.people figure {
				background-color: <?php the_field('frame_color','option'); ?> !important;
				border-color: <?php the_field('frame_color','option'); ?> !important;
			}

			aside.event div, aside.related ul, aside.related form {
				border-color: <?php the_field('frame_color','option'); ?> !important;
			}

			.breadcrumb li:after {
				color: <?php the_field('headline_color','option'); ?> !important;
			}

			.swiper-pagination-bullet-active {
				background-color: <?php the_field('headline_color','option'); ?> !important;
			}

		</style>

	</head>

	<body <?php
				$post_id = get_the_ID();
				$post_type = get_post_type( $post_id );
				$post_obj = get_post_type_object($post_type);
				body_class();
				//Ev att detta ska vara kvar?
				$startpages = [299,303,306];
	?>>

	   <!--[if lt IE 7]>
          <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
      <![endif]-->

			<div class="margin-wrapper">
			<div class="outer-container">
			<div class="container">
			<!--<nav class="search"><h1 class="icon">o</h1></nav>-->
			<nav class="logo">
				<a href="/">
					<figure>
						<img src="
						<?php
						if(is_front_page()) {
							the_field('logo','option');
						}
						else {
							the_field('logo','option');
						} ?>">
					</figure>
				</a>
			</nav>

			<nav class="top">
				<div class="top-wrapper">
				<ul class="menu-header"><li>Meny</li></ul>
				<?php include( locate_template( 'templates/framework/breadcrumb.php', false, false ) ); ?>
				<?php include( locate_template( 'templates/framework/menu.php', false, false ) );  ?>
				<button class="hamburger">
					<h1 class="icon burger">=</h1>
					<h1 class="icon cross">×</h1>
				</button>
				</div>
			</nav>

			<main>
