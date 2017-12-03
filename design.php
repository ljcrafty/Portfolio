<?php
    if( empty($_GET['title']) || empty($_GET['cont']) )
    {
        dies();
    }

    $title = $_GET['title'];
    $contTitle = $_GET['cont'];
    $filename = "media/cards.json";

    if( !file_exists($filename) || !is_readable($filename) )
    {
        dies();
    }

    $data = file_get_contents($filename);
    $json = json_decode($data);
    $conts = $json -> containers;
        
    //find right container
    foreach( $conts as $container )
    {
        if($container -> title == $contTitle)
        {
            $cont = $container;
        }
    }

    if( !$cont )
    {
        dies();
    }

    //find right card
    $objs = $cont -> objs;

    foreach( $objs as $obj )
    {
        if( array_key_exists("desTitle", $obj) && $obj -> desTitle == $title )
        {
            $card = $obj;
        }
    }

    if( !$card )
    {
        dies();
    }



    function dies()
    {
        header("Location: index.html");
        die();
    }

    function numImgs( $imgBase )
    {
        $paths = glob("media/$imgBase*.png");
        return count($paths);
    }

    function jsSetup($imgBase)
    {
        $paths = glob("media/$imgBase*.png");
        $var = $imgBase . "Arr";
        
        echo "<script> var $var = [];";
        
        foreach( $paths as $path )
        {
            echo "$var.push('$path');\n";
        }

        echo "images['$imgBase'] = $var;";

        echo "</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/design.css">
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,300,500" rel="stylesheet">
    <script src="js/functions.js"></script>
    <title><?= $card -> title ?></title>
</head>
<script> var images = []; </script>
<body>
    <nav>
        <a href="index.html#<?= strtolower($contTitle) ?>">&larr; Back</a>
        <h2><?= $card -> title ?></h2>
    </nav>

    <main>
        <div id="overview">
            <?php echo"<img src='{$card -> mainImg}' alt='Finished Product'/>"; ?>
            <span><?= $card -> overview ?></span>
        </div>

        <?php
            foreach( $card -> steps as $step )
            {
                echo "<div class='step'>\n<h2>{$step -> title}</h2>";

                if( array_key_exists("imgBase", $step) )
                {
                    $base = $step -> imgBase;
                    $left = ( numImgs($base) > 1 ? "<a href='javascript: changeShow(\"$base\", -1)'>&lt;</a>" : "" );
                    $right = ( numImgs($base) > 1 ? "<a href='javascript: changeShow(\"$base\", 1)'>&gt;</a>" : "" );

                    echo "<div class='imgGal' id='$base'>
                        $left
                        <img class='showcaseimg' src='media/{$base}1.png'/>
                        $right
                    </div>";

                    jsSetup($base);
                }

                if( array_key_exists("link", $step) )
                {
                    echo "<a href='{$step -> link}'>{$card -> title} {$step -> title}</a>";
                }

                echo "<p>{$step -> text}</p>\n</div>";
            }
        ?>
    </main>
</body>
</html>