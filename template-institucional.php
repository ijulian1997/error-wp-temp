<?php
//Template Name: Pagina Institucional
get_header(); 
$fields = get_fields();
?>

<section class="grid-container">
	<?php 
		if(have_posts()) {
			while(have_posts()){
				the_post(); ?>
				<h1> <?php echo $fields['titulo'] ?></h1>
				<img src="<?php echo $fields['imagen'] ?>" alt=""> 
				<hr>
				<?php the_content(); ?>
			<?php }
		} ?>
</section>

<?php get_footer(); ?>