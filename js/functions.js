//helpers
function byId( ele )
{
	return document.getElementById( ele );
}

function byTag( ele )
{
	return document.getElementsByTagName( ele );
}

function byClass( ele )
{
	return document.getElementsByClassName( ele );
}

function createEleWithText( type, text )
{
	var ele = document.createElement( type );
	var text = document.createTextNode( text );
	ele.appendChild( text );
	
	return ele;
}

function create(type)
{
	return document.createElement(type);
}

//init cards and arrow
function init()
{
	//make cards
	$.ajax({
  		url: "media/cards.json",
  		dataType: "json",
  		success: function(response) {
    		createCards(response.containers);
    		$(".card").flip();
  		},
  		error: function(xhr, ajaxOptions, thrownError)
  		{
  			console.log(thrownError);
  		}
	});
	
	//set arrow event listener
	byId( "arrow" ).addEventListener( "click", function() 
		{
			window.scrollTo(0, 0);
		}
	);
	
	//set menu event listener
	byId( "menu" ).addEventListener( "click", menu );
}

function createCards( json )
{
	for( var i = 0; i < json.length; i++ )
	{
		var cont = json[i];
		var container = create("div");
		var head = createEleWithText("h2", cont.title);
		head.setAttribute("id", cont.title.toLowerCase());
		
		container.appendChild(head);
		
		var cardCont = create("div");
		cardCont.setAttribute("class", "container");
		
		for( var j = 0; j < cont.objs.length; j++ )
		{
			var card = cont.objs[j];
			var cardDiv = create("div");
			cardDiv.setAttribute("class", "card");
			
			//front face
			var front = create("section");
			front.setAttribute("class", "front");
			front.setAttribute("style", "--filter: " + card.color);
			
			var img = create("img");
			img.setAttribute("src", "media/" + card.img);
			img.setAttribute("alt", card.title);
			
			var frontHead = createEleWithText("h2", card.title);
			var click = createEleWithText("p", "Click to flip");
			
			front.appendChild(img);
			front.appendChild(frontHead);
			front.appendChild(click);
			cardDiv.appendChild(front);
			
			//back face
			var back = create("section");
			back.setAttribute("class", "back");
			
			var backHead = createEleWithText("h3", card.title);
			back.appendChild(backHead);
			
			if( card.link != null )
			{
				if( card.linkText != null )
				{
					var link = createEleWithText("a", card.linkText);
				}
				else
				{
					var link = createEleWithText("a", card.title);
				}
				link.setAttribute("href", card.link);
				
				var p = create("p");
				var b = createEleWithText("b", "Link: ");
				
				p.appendChild(b);
				p.appendChild(link);
				back.appendChild(p);
			}
			
			//keyvals
			for( var k = 0; k < card.keyvals.length; k++ )
			{
				arr = card.keyvals[k];
				var p = create("p");
				var b = createEleWithText("b", arr[0] + ": ");
				var val = document.createTextNode(arr[1]);
				
				p.appendChild(b);
				p.appendChild(val);
				back.appendChild(p);
			}
			cardDiv.appendChild(back);
			
			cardCont.appendChild(cardDiv);
			container.appendChild(cardCont);
		}
		
		byTag("main")[0].insertBefore(container, byTag("main")[0].children[byTag("main")[0].children.length - 1]);
	}
}

//arrow
function arrow()
{
	var scrollPt = window.pageYOffset;
	var img = byId( "arrow" );
	
	if( scrollPt > 600 )//to top arrow
		img.style.display = "initial";
	else
	{
		
		img.style.display = "none";
	}
	
	if( scrollPt > 30 )
		byTag( "nav" )[0].className = "fixed";
	else
		byTag( "nav" )[0].className = "";
}

//cards
// function flip( id, mouseIn )
// {
// 	var div = cardList[id].card;
// 	
// 	//stayFlip?
// 	if( !cardList[id].flipped )//do regular flip
// 	{
// 		if( mouseIn == 1 )
// 			div.classList.toggle( "flipped" );
// 		else
// 			div.classList.toggle( "flipped" );
// 	}
// }
// 
// function stayFlip( id )//marshmallow man
// {
// 	cardList[id].flipped = !cardList[id].flipped;
// }

//scroll to element so you can see title
function scrollInto( ele )
{
	var element = byId( ele.toString() ).getBoundingClientRect().top;
	var body = byTag( "body" )[0].getBoundingClientRect().top;
	var menuStyle = byId( "menu" ).style;
	
	window.scrollTo( 0, element - body - 70 );
	
	if( menuStyle.display != "none" && menuStyle.left != "0mm" )
	{
		menu();
	}
}

function menu()
{
	var navTag = byTag( "nav" )[0];
	
	if( byId( "menu" ).style.left == "0mm" )
	{
		navTag.style.left = "0mm";
		byId( "menu" ).style.left = "150mm";
	}
	else
	{
		navTag.style.left = "-150mm";
		byId( "menu" ).style.left = "0mm";
	}
}