<?php 
/* =============================
Default functions
============================= */
function init_template() {
	add_theme_support('post-thumbnails');
	add_theme_support('title-tag');

	// Agregar menu a Appereance Menu
	register_nav_menus( 
		array(
			'top_menu' => 'Main Menu'
		)
		);
}

add_action( 'after_setup_theme', 'init_template');

/* =============================
Add custom CSS and JavaScript
============================= */
function assets() {

	// CSS
	wp_register_style( 'foundation', 'https://cdn.jsdelivr.net/npm/foundation-sites@6.6.3/dist/css/foundation-prototype.min.css', '', '6.3', 'all');
	wp_register_style( 'ubuntu', 'https://fonts.googleapis.com/css2?family=Ubuntu&display=swap', '', '1.0', 'all');
	wp_register_style( 'fontAwesome', 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.0/css/all.min.css', '', '5.13.0', 'all');

	// Al poner foundation y ubuntu como dependencias, se cargan antes del CSS del tema
	wp_enqueue_style( 'styles', get_stylesheet_uri(), array('foundation','ubuntu','fontAwesome'), '1.0', 'all');

	// JavaScript
	wp_register_script( 'whatInput', 'https://cdn.jsdelivr.net/npm/what-input@5.2.7/dist/what-input.min.js', '', '5.2.7', true );
	wp_enqueue_script( 'foundation', 'https://cdn.jsdelivr.net/npm/foundation-sites@6.6.3/dist/js/foundation.min.js', array('jquery','whatInput'), '6.6.3', true );
	wp_enqueue_script( 'custom', get_template_directory_uri().'/assets/js/custom.js', '', '1.0', true );

	// Enviar data de PHP en un objeto a JS. custom es el nombre del js file
	wp_localize_script( 'custom', 'objPlatziGift', array(
		'ajaxurl' => admin_url( 'admin-ajax.php'), //para todas las peticiones ajax usaoms la misma URL
		
		// para custom endpoint
		'apiurl' => home_url('wp-json/pg/v1/')
	) );
}

add_action( 'wp_enqueue_scripts', 'assets');
// add_action( 'wp_footer', 'assets');

/* =============================
Add sidebar (enalenables widget at appereance menu)
============================= */
function sidebar() {
	register_sidebar(
		array(
			'name' => 'Footer',
			'id' =>'footer',
			'description' => 'Widgets zone for footer',
			'before_title' => '<p>',
			'after_title' => '</p>',
			'before_widget' => '<div id="%1$s" class="%2$s">',
			'after_widget' => '</div>',
		)
	);
}

add_action ('widgets_init', 'sidebar');

/* =============================
Move CSS and JS to the bottom
============================= */

function footer_enqueue_scripts() {
	// Javascript
	remove_action('wp_head', 'wp_print_scripts');
	remove_action('wp_head', 'wp_print_head_scripts', 9);
	remove_action('wp_head', 'wp_enqueue_scripts', 1);

	add_action('wp_footer', 'wp_print_scripts', 5);
	add_action('wp_footer', 'wp_enqueue_scripts', 5);
	add_action('wp_footer', 'wp_print_head_scripts', 5);

	// CSS
	remove_action('wp_head', 'wp_print_styles');
	remove_action('wp_head', 'wp_enqueue_style', 1);

	add_action('wp_footer', 'wp_print_styles', 5);
	add_action('wp_footer', 'wp_enqueue_style', 5);
}

add_action('after_setup_theme', 'footer_enqueue_scripts');


/* =============================
Generando custom post type
============================= */

function productos_type() {
	$labels = array(
		'name' => 'Prodcutos',
		'singular_name' => 'Producto',
		'menu_name' => 'Productos',
	);


	$args = array(
		'label' => 'Productos', //nombre por defecto
		'description' => 'Productos de Platzi',
		'labels' => $labels,
		'supports' => array('title', 'editor', 'thumbnail', 'revisions'),
		'public' => true,
		'show_in_menu' => true,
		'menu_position' => 5, //para mostrarlo al top del sidebar menu, va de 5 en 5 cada posicion
		'menu_icon' => 'dashicons-cart',
		'can_export' => true,
		'publicy_queryable' => true, // puede ser llamado por un loop
		'rewrite' => true, // para que tenga URL asignada
		'show_in_rest' => true, // activar gutenberg
	);
	register_post_type( 'producto', $args);
}

add_action( 'init', 'productos_type' );


/* =============================
Generando custom taxonomy
============================= */
function pgRegisterTax(){
	$args = array(
		'hierarchical' => true,
		'labels' => array(
			'name' => 'Categorías de Productos',
			'singular_name' => 'Categroía de Productos'
		),
		'show_in_nav_menu' => true,
		'show_admin_column' => true,
		'rewrite' => array('slug' => 'categoria-productos')
	);

	register_taxonomy( 'categoria-productos', array('producto'), $args);
}

add_action('init', 'pgRegisterTax');


/* =============================
Filtrar productos con AJAX
Muy similar a la usada en single-producto.php
============================= */

function pgFiltroProductos() {
	$args = array(
		'post_type' => 'producto',
		'post_per_page' => -1,
		'order' => 'ASC',
		'order_by' => 'title',
	);

	if($_POST['categoria']) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'categoria-productos',
				'field' => 'slug',
				'terms' => $_POST['categoria']
			)
		);
	}
	
	$productos = new WP_Query($args);

	if ($productos->have_posts()) {
		$return = array();

		while($productos->have_posts()) {
			$productos->the_post();
			$return[] = array(
				'imagen' => get_the_post_thumbnail( get_the_id(), 'large' ),
				'link' => get_the_permalink( ),
				'titulo' => get_the_title( )
			);
		}
		// enviar array de rta como json
		wp_send_json( $return);
	}
}

