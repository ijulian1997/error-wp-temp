<?php get_header(); ?>

<section class="grid-container">
	<?php if(have_posts()){
		while(have_posts()) {
			the_post(  );
			?>
				<h1 class="padding-vertical-1">
					<?php the_title() ?>
				</h1>
				<div class="grid-x grid-padding-y">
					<div class="cell medium-6">
						<?php the_post_thumbnail( 'medium'); ?>
					</div>

					<div class="cell medium-6">
						<?php the_content( ); ?>
					</div>
				</div>

				<?php get_template_part( 'template-parts/post', 'navigation' ) ?>
			<?php 
		}
	}?>
</section>


<?php get_footer(); ?>