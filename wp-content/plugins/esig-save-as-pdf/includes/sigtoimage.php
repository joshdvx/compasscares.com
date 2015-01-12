<?php
require_once('../../../../wp-load.php');

require_once('../../../../wp-config.php');

function imageto($json){
require_once 'signature-to-image.php';

$img = sigJsonToImage($json);


// Output to browser
header('Content-Type: image/png');
imagepng($img);

// Destroy the image in memory when complete
imagedestroy($img);
}

 if(! function_exists('WP_E_Sig'))
				return ;
				
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		$signature = new WP_E_Signature; 
		
		$userid=$_GET['uid'];
		$doc_id=$_GET['doc_id'];
		//$owner_id =$_GET['owner_id']; 
        if(isset($_GET['owner_id']) && $_GET['owner_id']=="1"){
            $json =$signature->getUserSignature($userid);
        }else {
        
            $json =$signature->getDocumentSignature($userid,$doc_id);
         }
         
    imageto($json);
    
?>

