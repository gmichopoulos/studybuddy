<!DOCTYPE html> 
<html>

<head>
	<title>StudyBuddy</title> 
	<meta charset="utf-8">
	<meta name="apple-mobile-web-app-capable" content="yes">
 	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 

	<link rel="stylesheet" href="jquery.mobile-1.2.0.css" />
	<link rel="stylesheet" href="style.css" />
	<link rel="apple-touch-icon" href="appicon.jpg" />
	<link rel="apple-touch-startup-image" href="startup.jpg">

	<script src="//cdn.optimizely.com/js/139947620.js"></script>
	<script src="jquery-1.8.2.min.js"></script>
	<script src="jquery.mobile-1.2.0.js"></script>

	<link rel="stylesheet" href="jquery.mobile-1.2.0.css" />

</head>

<body>
<div data-role="page" id="filter">

	
	<div data-role="header">
		<img src="banner.gif">
	</div> <!-- /header -->

	<div data-role="content" style="padding:0">	
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
		<div id="mapcanvas" style="height:100%;width:100%;position:absolute"></div>

		<div id="currUserName" style="display:none;"></div>
		<div id="fb-root"></div>

		<script>
		  window.fbAsyncInit = function() {
		    FB.init({
		      appId      : '377088085704166', // App ID
		      status     : true, // check login status
		      cookie     : true, // enable cookies to allow the server to access the session
		      xfbml      : true  // parse XFBML
		    });

		    FB.getLoginStatus(function(response) {
				  if (response.status != 'connected') {
					window.location = "http://www.stanford.edu/~sapte1/cgi-bin/studybuddy/index.html";
				  } else {
				  	var uid = response.authResponse.userID;
   					var accessToken = response.authResponse.accessToken;
   					FB.api('/me', function(response) {
					    $('#currUserName').html(response.name);
					    $('#uID').html(response.id);
					});
				  }
			});
		  };

		  // Load the SDK Asynchronously
		  (function(d){
		     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
		     if (d.getElementById(id)) {return;}
		     js = d.createElement('script'); js.id = id; js.async = true;
		     js.src = "//connect.facebook.net/en_US/all.js";
		     ref.parentNode.insertBefore(js, ref);
		   }(document));
		</script>

		<?php 
			include ('./sqlitedb.php');

			$uID = 1;

			$currFav = 1;
			$usersFavsQuery = "select * from Users U where U.uID in (select fID from Favorites F where F.uID = ".$uID." )";
			foreach ($db->query($usersFavsQuery) as $usersFavsResults) {
				echo "<div id='fav_firstName".$currFav."' style='display:none;'>".$usersFavsResults["firstName"]."</div>";
				echo "<div id='fav_lastName".$currFav."' style='display:none;'>".$usersFavsResults["lastName"]."</div>";
				echo "<div id='fav_lat".$currFav."' style='display:none;'>".$usersFavsResults["lat"]."</div>";	
				echo "<div id='fav_long".$currFav."' style='display:none;'>".$usersFavsResults["long"]."</div>";	
				$currFav = $currFav + 1;
			}
			echo "<div id='numFavs' style='display:none;'>".$currFav."</div>";

			$currnonFav = 1;
			$usersNonFavsQuery = "select * from Users U where U.uID <> ".$uID." and U.uID not in (select fID from Favorites F where F.uID = ".$uID." )";
			foreach ($db->query($usersNonFavsQuery) as $usersNonFavsResults) {
				echo "<div id='nonFav_firstName".$currNonFav."' style='display:none;'>".$usersNonFavsResults["firstName"]."</div>";
				echo "<div id='nonFav_lastName".$currNonFav."' style='display:none;'>".$usersNonFavsResults["lastName"]."</div>";
				echo "<div id='nonFav_lat".$currNonFav."' style='display:none;'>".$usersNonFavsResults["lat"]."</div>";	
				echo "<div id='nonFav_long".$currNonFav."' style='display:none;'>".$usersNonFavsResults["long"]."</div>";	
				$currNonFav = $currNonFav + 1;
			}
			echo "<div id='numNonFavs' style='display:none;'>".$currNonFav."</div>";
		?>

		<script type="text/javascript">
			var map;

			// Create the marker
			function createMarker(latlng, name, html, pin_name) {
			    var infowindow = new google.maps.InfoWindow({
    				content: html
				});

			    var marker = new google.maps.Marker({
			        position: latlng,
			        map: map,
			        title: name,
			        icon: pin_name
			    });

			    google.maps.event.addListener(marker, 'click', function() {
  					infowindow.open(map,marker);
				});
			
			    return marker;
			}
			
			function success(position) {
				console.log("The user's position is at");

			    var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
			    console.log(position.coords.latitude+ ", " +position.coords.longitude);
			    var myOptions = {
			        zoom: 15,
			        center: latlng,
			        mapTypeControl: false,
			        navigationControlOptions: {
			            style: google.maps.NavigationControlStyle.SMALL
			        },
			        mapTypeId: google.maps.MapTypeId.ROADMAP
			    };
			
			    map = new google.maps.Map(document.getElementById("mapcanvas"), myOptions);
			
			    var currUserName = $('#currUserName').text();
			    console.log ($('#currUserName').text());
			    var currUserMarker = createMarker(latlng,currUserName, "<a href='buddyprofile.php'>George Michopoulos</a>", "pin.png");
			    console.log(latlng,currUserName, "I work!", "pin.png");

			    var numFavs = $('#numFavs').text();
			    for (var i = 1; i < numFavs; i++)
				{ 
					var fav_firstName = $("#fav_firstName"+i).text();
					var fav_lastName = $("#fav_lastName"+i).text();
					var fav_lat = $("#fav_lat"+i).text();
					var fav_long = $("#fav_long"+i).text();
					var latlng = new google.maps.LatLng(fav_lat, fav_long);
					var newMarker = createMarker(latlng,fav_firstName + " " +fav_lastName,"<a href='buddyprofile.php'>Shilpa Apte</a>", "fav_pin.png");

				}

				var numNonFavs = $('#numNonFavs').text();
			    for (var j = 1; j < numNonFavs; j++)
				{ 
					var nonFav_firstName = $("#fav_firstName"+j).text();
					var nonFav_lastName = $("#fav_lastName"+j).text();
					var nonFav_lat = $("#fav_lat"+j).text();
					var nonFav_long = $("#fav_long"+j).text();
					var latlng = new google.maps.LatLng(nonFav_lat, nonFav_long);
					var newMarker = createMarker(latlng,nonFav_firstName + " " +nonFav_lastName,"Shilpa Apte", "other_pin.png");
					console.log("j is "+j, latlng,nonFav_firstName + " " +nonFav_lastName,"I work too!", "other_pin.png");
				}


			}
			
			function error(msg) {
		    var s = document.querySelector('#status');
		    s.innerHTML = typeof msg == 'string' ? msg : "failed";
		    s.className = 'fail';
			}
		
			if (navigator.geolocation) {
			    navigator.geolocation.getCurrentPosition(success, error);
			} else {
			    error('not supported');
			}	
		
		</script> 
	</div>
		
		

	<div data-role="footer" data-id="samebar" class="nav-glyphish-example" data-position="fixed" data-tap-toggle="false">
		<div data-role="navbar" class="nav-glyphish-example" data-grid="b">
		<ul>
			<li><a href="profile.php" data-icon="customprof">Profile</a></li>
			<li><a href="map.php" data-icon="custommap" class="ui-btn-active">BuddyMap</a></li>
			<li><a href="favorites.php" data-icon="customfavs">Buddies</a></li>
		</ul>
		</div>
	</div>

	<script type="text/javascript">
		setTimeout(function(){var a=document.createElement("script");
		var b=document.getElementsByTagName("script")[0];
		a.src=document.location.protocol+"//dnn506yrbagrg.cloudfront.net/pages/scripts/0013/5764.js?"+Math.floor(new Date().getTime()/3600000);
		a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);
	</script>

</body>
</html>