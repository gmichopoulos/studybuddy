<!DOCTYPE html> 
<html>

<head>
	<title>StudyBuddy</title> 
	<meta http-equiv="Content-Type" content="text/javascript; charset=utf-8"/>
	<meta charset="utf-8">
	<meta name="apple-mobile-web-app-capable" content="yes">
 	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 

	<link rel="stylesheet" href="jquery.mobile-1.2.0.css" />
	<link rel="stylesheet" href="style.css" />
	<link rel="apple-touch-icon" href="appicon.jpg" />
	<link rel="apple-touch-startup-image" href="startup.jpg">

	<script type="text/javascript" src="jquery-1.8.2.min.js" ></script>
	<script type="text/javascript" src="jquery.mobile-1.2.0.js"  ></script>

</head> 

	
<body> 

<div data-role="page" id="profile">

	<div data-role="header">
		<img src="banner.gif">
	</div> 

	<div data-role="content">	
		<div id="fb-root"></div>
		
		<ul data-role="listview">
			<li data-role=​"list-divider" role=​"heading" class=​"ui-li ui-li-divider ui-bar-b ui-first-child">​<span id="username">Divider​</span></li>​

		<script>
		  window.fbAsyncInit = function() {
		    FB.init({
		      appId      : '377088085704166', // App ID
		      status     : false, // check login status
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
					    $('#username').html(response.name + "'s Profile");
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

			$updater_uID = $_POST["uID"]; 
			$updater_cID = $_POST["cID"]; 
			$updated_status = $_POST["status"]; 
			if ($updater_uID and $updater_cID and $updated_status) {
				$db->beginTransaction();
				$updateSH = $db->prepare("update Classes set status = :updated_status where uID = :uID and cID = :cID");
				$updateSH->bindParam(':updated_status', $updated_status);
				$updateSH->bindParam(':uID', $updater_uID);
				$updateSH->bindParam(':cID', $updater_cID);
				$updateSH->execute();
				$db->commit();
			}


			$uID = 2;
			$currClass = 1;
			$userClassesQuery = "select * from Classes where uID = ".$uID;
			foreach ($db->query($userClassesQuery) as $userClassesResults) {
				echo "<li>";
					echo "<h3>".$userClassesResults["cName"]."</h3>";
					echo "<div class='profile-content' id='statusContent".$currClass."'>";
						echo "<span id='cur-status".$currClass."'>".$userClassesResults["status"]."</span>";
						echo "<span id='edit-status".$currClass."'>";
							echo "<a href='#' id='edit-status-button' data-role='button' data-inline='true' data-mini='true' button-inline='true'>Change Status</a>";
						echo "</span>";
					echo "</div>";
					echo "<div class='profile-content' id='editStatusForm".$currClass."' data-role='collapsible' data-collapsed='true'>";
						echo "<div data-role='fieldcontain'>";
							echo "<form method='POST' action='profile.php'>";
							echo "<input type='hidden' name='uID' id='updater_uID' value = '".$uID."'></input>";
							echo "<input type='hidden' name='cID' id='updater_cID' value = '".$userClassesResults["cID"]."'></input>";
							echo "<input type='text' name='status' id='new-status".$currClass."' value='' />";
							echo "<button type='submit' data-role='button' data-inline='true' data-theme='b' id='save-status".$currClass."' style='margin:auto;'>Update</button>";
							echo "<a href='#' data-role='button' data-inline='true' id='cancel-status".$currClass."'>Cancel</a>";
							echo "</form>";
						echo "</div>";
					echo "</div>";
				echo "</li>";
				
				echo "<script type='text/javascript'>";
				echo "$('#edit-status".$currClass."').click(function() {";
				echo "$('#editStatusForm".$currClass."').trigger('expand');";
				echo "$('#statusContent".$currClass."').css('display','none');";
				echo "$('#new-status".$currClass."').val($('#cur-status".$currClass."').text());";
				echo "console.log($('#cur-status".$currClass."').text());";
				echo "});";

				echo "$('#cancel-status".$currClass."').click(function() {";
				echo "$('#editStatusForm".$currClass."').trigger('collapse');";
				echo "$('#statusContent".$currClass."').css('display','none');";
				echo "$('#statusContent".$currClass."').css('display','block');";
				echo "console.log($('#cur-status".$currClass."').text());";
				echo "});";
				echo "</script>";

				$currClass = $currClass + 1;
			}
			?>
		</ul>


		<div style="padding:40px;" data-role="collapsible" data-collapsed="true">
			<h4>Add Classes</h4>
			<ul data-role="listview" data-inset="true" data-filter="true">
				<li><a href="#">CS 147</a></li>
				<li><a href="#">CS 161</a></li>
				<li><a href="#">Psych 1</a></li>
				<li><a href="#">Engr 40</a></li>
				<li><a href="#">Spanish 1</a></li>
				<li><a href="#">French 1</a></li>
				<li><a href="#">ME 101</a></li>
				<li><a href="#">PoliSci 1</a></li>
				<li><a href="#">History 50</a></li>
			</ul>
		</div>
	</div><!-- /content -->
	
	<div data-role="footer" data-id="samebar" class="nav-glyphish-example" data-position="fixed" data-tap-toggle="false">
		<div data-role="navbar" class="nav-glyphish-example" data-grid="b">
			<ul>
				<li><a href="profile.php" id="prof" data-icon="customprof" class="ui-btn-active">Profile</a></li>
				<li><a href="map.php" id="map" data-icon="custommap">BuddyMap</a></li>
				<li><a href="favorites.php" id="favs" data-icon="customfavs">Favorites</a></li>
			</ul>
		</div>
	</div>


</div><!-- /page one -->
</body>
</html>