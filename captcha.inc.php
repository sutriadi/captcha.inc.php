<?php 
//Start the session so we can store what the security code actually is
if(!isset($_SESSION))
	session_start();

//Send a generated image to the browser 
create_image(); 
exit(); 

function create_image() 
{ 
    //Let's generate a totally random string using md5 
    $md5_hash = md5(rand(0,999)); 
    //We don't need a 32 character long string so we trim it down to 5 
    $security_code = substr($md5_hash, 15, 5); 

    //Set the session to store the security code
    $_SESSION["security_code"] = $security_code;

    //Set the image width and height 
    $width = 100; 
    $height = 40;  

    //Create the image resource 
    $image = ImageCreate($width, $height);  

    //We are making three colors, white, black and gray 
    $white = ImageColorAllocate($image, 255, 255, 255); 
    $yellow = ImageColorAllocate($image, 255, 255, 0); 
    $black = ImageColorAllocate($image, 0, 0, 0); 
    $shadow = ImageColorAllocate($image, 255, 120, 120); 

    //Make the background black 
    ImageFill($image, 0, 0, $black); 

    //Add randomly generated string in white to the image
    $font = __DIR__ . "/DroidSerif-Italic.ttf";
/*
    ImageString($image, 3, 30, 3, $f, $yellow);
*/
    // shadow text
    imagettftext($image, 20, 15, 15, 30, $shadow, $font, $security_code);
    // true text

    imagettftext($image, 20, 5, 20, 33, $yellow, $font, $security_code);

    //Throw in some lines to make it a little bit harder for any bots to break 
    ImageRectangle($image, 0, 0, $width-1, $height-1, $shadow); 
    imageline($image, 0, $height/2, $width, $height/2, $shadow); 
    imageline($image, $width/2, 0, $width/2, $height, $shadow); 
 
    //Tell the browser what kind of file is come in 
    header("Content-Type: image/jpeg; name=\"Captcha.jpg\""); 

    //Output the newly created image in jpeg format 
    ImageJpeg($image); 
    
    //Free up resources
    ImageDestroy($image); 
} 
