<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head() ?>
	
</head>
<body>

<header>
	<div class="grid-container">
		<div class="grid-x align-middle">
			<div class="cell medium-4">
				<img src="<?php echo get_template_directory_uri() ?>/assets/img/logo.png" alt="Logo"> 
			</div>  <!-- // medium-4 -->

			<div class="cell medium-8">
				<nav>
					<?php wp_nav_menu(
						array(
							'theme_location' => 'top_menu', //top_menu es el nombre que se le dio en functions.php
							'menu_class' => 'menu align-right',
							'container_class' => 'container-menu'
						)
					); ?>
				</nav>
			</div> <!-- // medium-8 -->
		</div> <!-- // grid-x -->
	</div> <!-- // grid-container -->
</header>
