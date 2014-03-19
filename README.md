FolderImageGallery
==================

Image gallery based on folder content

This will display a image gallery based on the folder contents.

There is a php script to create the files which stores image details for speed

Requires some static variables:
	define( 'GALLERY_BASE', site_url() . '/gallery/');
	define( 'GALLERY_CONTENT_BASE', "/wp-content/gallery/");
	define( 'GALLERY_DIRECTORY', "/gallery");

The following is a sample rewrite rule for the gallery pointing to a custom page
	function foldergallery_rewrite_rule() {
		add_rewrite_tag('%dir%','(.+)');
		add_rewrite_rule('^gallery(.*)','index.php?pagename=gallerytest&dir=$matches[1]','top');

	}
	 
	add_action( 'init', 'foldergallery_rewrite_rule' );
