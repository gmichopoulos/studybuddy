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
	
		<!-- This is the magic script, usually I'd put it near the header -->
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
		
			
		<!-- This is where the map gets inserted -->
		<div id="mapcanvas" style="height:100%;width:100%;position:absolute"></div>
		

		<script type="text/javascript">
			var map;
			var markerNames = new Array("George Michopoulos", "Dan Schwartz");
			var markerLocations = [[37.4225, -122.1653],[37.4235, -122.1753]];

			// Create the marker
			function createMarker(latlng, name, html) {
			    var infowindow = new google.maps.InfoWindow({
    				content: html
				});

			    var marker = new google.maps.Marker({
			        position: latlng,
			        map: map,
			        title: name,
			        icon: 'pin.png'
			    });

			    google.maps.event.addListener(marker, 'click', function() {
  					infowindow.open(map,marker);
				});
			
			    return marker;
			}
			
			function success(position) {
				console.log("The user's position is at");

			    var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
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
			
			    var marker = createMarker(latlng,"Shilpa Apte", "I work!");
			    for (var i=0;i<markerNames.length;i++)
				{ 
					var latlng = new google.maps.LatLng(markerLocations[i][0], markerLocations[i][1]);
					var newMarker = createMarker(latlng,markerNames[i],"I work too!");
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
			<li><a href="profile.php" id="profile" data-icon="custom">Profile</a></li>
			<li><a href="map.php" id="map" data-icon="custom" class="ui-btn-active">BuddyMap</a></li>
			<li><a href="favorites.php" id="favs" data-icon="custom">Favorites</a></li>
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