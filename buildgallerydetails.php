<?php

$exclude_list = array(".", "..", "example.txt", "thumbs", "dynamic", "folder_image.jpg", "imagesdetails.json");


//array format
//Type, Name, Description

function fileCheck($directory, $fileToCheck) {
	if(is_dir($directory . '/' . $fileToCheck)) {

		$folderTitle = $fileToCheck;
		$folderTitle = preg_replace('/[-_]/', ' ', $folderTitle);
		$folderTitle = preg_replace('/\..{3,4}/', '', $folderTitle);

		if (file_exists($directory . '/' . $fileToCheck . '/folder_image.jpg')) {
			return array ('type' => 'folder', 'name' => $fileToCheck, 'description' => $folderTitle . ' gallery', 'thumbnail' => $fileToCheck . '/folder_image.jpg' );
		} else {
			return array ('type' => 'folder', 'name' => $fileToCheck, 'description' => $folderTitle . ' gallery' );
		}
	} else {
		return imageFileCheck($directory, $fileToCheck);
	}
}


function imageFileCheck($directory, $fileToCheck) {
	$fileFullPath = $directory . '/' . $fileToCheck;
	if(is_file($fileFullPath)) {
		$mimes = array('image/gif','image/jpeg','image/pjpeg','image/png');
		$extensions = array('jpg','png','gif','jpeg');

		$imageInfo = array();
		$mime = getimagesize($fileFullPath,$imageInfo);
		$extension = strtolower( pathinfo( $fileFullPath, PATHINFO_EXTENSION ) );

		
		if ( in_array( $extension , $extensions ) AND in_array( $mime['mime'], $mimes ) ) {
			$exif = exif_read_data($fileFullPath, 0, true);
			// if ($fileToCheck == 'p1070235.jpg') {
			// 	$info = array();                      
			// 	getimagesize($fileFullPath, $info);
			// 	if(isset($info['APP13']))
			// 	{
			// 	    $iptc = iptcparse($info['APP13']);
			// 	    print $iptc['2#120'][0];
			// 	    print_r($iptc);
			// 	}
			// }
			$imageTitle = preg_replace('/^.+\/(.+?)/', '${1}', $directory);
			$imageTitle = preg_replace('/[-_]/', ' ', $imageTitle);
			// $imageTitle = $fileToCheck;
			if (!empty($exif['IFD0']['Title'])) {
				$imageTitle = $exif['IFD0']['Title'];
				$imageTitle = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $imageTitle);
			}  elseif (isset($imageInfo['APP13'])) {
				$iptc = iptcparse($imageInfo['APP13']);
				if (isset( $iptc['2#120'][0] )) {
					$imageTitle = $iptc['2#120'][0];
				}
			}
			if ($imageTitle == $fileToCheck) {
				$imageTitle = preg_replace('/[-_]/', ' ', $imageTitle);
				$imageTitle = preg_replace('/\..{3,4}/', '', $imageTitle);
			}
			if (file_exists($directory . '/thumbs/thumbs_' . $fileToCheck)) {
				return array ('type' => 'image', 'name' => $fileToCheck, 'description' => $imageTitle, 'thumbnail' => '/thumbs/thumbs_' . $fileToCheck);
			} else {
				return array ('type' => 'image', 'name' => $fileToCheck, 'description' => $imageTitle);
			}

		} else {
			return array ('type' => 'unknown', 'name' => $fileToCheck);
		}
	} else {
		// echo "no file:" . $_SERVER["DOCUMENT_ROOT"];
		return array ('type' => 'unknown', 'name' => $fileToCheck);
	}
}



function directoryProc($directory) {
	global $exclude_list;
	echo "Processing directory : " . $directory . " :\n";
	$directories = array_diff(scandir($directory), $exclude_list);
	$fileEntries = (array) null;
	foreach($directories as $dirEntry) {
		array_push($fileEntries, fileCheck($directory, $dirEntry));
	}

	//echo json_encode($fileEntries);
	file_put_contents($directory . '/imagesdetails.json', json_encode($fileEntries));

}

$regenerate = 1;
$baseDirectory = getcwd() . '/wp-content/gallery';
directoryProc($baseDirectory);
$exclusionPattern = "/\/thumbs|\/dynamic/";
//$exclusionPattern = "/\/\.\.$|\/thumbs\//";
$Directory = new RecursiveDirectoryIterator($baseDirectory, RecursiveDirectoryIterator::SKIP_DOTS);
foreach (new RecursiveIteratorIterator($Directory, RecursiveIteratorIterator::SELF_FIRST) as $filename) {
	if (is_dir($filename)) {
		// print $filename . ":\n";
		if (!preg_match($exclusionPattern, $filename) ) {
			// echo $filename . ":\n";
			// $filename = preg_replace('/^(.+)\/\.$/', '${1}', $filename);
			// $filename = preg_replace('/^(.+)\/\.\.$/', '${1}', $filename);

			if( !is_file($filename. '/imagesdetails.json') || $regenerate) {
				directoryProc($filename);
			} else {
				echo "Skipping : " . $filename . " :\n";
			}

		}
	}
	
}

?>
