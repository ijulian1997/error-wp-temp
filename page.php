<?php get_header(); ?>

<section class="grid-container">
	<?php 
		if(have_posts()) {
			while(have_posts()){
				the_post(); ?>
				<h1><?php the_title(); ?></h1>
				<?php 
				// $temp = get_the_ID();
				// echo gettype($temp);
				// echo $temp;

				// $validador = is_page( 2 );
				// echo gettype($validador);
				// echo $validador;
				if (is_page( 2 )) {
					echo "esto es 2";
				} else {
					echo "esto no es 2";
				}
				// 	 //echo is_page(2);
				?>

				<?php the_content(); ?>
			<?php }
		} ?>
</section>

<?php get_footer(); ?>