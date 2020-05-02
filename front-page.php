<?php get_header();?>

<section class="grid-container">
	<?php if(have_posts(  )) {
		while(have_posts(  )) {
			the_post(  ); ?>
			<h1><?php the_title(); ?> !!</h1>
			<?php the_content(); ?>
		<?php  }
	}?>
</section>

<section class="lista-productos grid-container">
	<h2 class="text-center">Productos</h2>
	<div class="grid-x grid-padding-y">
		<div class="cell">
			<select name="categorias-productos" id="categorias-productos">
				<option value="">Todas las categorías</option>
				<?php $terms = get_terms('categoria-productos', array('hide_empty' => true));
					foreach ($terms as $term) {
						echo '<option value="'.$term->slug.'">'.$term->name.'</option>';
					}
				?> 
			</select>
		</div>
	</div>
	<div id="resultado-productos" class="grid-x grid-padding-y">
		<?php 
			$args = array(
				'post_type' => 'producto',
				'post_per_page' => -1, // mostrar todos
				'order' => 'ASC', // default: 'DESC'
				'orderby' => 'title', // default: 'date'
			);
			
			$productos = new WP_Query($args);

			if($productos -> have_posts(  )) {
				while($productos -> have_posts(  )) {
					$productos -> the_post(  );
					?>
					<div class="cell small-6 medium-4">
						<a href="<?php the_permalink( ); ?>">
							<figure>
								<?php the_post_thumbnail('large'); ?>
							</figure>
							<h4 class="text-center"><?php the_title(); ?></h4>
						</a>
					</div> <!-- //medium-4 -->
				<?php }
		}?>
	</div>
</section>

<section class="grid-container">
	<div class="grid-x grid-padding-y">
		<div class="cell">
			<h2>Novedades</h2>
		</div>
	</div> <!-- grid-x -->

	
	<div id="resultado-novedades" class="grid-x grid-padding-y grid-padding-x">
	</div> <!-- grid-x -->
</section>

<?php get_footer(); ?>