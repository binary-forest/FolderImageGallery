<?php
/*
Template Name: Gallery Page
*/
?>
<?php get_header(); ?>

<?php
	wp_register_style( 'pgcls_css',  get_stylesheet_directory_uri() . '/getimages.css' );
	// wp_enqueue_script('jquerymobile', 'http://code.jquery.com/mobile/1.4.2/jquery.mobile-1.4.2.min.js' );
	wp_enqueue_style( 'pgcls_css' );
	wp_dequeue_script('simplemodal');
	wp_dequeue_script('jquery-ui-core');
	wp_dequeue_script('jquery-ui-draggable');
	wp_dequeue_script('jquery-ui-resizable');
	wp_dequeue_script('jquery-ui-datepicker');
	wp_dequeue_script('growl');
	wp_dequeue_script('init_show_calendar');
	wp_dequeue_script('mousewheel');
	wp_dequeue_script('fullcalendar');
?>
<div class="container">
  <div class="row">
	<ol class="breadcrumb">
	  <?php
	    $urlPath = "";
	    $breadBase = GALLERY_BASE;
	    $showImageInit = 0;
	    
	    if (isset($wp_query->query_vars['dir'])) {
	    	$passedDirectory = $wp_query->query_vars['dir'];
	    	if ( preg_match('/\.\S{3,4}/', basename($passedDirectory))) {
	    		$imageName = basename($passedDirectory);
	    		$passedDirectory = dirname($passedDirectory);
	    		$showImageInit = 1;
	    	}
		  	$urlParts = explode("/", rawurldecode($passedDirectory));
		  	for ($i=0; $i < count($urlParts); $i++) {
		  		if ($i == 0) {
		  			$urlPath = $urlPath .  $urlParts[$i] . '/';
		  			echo "<li><a href='".  GALLERY_BASE . $urlParts[$i] . "/'>Home</a></li>";
		  		} elseif ($i == count($urlParts)-1) {
		  			echo '<li class="active">'.$urlParts[$i].'</li>';
		  		} else {
		  			$urlPath = $urlPath .  $urlParts[$i] . '/';
		  			echo '<li><a href="'.$breadBase .  $urlPath.'">'.$urlParts[$i].'</a></li>';
		  		}
		  	}
	  	}
	  ?>
	</ol>
  </div>
  <div class="row">
  	<div class="row">
<?php


// echo '<div class="col-lg-2 col-sm-3 col-xs-6"><a title="'.$imgTitle.'" href="#"><img alt="'.$imgTitle.'" class="galleryimage thumbnail img-responsive" width="200px" src="'.$imageThumbnailUrl.'" fullimage_src="'.$imageUrl.'"></a><p>'.$imgTitle.'</p></div>';
// echo "<div class='col-lg-2 col-sm-3 col-xs-6'><a title='".$imgTitle."' href='" . $folderUrl. "/'><img class=' img-responsive' width='200px' src='".$imageUrl."'></a><p>".$imgTitle."</p></div>";

$fileSysBase =  getcwd() . GALLERY_CONTENT_BASE;
$galleryFile = 'imagesdetails.json';
$urlBase = content_url() . GALLERY_DIRECTORY;
$linkBase = GALLERY_BASE;

if (isset($wp_query->query_vars['dir'])) {
  	// $urlParts = explode("/", rawurldecode($wp_query->query_vars['dir']));
  	$fileSysBase = $fileSysBase . rawurldecode($passedDirectory) . '/';
  	$urlBase = $urlBase . rawurldecode($passedDirectory) . '/';
  	$linkBase = $linkBase . rawurldecode($passedDirectory) . '/';
}

$galleryDetails = json_decode(file_get_contents($fileSysBase.$galleryFile), true);

