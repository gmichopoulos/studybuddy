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
		<h1>Buddy's Profile</h1>
		<a href="map.php" data-icon="back" class="ui-btn-left">BuddyMap</a>
	</div>
	<div data-role="content">	
		<div id="fb-root"></div>
		<table id="userInfo">	
			<tr>

			</tr>
		</table>


		<ul data-role="listview">
			<li>
				<div id="uID" style="display:none"></div>
				<div id="profPic"><img src='images/no_user.jpg' /></div>
				<div id="username">Profile Page</div>
			</li>

		<?php 
			include ('./sqlitedb.php');

			
			$uID = 3;
			$currClass = 1;
			$userClassesQuery = "select S.cID as cID, cName, status from Statuses S join Classes C where S.cID = C.cID and uID = ".$uID;
			foreach ($db->query($userClassesQuery) as $userClassesResults) {
				echo "<li>";
					echo "<h3>".$userClassesResults["cName"]."</h3>";
					echo "<div class='profile-content' id='statusContent".$currClass."'>";
						echo "<span id='cur-status".$currClass."'>".$userClassesResults["status"]."</span>";
						echo "<span id='edit-status".$currClass."'>";
						echo "</span>";
					echo "</div>";
				echo "</li>";

				$currClass = $currClass + 1;
			}
		?>


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

		</ul><br/>
	</div>
</div>
</body>
</html>





