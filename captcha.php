<?php
    session_start();
    
    $permittedChars = "ABCDEFGHJKLMNPQRSTUVWXYZ0123456789!@#$*()=:?"; //the characters that the captcha will use
    
    //this pretty obviously creates a random string
    function generateString($input/*the input is the characters the function will choose from*/, $strength = 10/*the length of the string*/) {
        $inputLength = strlen($input); //gets length of the input
        $randomString = ""; //where the string will be storerd
        
        for($i = 0; $i < $strength; $i++) {
            $randomCharacter = $input[mt_rand(0, $inputLength - 1)];
            $randomString .= $randomCharacter;
        }
        
        return $randomString;
    }
    
    $image = imagecreatetruecolor(200, 50); //makes the base of the captcha image
    
    imageantialias($image, true);
    
    $colors = []; //where the colors will be stored
    
    //base colors
    $red = rand(125, 175);
    $green = rand(125, 175);
    $blue = rand(125, 175);
    
    //generates the colors
    for($i = 0; $i < 10; $i++) {
        $colors[] = imagecolorallocate($image, $red - 20*$i, $green - 20*$i, $blue - 20*$i);
    }
    
    imagefill($image, 0, 0, $colors[0]); //makes the background of the image
    
    //creates random rectangles in the background
    for($i = 0; $i < 20; $i++) {
        imagesetthickness($image, rand(2, 10));
        $lineColor = $colors[rand(1, 4)];
        imagerectangle($image, rand(-10, 190), rand(-10, 10), rand(-10, 190), rand(40, 60), $lineColor);
    }
    
    $black = imagecolorallocate($image, 0, 0, 0);
    $white = imagecolorallocate($image, 255, 255, 255);
    $textcolors = [$black, $white];
    
    $fonts = ["ransom.ttf", "eco.ttf"]; //the fonts that the captcha will use
    
    $stringLength = 7; //the length of the string
    
    $captchaString = generateString($permittedChars, $stringLength); //the captcha string
    
    $_SESSION["captchaText"] = $captchaString; //adds the captcha string to the session so you can check if the user got the captcha correct
    
    //puts the string in the picture
    for($i = 0; $i < $stringLength; $i++) {
        $letterSpace = 170/$stringLength;
        $initial = 15;
        
        imagettftext($image, 24, rand(-15, 15), $initial + $i*$letterSpace, rand(25, 45), $textcolors[rand(0, 1)], "/fonts/".$fonts[array_rand($fonts)], $captchaString[$i]);   
    }
    
    //creates a few thin rectangles infront of the text
    for($i = 0; $i < 5; $i++)
    {
        imagesetthickness($image, rand(1, 2));
        $lineColor = $colors[rand(1, 4)];
        imagerectangle($image, rand(-10, 190), rand(-10, 10), rand(-10, 190), rand(40, 60), $lineColor);
    }
    
    //creates dashed lines over the text
    for($i = 0; $i < 3; $i++)
    {
        imagesetthickness($image, 1);
        $lineColor = $colors[rand(1, 4)];
        imagedashedline($image, rand(-360, 360), rand(-360, 360), rand(-360, 360), rand(360, 360), $lineColor);
    }
    
    //makes a bunch of thin arcs over the text
    for($i = 0; $i < 10; $i++)
    {
        imagesetthickness($image, rand(1, 2));
        $lineColor = $colors[rand(1, 4)];
        imagearc($image, rand(-10, 190), rand(-10, 10), rand(-10, 190), rand(40, 60), 360, 0, $lineColor);
    }
    
    //makes it a picture
    header("Content-type: image/png");
    imagepng($image);
    imagedestroy($image);
?>
