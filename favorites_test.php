<!DOCTYPE html> 
<html>

<head>
	<title>StudyBuddy</title> 
	<meta http-equiv="Content-Type" content="text/javascript; charset=utf-8"/>
	<meta charset="utf-8" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
 	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="viewport" content="width=device-width, initial-scale=1" /> 

	<link rel="stylesheet" href="jquery.mobile-1.2.0.css" />
	<link rel="stylesheet" href="style.css" />
	<link rel="apple-touch-icon" href="appicon.jpg" />
	<link rel="apple-touch-startup-image" href="startup.jpg" />

	<script type="text/javascript" src="jquery-1.8.2.min.js" ></script>
	<script type="text/javascript" src="jquery.mobile-1.2.0.js"  ></script>
</head> 

<div data-role="page" id="favs">

	<div data-role="header">
		<img src="banner.gif" />
	</div> 

	<div data-role="content">
		<ul data-role="listview" data-split-icon="minus" data-split-theme="d">

		<?php 
			include ('./sqlitedb.php');

			$uID = 1;
			$currClass = 1;
			$usersFavsQuery = "select fID from Favorites F join Users U where F.uID = U.uID and F.uID = ".$uID;
			foreach ($db->query($usersFavsQuery) as $usersFavsResults) {
				$favsInfoSH = $db->prepare("select * from Users where uID = :fID");
				$favsInfoSH->execute(array(":fID" => $usersFavsResults["fID"]));
				$favsInfo = $favsInfoSH->fetch();
				echo "<li>";
				echo "<a href='index.html'>";
					echo "<img src='images/no_user.jpg' />";
					echo "<h3>".$favsInfo["firstName"]." ".$favsInfo["lastName"]."</h3>";
					
					$favsClassQuery = "select * from Users U join Classes C where U.uID = C.uID and U.uID = ".$usersFavsResults["fID"];
					foreach ($db->query($favsClassQuery) as $favsClassresults) {

						echo "<p>".$favsClassresults["cName"].": ".$favsClassresults["status"]."</p>";
					}
				echo "</a><a href='#removeFav' data-rel='popup' data-position-to='window' data-transition='pop'>Remove Favorite</a>";
				echo "</li>";
			}
		?>

		</ul>
	
		<div data-role="popup" id="removeFav" data-theme="d" data-overlay-theme="b" class="ui-content" style="max-width:340px;">
			<h3>Remove From Favorites?</h3>
			<p>You will have to manually add this person again to undo this action.</p>
			<a href="index.html" data-role="button" data-rel="back" data-theme="b" data-icon="check" data-inline="true" data-mini="true">Remove</a>
			<a href="index.html" data-role="button" data-rel="back" data-inline="true" data-mini="true">Cancel</a>	
		</div>
	</div><!--/content -->	

	<div data-role="footer" data-id="samebar" class="nav-glyphish-example" data-position="fixed" data-tap-toggle="false">
		<div data-role="navbar" class="nav-glyphish-example" data-grid="b">
		<ul>
			<li><a href="profile.php" id="profile" data-icon="customprof">Profile</a></li>
			<li><a href="map.php" id="map" data-icon="custommap">BuddyMap</a></li>
			<li><a href="favorites.php" id="favs" data-icon="customfavs" class="ui-btn-active">Favorites</a></li>
		</ul>
		</div>
	</div>
	
	
</div><!-- /page -->

</body>
</html>







