<?php get_header(); ?>

<section class="grid-container">
	<?php if(have_posts()){
		while(have_posts()) {
			the_post(  );
			$taxonomy = get_the_terms(get_the_ID(), 'categoria-productos');
			?>
				<h1 class="padding-vertical-1 text-center">
					<?php the_title() ?>
				</h1>
				<div class="grid-x grid-padding-y">
					<div class="cell medium-4">
						<?php the_post_thumbnail( 'medium'); ?>
					</div>

					<div class="cell medium-8">
						<?php the_content( ); ?>
						<a class="hollow button" href="#">Más info <i class="fas fa-info"></i></a>
						<a class="button" href="#">Añadir al carrito <i class="fas fa-shopping-cart"></i></a> <br>
						<code>this looks like code</code>
					</div>
				 </div>  <!-- //grid-x -->

				 <div class="grid-x grid-padding-y">
				 	<div class="cell medium-12">
					 	<?php echo do_shortcode('[contact-form-7 id="48" title="Contact form 1"]') ?>
					 </div>
				 </div> <!-- //grid-x -->

				<?php
					$exclude_post  = $post->ID;
					$args = array(
						'post_type' => 'producto',
						'post_per_page' => 6,
						'order' => 'ASC',
						'order_by' => 'title',
						'tax_query' => array(
							array(
								'taxonomy' => 'categoria-productos',
								'field' => 'slug',
								'terms' => $taxonomy[0]->slug
							)
						),
						'post__not_in' => array($exclude_post)
					);
					$productos = new WP_Query($args);

					if ($productos->have_posts()) {?>
						<div class="grid-x grid-padding-y align-center">
							<div class="cell text-center">
								<h3>Productos relacionados</h3>
							</div>
							<?php while($productos->have_posts()) {
								
								$productos->the_post(); ?>

								<div class="cell small-6 medium-3 large-2 text-center">
									<a href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail('thumbnail'); ?>
										<h4>
											<?php the_title(); ?>
										</h4>
									</a> 
								</div>
							<?php } ?> 
						</div>
					<?php } // if ends
		} // whlie ends
	}?>
	

</section>

<?php get_footer(); ?>