foreach ( $galleryDetails as $galleryDetail ) {

	if ($galleryDetail['type'] == 'image') {
		if (isset($galleryDetail['thumbnail'])) {
			//echo '<div class="col-lg-2 col-sm-3 col-xs-6"><a title="'.$galleryDetail['description'].'" href="#"><img alt="'.$galleryDetail['description'].'" class="galleryimage thumbnail img-responsive" width="200px" src="'.$urlBase.$galleryDetail['thumbnail'].'" fullimage_src="'.$urlBase.$galleryDetail['name'].'"></a><div class="gallery-imgtitle "><p>'.$galleryDetail['description'].'</p></div></a></div>';
			echo '<div class="col-lg-2 col-sm-3 col-xs-6"><a title="'.$galleryDetail['description'].'" href="#"><img alt="'.$galleryDetail['description'].'" class="galleryimage thumbnail img-responsive" width="200px" src="'.$urlBase.$galleryDetail['thumbnail'].'" fullimage_src="'.$urlBase.$galleryDetail['name'].'"></a><p>'.$galleryDetail['description'].'</p></div>';
		} else {
			//echo '<div class="col-lg-2 col-sm-3 col-xs-6"><a title="'.$galleryDetail['description'].'" href="#"><img alt="'.$galleryDetail['description'].'" class="galleryimage thumbnail img-responsive" width="200px" src="'.$urlBase.$galleryDetail['name'].'" fullimage_src="'.$urlBase.$galleryDetail['name'].'"><div class="gallery-title"><p>'.$galleryDetail['description'].'</p></div></a></div>';
			echo '<div class="col-lg-2 col-sm-3 col-xs-6"><a title="'.$galleryDetail['description'].'" href="#"><img alt="'.$galleryDetail['description'].'" class="galleryimage thumbnail img-responsive" width="200px" src="'.$urlBase.$galleryDetail['name'].'" fullimage_src="'.$urlBase.$galleryDetail['name'].'"></a><p>'.$galleryDetail['description'].'</p></div>';
		}
		
	} if ($galleryDetail['type'] == 'folder') {
		if (isset($galleryDetail['thumbnail'])) {
			echo '<div class="col-lg-2 col-sm-3 col-xs-6"><a title="'.$galleryDetail['description'].'" href="'.$linkBase.$galleryDetail['name'].'"><img class=" img-responsive" width="200px" src="'.$urlBase.$galleryDetail['thumbnail'].'"></a><p>'.$galleryDetail['description'].'</p></div>';
		} else {
			echo '<div class="col-lg-2 col-sm-3 col-xs-6"><a title="'.$galleryDetail['description'].'" href="'.$linkBase.$galleryDetail['name'].'"><img class=" img-responsive" src="http://192.168.1.76/wp-content/themes/codeblack/folder.png"><div class="gallery-title"><p>'.$galleryDetail['description'].'</p></div></a></div>';
		}
	}

}




?>

    </div>
  </div>
</div>

<div tabindex="-1" class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">
  <div class="modal-content">
	<div class="modal-header">
		<button class="close" type="button" data-dismiss="modal">x</button>
		<h3 class="modal-title">Heading</h3>
	</div>
	<div class="modal-body">
		<div><img class="img-rounded img-responsive img-full" alt="" id="mimg" src=""></div>
		<ul class="pagination"><li><a class="prevImage" href="#">&laquo;</a></li><li><a class="nextImage" href="#">&raquo;</a></li></ul>
	</div>
<!-- 	<div class="modal-footer">
		<button class="btn btn-default" data-dismiss="modal">Close</button>
	</div> -->
   </div>
  </div>
</div>

