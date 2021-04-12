<?php
session_start();
$image = imagecreatetruecolor(100, 30);
$bgcolor = imagecolorallocate($image,255,255,255);
imagefill($image, 0, 0, $bgcolor);
$captcha_code = "";
for($i=0;$i<4;$i++){
    $fontsize = 6;    
    $fontcolor = imagecolorallocate($image, rand(0,120),rand(0,120), rand(0,120));//0-120深颜色
    $fontcontent = rand(0,9);
    $captcha_code .= $fontcontent;  
    $x = ($i*100/4)+rand(5,10);
    $y = rand(5,10);
    imagestring($image,$fontsize,$x,$y,$fontcontent,$fontcolor);
}
$_SESSION['authcode'] = $captcha_code;
for($i=0;$i<200;$i++){
    //50-200颜色比数字浅
    $pointcolor = imagecolorallocate($image,rand(50,200), rand(50,200), rand(50,200));    
    imagesetpixel($image, rand(1,99), rand(1,29), $pointcolor);
}
//增加干扰元素
for($i=0;$i<4;$i++){
    //设置线的颜色
    $linecolor = imagecolorallocate($image,rand(80,220), rand(80,220),rand(80,220));
    //设置两点一线
    imageline($image,rand(1,99), rand(1,29),rand(1,99), rand(1,29),$linecolor);
}
header('Content-Type: image/png');
imagepng($image);
imagedestroy($image);