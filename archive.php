<?php get_header(); ?>
<section class="grid-container">
	<div class="grid-x grid-padding-y">
		<div class="cell text-center">
			<h1><?php the_archive_title( ) ?></h1>
		</div>
		
			<?php if(have_posts()){
				while (have_posts()) {
					the_post(  ); ?>
					<div class="cell medium-4">
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail('large'); ?>
							<h4><?php the_title(); ?></h4>
						</a> 
					</div>
				<?php  }
			} ?>
		
	</div>
</section>
<?php get_footer(); ?>