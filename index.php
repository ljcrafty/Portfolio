<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/index.css"/>
    <link href="https://fonts.googleapis.com/css?family=Work+Sans:100,300,500" rel="stylesheet">
    <link rel="icon" type="image/png" href="media/icon.png">
    <script src="js/functions.js"></script>
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
	<script src="https://cdn.rawgit.com/nnattawat/flip/master/dist/jquery.flip.min.js"></script>
	<script src="js/jquery-ui.min.js"></script>
    <title>Lauren Johnston | Portfolio</title>
  </head>
  <body onscroll="arrow();" onload="init();">
  	
  	<img id="menu" src="media/menu.png" style="left: 0mm;"/>
  	<nav>
		<span onclick="window.scrollTo(0, 0);" id="logo"><img src="media/logoDark.png" alt="Logo" /></span>
  		<a href="javascript: scrollInto( 'bio' );">Bio</a>
  		<a href="javascript: scrollInto( 'projects' );">Projects</a>
  		<a href="javascript: scrollInto( 'work' );">Work</a>
  		<a href="javascript: scrollInto( 'volunteer' );">Volunteer</a>
  		<a href="javascript: scrollInto( 'contact' );">Contact</a>
  	</nav>
  	<section id="landing">
		<img src="media/logoDark.png" alt="Logo" id="bigLogo"/>
		<h1>
			<a class="noULine" href="javascript: scrollInto( 'bio' );">
					Lauren Johnston
			</a>
		</h1>
		<h2 id="sub" >
			<a class="noULine" href="javascript: scrollInto( 'bio' );">
				Web and Mobile Computing B.S. and Human Centered Computing B.S.
			</a>
		</h2>
  	</section>
  	<img id="arrow" type="image" src="media/arrow.png"/>
  	<main>
  		<!-- Bio -->
  		<div id="pad">
  			<h2 id="bio">Bio</h2><span class="orng">.</span>
  			<img id="me" src="media/me.jpg" width="300" height="420"/>
  			<p><b>Lauren Johnston</b></p>
  			<p>
  				Highly motivated individual with concentration in web and mobile development 
  				and back-end development. Skills in PHP, Javascript, HTML, Java, C++, and 
  				UNIX.
  			</p>
  			<p>Web and Mobile B.S. and Human Centered Computing B.S.</p>
  			<p>Rochester Institute of Technology</p>
  			<p><a href="media/Lauren_Johnston_RIT_resume.pdf">R&eacute;sum&eacute;</a></p>
		  </div>
		  
		<?php
			$json = json_decode( file_get_contents("./media/cards.json") ) -> {"containers"};

            //create each section
			for( $sec = 0; $sec < count($json); $sec++ )
			{
				$sect = $json[$sec];
				$container = "<div class='tab'>
					<h2 id='" . strtolower($sect -> {'title'}) . "'>" . $sect -> {"title"} . "</h2>\n
					<span class ='orng'>.</span>\n";

				//add toggle if necessary
				if( array_key_exists( "types", $sect ) )
				{
					$ul = "<ul>";

					for( $j = 0; $j < count($sect -> {'types'}); $j++ )
					{
						$ul .= "<li>
							<a href='#" . $sect -> {"types"}[$j] . "'>" . $sect -> {"types"}[$j] . "</a>" .
						 	"</li>";
					}

					$container .= $ul . "</ul>\n";

					//add card containers
					for( $j = 0; $j < count($sect -> {"types"}); $j++ )
					{
						$typeCont = createCardContainer($sect, $sect -> {"types"}[$j]);
						$container .= $typeCont  . "\n";
					}
				}
				else//no toggle
				{
					$typeCont = createCardContainer($sect);
					$container .= $typeCont . "\n";
				}

				echo $container;
			}

			
			/*
            	Creates cards for a given section and type (for toggle)
            */
            function createCardContainer($cont, $type = "")
            {
            	//add type to container if needed
				$cardCont = ($type == "" ? "<div class='container'>" : 
					"<div class='container' id='" . $type . "'>");
            	
            	//add the actual cards
            	for( $j = 0; $j < count($cont -> {"objs"}); $j++ )
            	{
					$card = $cont -> {"objs"}[$j];
					$reg = array_key_exists("img", $card);
			
					//only make cards of the given type
            		if( array_key_exists("type", $card) && $card -> {"type"} != $type )
            			continue;
            
            		$cardDiv = ( $reg ? "<div class='card'>" : "<div class='linkCard'>");
            
            		//front face
					$front = "<section style='--filter: " . $card -> {"color"} . "'" . 
						($reg ? "class='front'>" : "onclick='function(){window.location = \"design.php?title=" . 
							$card -> {'title'} . ";\"'})>" );
					$frontHead = "<h2>" . $card -> {"title"} . "</h2>";
            
            		//is a regular card
            		if( $reg )
            		{
						$arr = $card -> {"keyvals"}[0];

            			$front .= "<img src='media/" . $card -> {"img"} . "' alt='" . $card -> {"title"} . "'/>";
            			$front .= $frontHead;
            			$front .= "<p>Click to flip</p>";
						$cardDiv .= $front . "</section>";
            
            			//back face
						$back = "<section class='back' style='--filter: " . $card -> {"color"} . 
							"'><h3>". $card -> {"title"} . "</h3>";
            
            			//take care of date value
            			$innerDiv = "<div><p><i>" . $arr[1] . "</i></p>";
            
            			//add link if needed
            			if( array_key_exists("link", $card) )
            			{
            				$innerDiv .= "<p><a href='" . $card -> {"link"} . "'>" . 
								(array_key_exists("linkText", $card) ? $card -> {"linkText"} : 
								$card -> {"title"}) . "</a></p>";
            			}
            			
            			//keyvals
            			for( $k = 1; $k < count($card -> {"keyvals"}); $k++ )
            			{
            				$arr = $card -> {"keyvals"}[$k];
            				$p = "<p>";
            				
            				if( $arr[0] != "Date" && $arr[0] != "Description" )
            				{
            					$p .= "<b>" . $arr[0] . "</b>";
            				}
            
            				$p .= $arr[1] . "</p>";
            				$innerDiv .= $p;
            			}
            			$back .= $innerDiv . "</div></section>";
            			$cardDiv .= $back . "\n";
            		}
            		else//link card
            		{
            			$front .= $frontHead . "</section>";
						$cardDiv .= $front;
            		}
            
            		$cardCont .= $cardDiv . "</div>\n";
            	}
            
            	return $cardCont . "</div>\n";
            }
		?>
  	
  		<!-- Contact -->
  		<div class="center">
  			<h2 id="contact">Contact</h2><span class="orng">.</span>
  			<p><b>Lauren Johnston</b></p>
  			<p>lxj7261@rit.edu</p>
  			<p>(717) 222-2859</p>
  			<p><a href="http://linkedin.com/in/lauren-johnston">linkedin.com/in/lauren-johnston</a>
  		</div>	
  	</main>
  	<footer>
  		<p>&copy;Lauren Johnston 2016</p>
  		<p><a href="sources.html">Image sources</a></p>
  	</footer>
  </body>
</html>