<?php
    session_start();
    
    $permittedChars = "ABCDEFGHJKLMNPQRSTUVWXYZ0123456789!@#$^*()=:?";
    
    function generateString($input, $strength = 10) {
        $inputLength = strlen($input);
        $randomString = "";
        for($i = 0; $i < $strength; $i++) {
            $randomCharacter = $input[mt_rand(0, $inputLength - 1)];
            $randomString .= $randomCharacter;
        }
        
        return $randomString;
    }
    
    $image = imagecreatetruecolor(200, 50);
    
    imageantialias($image, true);
    
    $colors = [];
    
    $red = rand(125, 175);
    $green = rand(125, 175);
    $blue = rand(125, 175);
    
    for($i = 0; $i < 10; $i++) {
        $colors[] = imagecolorallocate($image, $red - 20*$i, $green - 20*$i, $blue - 20*$i);
    }
    
    imagefill($image, 0, 0, $colors[0]);
    
    for($i = 0; $i < 20; $i++) {
        imagesetthickness($image, rand(2, 10));
        $lineColor = $colors[rand(1, 4)];
        imagerectangle($image, rand(-10, 190), rand(-10, 10), rand(-10, 190), rand(40, 60), $lineColor);
    }
    
    $black = imagecolorallocate($image, 0, 0, 0);
    $white = imagecolorallocate($image, 255, 255, 255);
    $textcolors = [$black, $white];
    
    $fonts = ["ransom.ttf", "eco.ttf"];
    
    $stringLength = 7;
    $captchaString = generateString($permittedChars, $stringLength);
    
    $_SESSION["captchaText"] = $captchaString;
    
    for($i = 0; $i < $stringLength; $i++) {
        $letterSpace = 170/$stringLength;
        $initial = 15;
        
        if($captchaString[$i] === "^")
        {
            imagettftext($image, 24, rand(-15, 15), $initial + $i*$letterSpace, rand(25, 45), $textcolors[rand(0, 1)], "/fonts/".$fonts[1], $captchaString[$i]);
        } else
        {
            imagettftext($image, 24, rand(-15, 15), $initial + $i*$letterSpace, rand(25, 45), $textcolors[rand(0, 1)], "/fonts/".$fonts[array_rand($fonts)], $captchaString[$i]);   
        }
    }
    
    for($i = 0; $i < 5; $i++)
    {
        imagesetthickness($image, rand(1, 2));
        $lineColor = $colors[rand(1, 4)];
        imagerectangle($image, rand(-10, 190), rand(-10, 10), rand(-10, 190), rand(40, 60), $lineColor);
    }
    
    for($i = 0; $i < 3; $i++)
    {
        imagesetthickness($image, 1);
        $lineColor = $colors[rand(1, 4)];
        imagedashedline($image, rand(-360, 360), rand(-360, 360), rand(-360, 360), rand(360, 360), $lineColor);
    }
    
    for($i = 0; $i < 10; $i++)
    {
        imagesetthickness($image, rand(1, 2));
        $lineColor = $colors[rand(1, 4)];
        imagearc($image, rand(-10, 190), rand(-10, 10), rand(-10, 190), rand(40, 60), 360, 0, $lineColor);
    }

    header("Content-type: image/png");
    imagepng($image);
    imagedestroy($image);
?>