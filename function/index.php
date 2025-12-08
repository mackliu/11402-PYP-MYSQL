<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>自定函式</title>
    <style>
    * {
        font-family:'Courier New', Courier, monospace
    }
</style>
</head>

<body>
<h1>自定函式</h1>    

<?php

//Triangle(8);
//RightTriangle(10);
//Diamond(20);
//Square(10);

Shape("菱形",12);
Shape("正三角形",10);
Shape("直角三角形",12);
echo "<br>";
Shape("矩形",10);

/* 形狀("菱形",12);
形狀("正三角形",10);
形狀("直角三角形",12);
echo "<br>";
形狀("矩形",10); */



/* function 形狀($shape="正三角形",$size=5){
    switch($shape){
        case "正三角形":
            Triangle($size);
            break;
        case "直角三角形":
            RightTriangle($size);
            break;
        case "矩形":
            Square($size);
            break;
        case "菱形":
            Diamond($size);
            break;
        default:
            echo "無此圖形";
    }

} */

function Shape($shape="正三角形",$size=5){
    switch($shape){
        case "正三角形":
            Triangle($size);
            break;
        case "直角三角形":
            RightTriangle($size);
            break;
        case "矩形":
            Square($size);
            break;
        case "菱形":
            Diamond($size);
            break;
        default:
            echo "無此圖形";
    }

}
/* function Shape($shape="Triangle",$size=5){
    switch($shape){
        case "Triangle":
            Triangle($size);
            break;
        case "RightTriangle":
            RightTriangle($size);
            break;
        case "Square":
            Square($size);
            break;
        case "Diamond":
            Diamond($size);
            break;
        default:
            echo "無此圖形";
    }

}
 */



function Triangle($line){
    for($i=0;$i<$line;$i++){
         
        for($k=0;$k<$line-1-$i;$k++){
            echo "&nbsp;";
        }
    
        for($j=0;$j<2*$i+1;$j++){
            echo "*";
        }
        echo "<br>";
    }
}


function RightTriangle($size){
for($i=0;$i<$size;$i++){
    for($j=0;$j<=$i;$j++){
        echo "*";
    }
    echo "<br>";
}
}


function Square($size){
    for($i=0;$i<$size;$i++){
        for($j=0;$j<$size;$j++){
               echo "*";
        }
        echo "<br>";
    }
}

function Diamond($line){
$x=$line;
$y=0;

if($x%2==0){
    $x=$x+1;
}

for($i=0;$i<$x;$i++){
    if($i>floor($x/2)){
        $y=$y-1;
    }else{
        $y=$i;
    }
    //echo $i."-".$y;


    for($k=0;$k<floor($x/2)-$y;$k++){
        echo "&nbsp;";
    }

    for($j=0;$j<2*$y+1;$j++){
        echo "*";
    }
    echo "<br>";
}

}

?>
</body>
</html>