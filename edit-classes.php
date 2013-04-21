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
		<h1>Edit Classes</h1>
		<a href="profile.php" data-icon="back" class="ui-btn-left">Profile</a>
	</div>

	<div data-role="content">	
		<div id="fb-root"></div>
		<div id="uID"></div>

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

			<form method="POST" action="profile.php">
				<h4>Edit Classes</h4>
				<div data-role="fieldcontain">
				 	<fieldset data-role="controlgroup">
				 		<?php
							include ('./sqlitedb.php');
							$uID = 2;
							$currClass = 1;
							$userClassesQuery = "select * from Classes C join Statuses S where C.cID = S.cID and S.uID = ".$uID." order by C.cName";
							foreach ($db->query($userClassesQuery) as $userClassesResults) {
								echo "<input type='checkbox' id='checkbox".$currClass."' name='".$userClassesResults[cID]."' value='".$userClassesResults[cName]."' class='custom' checked />";
								echo "<label for='checkbox".$currClass."'>".$userClassesResults[cName]."</label>";
								$currClass = $currClass + 1;
							}
							$otherClassesQuery = "select distinct cID, cName from Classes where cID not in (select cID from Statuses where uID = ".$uID.") order by cName";
							foreach ($db->query($otherClassesQuery) as $otherClassesResults) {
								echo "<input type='checkbox' id='checkbox".$currClass."' name='".$otherClassesResults[cID]."' value='".$otherClassesResults[cName]."' class='custom' />";
								echo "<label for='checkbox".$currClass."'>".$otherClassesResults[cName]."</label>";
								$currClass = $currClass + 1;
							}
						?>
					</fieldset>
				</div>
				<input type="hidden" name="classIDs" id="selectedClassIDs" value= ""></input>
				<button type="submit" data-theme="b" name="submit" value="submit-value" class="ui-btn-hidden" data-disabled="false">Submit</button>
			</form>

			<script>

			function updateSelectedClasses() {         
				var classIDs = "";
				$("input[type='checkbox']").each(function() {
					if ($(this).attr('checked'))
						if (classIDs == "") {
							classIDs = $(this).attr('name');
						} else {
							classIDs = classIDs + "," + $(this).attr('name');
						}
			     });
				console.log("classes is "+classIDs);
			    $('#selectedClassIDs').val(classIDs);
				console.log($('#selectedClassIDs').val());
			}
			$(function() {
				   $("input[type='checkbox']").click(updateSelectedClasses);
				   updateSelectedClasses();
			});
			</script>
	</div><!-- /content -->

</div><!-- /page one -->
</body>
</html>