// Hacemos el hook a ambos para que el filtro funcione con usuarios loggedos y con los que no
add_action( 'wp_ajax_pgFiltroProductos', 'pgFiltroProductos');
add_action( 'wp_ajax_nopriv_pgFiltroProductos', 'pgFiltroProductos');


/* =============================
Creando custom end point
============================= */
function novedadesAPI(){
	register_rest_route(
		'pg/v1', //namespace,
		'novedades/(?P<cantidad>\d+)', // route. Lo que esta entre () es un atributo dinamico como expresion regular
		array(
			'methods' => 'GET',
			'callback' => 'pedidoNovedades'
		)
	);
}

function pedidoNovedades($data) { //data es el atributo dinamico de la funcion novedadesAPI
	$args = array(
		'post_type' => 'post',
		'post_per_page' => $data['cantidad'],
		'order' => 'ASC',
		'order_by' => 'title',
	);
	
	$novedades = new WP_Query($args);

	if ($novedades->have_posts()) {
		$return = array();

		while($novedades->have_posts()) {
			$novedades->the_post();
			$return[] = array(
				'imagen' => get_the_post_thumbnail( get_the_id(), 'large' ),
				'link' => get_the_permalink( ),
				'titulo' => get_the_title( )
			);
		}
		// enviar array de rta como json
		return $return;
	}
}

add_action( 'rest_api_init', 'novedadesAPI');


/* =============================
Conditioanl plugin
============================= */
# Deregister style file
function deregister_css() {
	if (is_page('18')) {
		wp_dequeue_style( 'contact-form-7' );
		// wp_deregister_style( 'contact-form-7' );
	}
}
add_action( 'wp_enqueue_scripts', 'deregister_css', 100 );


# Deregister style file
function deregister_js() {
	if (is_page('18')) {
		wp_dequeue_script('wp-embed');
	} 
}
add_action( 'wp_print_scripts', 'deregister_js', 100 ); //other hooks: get_footer


function deregister_js2() {
	if (is_page('18')) {
		wp_dequeue_script('contact-form-7');
	} 
}
add_action( 'wp_enqueue_scripts', 'deregister_js2', 100 );

/*
function deregister_js3() {
	if (is_page('18')) {
		wp_dequeue_script('contact-form-7');
		wp_dequeue_script('wp-embed');
	} 
}
add_action( 'get_footer', 'deregister_js3', 100 );
*/


/* =============================
Registrar custom gutenberg block
============================= */
function pgRegisterBlock() {
	$assets = include_once get_template_directory_uri().'blocks/build/index.asset.php';

	// registramos script
	wp_register_script(
		'pg-block',
		get_template_directory_uri().'blocks/build/index.js',
		$assets['dependencies'],
		$asstets['version']
	);

	// registramos bloque
	register_block_type(
		'pg/basic', //slug
		array(
			'editor_script' => 'pg-block' //handler de cuando registramos el script
		)
	);
}

add_action( 'init', 'pgRegisterBlock');