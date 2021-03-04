<?php
#
# ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
#
# CleanForm > Captcha
#
# @author           Taras Palasyk (https://github.com/b33rm3up)
# @copyright        Copyright (c) 2021, Taras Palasyuk. All rights reserved.
# @licence          BSD 3-Clause License
# @version          Version 0.1
# @revision         null
# @category         null
# @filename         captcha.php
#
# ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
#
session_start();
 
$permitted_chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
  
function generate_string($input, $strength = 10) {
    $input_length = strlen($input);
    $random_string = '';
    for($i = 0; $i < $strength; $i++) {
        $random_character = $input[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
    }
  
    return $random_string;
}
 
$image = imagecreatetruecolor(206, 46);
 
imageantialias($image, true);
 
$colors = [];
 
$red = rand(125, 175);
$green = rand(125, 175);
$blue = rand(125, 175);
 
for($i = 0; $i < 5; $i++) {
  $colors[] = imagecolorallocate($image, $red - 20*$i, $green - 20*$i, $blue - 20*$i);
}
 
imagefill($image, 0, 0, $colors[0]);
 
for($i = 0; $i < 10; $i++) {
  imagesetthickness($image, rand(2, 10));
  $line_color = $colors[rand(1, 4)];
  imagerectangle($image, rand(-10, 190), rand(-10, 10), rand(-10, 190), rand(40, 60), $line_color);
}
 
$black = imagecolorallocate($image, 0, 0, 0);
$white = imagecolorallocate($image, 255, 255, 255);
$textcolors = [$black, $white];
 
$fonts = ['Captcha/Fonts/NSys.otf', 'Captcha/Fonts/TypeWriter.ttf', 'Captcha/Fonts/Panic.ttf', 'Captcha/Fonts/CrackedCode.ttf', 'Captcha/Fonts/Glass.ttf'];
 
$string_length = 6;
$captcha_string = generate_string($permitted_chars, $string_length);
 
$_SESSION['CaptchaText'] = $captcha_string;
 
for($i = 0; $i < $string_length; $i++) {
  $letter_space = 170/$string_length;
  $initial = 15;
   
  imagettftext($image, 24, rand(-15, 15), $initial + $i*$letter_space, rand(25, 45), $textcolors[rand(0, 1)], $fonts[array_rand($fonts)], $captcha_string[$i]);
}
 
header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
