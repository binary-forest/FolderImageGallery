<?php
/*
 * WordPress Sample function and action
 * for loading scripts in themes
 */

	/*
	 * Constants used to improve server performance
	 */
	define( 'HOME_URI', home_url() );
	define( 'THEME_URI', get_stylesheet_directory_uri() );
	define( 'THEME_IMAGES', THEME_URI . '/images' );
	define( 'THEME_CSS', THEME_URI . '/css' );
	define( 'THEME_JS', THEME_URI . '/js' );
	/* using it <?php echo THEME_URI; ?> */

	define( 'GALLERY_BASE', site_url() . '/gallery/');
	define( 'GALLERY_CONTENT_BASE', "/wp-content/gallery/");
	define( 'GALLERY_DIRECTORY', "/gallery");
 

	function pgcls_rewrite_rule() {
		add_rewrite_tag('%dir%','(.+)');
		add_rewrite_rule('^gallery(.*)','index.php?pagename=gallerytest&dir=$matches[1]','top');

	}
	 
	add_action( 'init', 'pgcls_rewrite_rule' );
 	

	// Let's hook in our function with the javascript files with the wp_enqueue_scripts hook 
 	add_action( 'wp_enqueue_scripts', 'pgcls_load_javascript_files' );
 	add_action( 'wp_enqueue_scripts', 'pgcls_load_css_files' );
	 
	// Register some javascript files, because we love javascript files. Enqueue a couple as well 
	 
	function pgcls_load_javascript_files() {
		wp_register_script( 'bootstrapcdn', "http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js", array('jquery'), '3.1.1', true );
		//<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
		wp_enqueue_script( 'bootstrapcdn' );

	}
	
	function pgcls_load_css_files() {
		wp_register_style( 'bootstrap.min',  "//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css",'', '3.1.1' );
		//wp_enqueue_style( 'bootstrapcdn-style', "//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css",'', '3.1.1' );
		wp_register_style( 'font-awesome.min',  "//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css",'', '4.0.3' );
		wp_enqueue_style( 'bootstrap.min' );
		wp_dequeue_style( 'fontawesome-css');
		wp_enqueue_style( 'font-awesome.min' );
	}


?>