<?php
	if ($showImageInit) {
		echo "<script type='text/javascript'>
			jQuery( document ).ready(function() {
					var imgTitle = jQuery(\"img[fullimage_src='".$urlBase.$imageName."']\").parent('a').attr('title');
				  	jQuery('.modal-title').html(imgTitle);
					jQuery('#mimg').attr('src','".$urlBase."/".$imageName."');
					jQuery('#mimg').attr('alt','alt text');
			        jQuery('#myModal').modal({show:true});
			    });
		</script>";
	}
?>

<script type='text/javascript'>
	jQuery('.thumbnail').click(function(){
	  	// jQuery('.modal-body').empty();
	  	var title = jQuery(this).parent('a').attr("title");
	  	jQuery('.modal-title').html(title);
	  	// jQuery(jQuery(this).parents('div').html()).appendTo('.modal-body');

		var sr=jQuery(this).attr('fullimage_src');
		var imgalt=jQuery(this).attr('alt');
		jQuery('#mimg').attr('src',sr);
		jQuery('#mimg').attr('alt',imgalt);

	  	jQuery('#myModal').modal({show:true});
	});

	jQuery('#mimg').click(function(){
		jQuery('#myModal').modal('hide');
	});

	jQuery('.prevImage').click(function(){
		var imagesList = [];
		jQuery('.galleryimage').each(function(){
			imagesList.push(jQuery(this));
		});
		var currentImage = 0;
		for ( var i = 0; i < imagesList.length; i = i + 1 ) {
			if (imagesList[ i ].attr('fullimage_src') == jQuery('#mimg').attr('src')) {
				currentImage = i;
				if (i == 0) {
					jQuery('#mimg').attr('src', imagesList[ imagesList.length-1 ].attr('fullimage_src'));
					jQuery('.modal-title').html(imagesList[ imagesList.length-1 ].attr('alt'));
					return(false);
				} else {
					jQuery('#mimg').attr('src', imagesList[ i-1 ].attr('fullimage_src'));
					jQuery('.modal-title').html(imagesList[ i-1 ].attr('alt'));
					return(false);
				}
			}
		}
	});

	jQuery('.nextImage').click(function(){
		var imagesList = [];
		jQuery('.galleryimage').each(function(){
			imagesList.push(jQuery(this));
		});
		var currentImage = 0;
		for ( var i = 0; i < imagesList.length; i = i + 1 ) {
			if (imagesList[ i ].attr('fullimage_src') == jQuery('#mimg').attr('src')) {
				currentImage = i;
				if (i == imagesList.length-1) {
					jQuery('#mimg').attr('src', imagesList[ 0 ].attr('fullimage_src'));
					jQuery('.modal-title').html(imagesList[ 0 ].attr('alt'));
					return(false);
				} else {
					jQuery('#mimg').attr('src', imagesList[ i+1 ].attr('fullimage_src'));
					jQuery('.modal-title').html(imagesList[ i+1 ].attr('alt'));
					return(false);
				}
			}
		}
	});

	jQuery(document).keydown(function(e){
	    if (e.keyCode == 37) { 
	       jQuery('.prevImage').trigger('click');
	    	return false;
	    }
	    if (e.keyCode == 39) {
	    	jQuery('.nextImage').trigger('click');
	    	return false;
	    }
	});

	// jQuery(function(){
	//   // Bind the swipeHandler callback function to the swipe event on div.box
	//   jQuery( "#mimg" ).on( "swipeleft", swipeHandler );
	 
	//   // Callback function references the event target and adds the 'swipe' class to it
	//   function swipeHandler( event ){
	// 	jQuery('.prevImage').trigger('click');
	// 	return false;
	//   }
	// });

	// jQuery(function(){
	//   // Bind the swipeHandler callback function to the swipe event on div.box
	//   jQuery( "#mimg" ).on( "swiperight", swipeHandler );
	 
	//   // Callback function references the event target and adds the 'swipe' class to it
	//   function swipeHandler( event ){
	// 	jQuery('.nextImage').trigger('click');
	// 	return false;
	//   }
	// });

	jQuery(window).on('load resize', function(){
		var windowWidth = jQuery(window).width() - 20;
		var maxImageWidth = windowWidth-50;
		var windowHight = jQuery(window).height() - 230;

		jQuery('#mimg').css('max-height',windowHight+'px');
		jQuery('#mimg').css('max-width',maxImageWidth+'px');

		jQuery('.modal-dialog').css('width',windowWidth+'px');

	});

</script>
<?php get_footer(); ?>