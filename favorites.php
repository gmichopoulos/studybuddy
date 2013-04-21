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
		<div data-role="popup" id="removeFav" data-theme="d" data-overlay-theme="b" class="ui-content" style="max-width:340px;">
			<h3>Remove From Favorites?</h3>
			<p>You will have to manually add this person again to undo this action.</p>
			<form method="POST" action="favorites.php">
			<input type="hidden" name="uID" id="removed_uID" value = ""></input>
			<input type="hidden" name="fID" id="removed_fID" value = ""></input>
			<button type=​"submit" data-role="button" data-theme=​"b" name=​"submit" value=​"submit-value" data-inline="true" data-mini="true" data-disabled=​"false">Remove</button>​
			<a href="index.html" data-role="button" data-rel="back" data-inline="true" data-mini="true">Cancel</a>

			</form>	
		</div>

		<ul data-role="listview" data-split-icon="minus" data-split-theme="d">

		<?php 
			include ('./sqlitedb.php');

			$removed_uID = $_POST["uID"]; 
			$removed_fID = $_POST["fID"]; 
			if ($removed_uID and $removed_fID) {
				$db->beginTransaction();
				$removeSH = $db->prepare("delete from Favorites where uID = :uID and fID = :fID");
				$removeSH->execute(array(":uID" => $removed_uID, ":fID" => $removed_fID));
				$db->commit();
			}


			$uID = 3;
			$currFav = 1;
			$usersFavsQuery = "select fID from Favorites F join Users U where F.uID = U.uID and F.uID = ".$uID;
			foreach ($db->query($usersFavsQuery) as $usersFavsResults) {
				$favsInfoSH = $db->prepare("select * from Users where uID = :fID");
				$favsInfoSH->execute(array(":fID" => $usersFavsResults["fID"]));
				$favsInfo = $favsInfoSH->fetch();
				echo "<li>";
				echo "<a href='map.php'>";
					echo "<div id='uID".$currFav."' style='display:none;'>".$uID."</div>";
					echo "<div id='fID".$currFav."' style='display:none;'>".$usersFavsResults["fID"]."</div>";
					echo "<img src='images/no_user.jpg' />";
					echo "<h3>".$favsInfo["firstName"]." ".$favsInfo["lastName"]."</h3>";

					$favsClassQuery = "select * from Users U join Statuses S where U.uID = S.uID and U.uID = ".$usersFavsResults["fID"];
					foreach ($db->query($favsClassQuery) as $favsClassresults) {
						$favsClassInfoSH = $db->prepare("select * from Classes where cID = :cID");
						$favsClassInfoSH->execute(array(":cID" => $favsClassresults["cID"]));
						$favsClassInfo = $favsClassInfoSH->fetch();
						echo "<p>".$favsClassInfo["cName"].": ".$favsClassresults["status"]."</p>";
					}
				echo "</a><a href='#removeFav' id='removeInit".$currFav."' data-rel='popup' data-position-to='window' data-transition='pop'>Remove Favorite</a>";
				echo "</li>";
				echo "<script type='text/javascript'>";
				echo "$('#removeInit".$currFav."').click(function() {";
				echo "$('#removed_uID').val($('#uID".$currFav."').text());"; 
				echo "$('#removed_fID').val($('#fID".$currFav."').text());"; 
				echo "console.log($('#removed_uID').val());";
				echo "console.log($('#removed_fID').val());";
				echo "});";
				echo "</script>";
				$currFav = $currFav + 1;
			}
		?>

		</ul>

	</div><!--/content -->	

	<div data-role="footer" data-id="samebar" class="nav-glyphish-example" data-position="fixed" data-tap-toggle="false">
		<div data-role="navbar" class="nav-glyphish-example" data-grid="b">
		<ul>
			<li><a href="profile.php" id="profile" data-icon="customprof">Profile</a></li>
			<li><a href="map.php" id="map" data-icon="custommap">BuddyMap</a></li>
			<li><a href="favorites.php" id="favs" data-icon="customfavs" class="ui-btn-active">Buddies</a></li>
		</ul>
		</div>
	</div>
	
	
</div><!-- /page -->

</body>
</html>

