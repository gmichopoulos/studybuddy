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

		<table id="userInfo">	
			<div id="uID" style="display:none"></div>
			<tr>
				<td id="profPic"><img src='images/no_user.jpg' /></td>
				<td id="username">Profile Page</td>​
			</tr>
		</table>
		<br/>

		<ul data-role="listview">

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
					    $('#uID').html(response.id);
					    $('#profPic').html("<img src='http://graph.facebook.com/" + response.id + "/picture' >");
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
			$uID = 2;
			$updater_uID = $_POST["uID"]; 
			$updater_cID = $_POST["cID"]; 
			$updated_status = $_POST["status"]; 

			if ($updater_uID and $updater_cID and $updated_status) {
				$db->beginTransaction();
				$updateSH = $db->prepare("update Statuses set status = :updated_status where uID = :uID and cID = :cID");
				$updateSH->bindParam(':updated_status', $updated_status);
				$updateSH->bindParam(':uID', $updater_uID);
				$updateSH->bindParam(':cID', $updater_cID);
				$updateSH->execute();
				$db->commit();
			}

			$updated_classes = $_POST["classIDs"];
			if ($updated_classes) {
				$classIDs = explode(",", $updated_classes);
				foreach ($classIDs as $classID){
					$userClassesQuery = "select * from Statuses where uID = ".$uID." and cID = ".$classID;
					$checkClassResult = $db->query($userClassesQuery);
					//echo "classID is ".$classID." and statusCount is ".count($checkClassResult->fetchAll());
					if (count($checkClassResult->fetchAll()) == 0){
						//echo "classID is ".$classID." and statusCount should be 0 and is ".count($checkClassResult->fetchAll());
						$db->beginTransaction();
						$insertSH = $db->prepare("insert into Statuses values(:uID,:cID,NULL)");
						$insertSH->execute(array(":uID" => $uID, ":cID" => $classID));
						$db->commit();
					} else {
						//echo "classID is ".$classID." and statusCount should be 1 and is ".count($checkClassResult->fetchAll());
						$db->beginTransaction();
						$deleteSH = $db->prepare("delete from Statuses where uID = :uID and cID = :cID");
						$deleteSH->execute(array(":uID" => $uID, ":cID" => $classID));
						$db->commit();
					}

				}
			}

			$currClass = 1;
			$userClassesQuery = "select S.cID as cID, cName, status from Statuses S join Classes C where S.cID = C.cID and uID = ".$uID;
			foreach ($db->query($userClassesQuery) as $userClassesResults) {
				echo "<li>";
					echo "<h3>".$userClassesResults["cName"]."</h3>";
					echo "<div class='profile-content' id='statusContent".$currClass."'>";
						echo "<span id='cur-status".$currClass."'>".$userClassesResults["status"]."</span>";
						echo "<span id='edit-status".$currClass."'>";
							echo "<a href='#' id='edit-status-button' data-role='button' data-inline='true' data-mini='true' button-inline='true' onclick='editStatus($currClass);'>Change Status</a>";
						echo "</span>";
					echo "</div>";
					echo "<div class='profile-content' id='editStatusForm".$currClass."' data-role='collapsible' data-collapsed='true'>";
						echo "<div data-role='fieldcontain'>";
							echo "<form method='POST' action='profile.php'>";
							echo "<input type='hidden' name='uID' id='updater_uID' value = '".$uID."'></input>";
							echo "<input type='hidden' name='cID' id='updater_cID' value = '".$userClassesResults["cID"]."'></input>";
							echo "<input type='text' name='status' id='new-status".$currClass."' value='' />";
							echo "<button type='submit' data-role='button' data-inline='true' data-theme='b' id='save-status".$currClass."' style='margin:auto;'>Update</button>";
							echo "<a href='#' data-role='button' data-inline='true' id='cancel-status-button' onclick='cancelEdit($currClass);'>Cancel</a>";
							echo "</form>";
						echo "</div>";
					echo "</div>";
				echo "</li>";

				$currClass = $currClass + 1;
			}
		?>

		</ul><br/>

		<div id="edit-classes"><a href="edit-classes.php" data-role="button" data-icon="gear">Edit Classes</a></div>


		<script>

		function editStatus(id) {
			$('#editStatusForm' + id).trigger('expand');
			$('#editStatusForm' + id).trigger('expand');
			$('#statusContent'+ id).css('display','none');
			$('#new-status'+ id).val($('#cur-status'+ id).text());
		}

		function cancelEdit(id) {
			$('#editStatusForm'+ id).trigger('collapse');
			$('#statusContent'+ id).css('display','none');
			$('#statusContent'+ id).css('display','block');
		}

		</script>

	</div><!-- /content -->
	
	<div data-role="footer" data-id="samebar" class="nav-glyphish-example" data-position="fixed" data-tap-toggle="false">
		<div data-role="navbar" class="nav-glyphish-example" data-grid="b">
			<ul>
				<li><a href="profile.php" id="prof" data-icon="customprof" class="ui-btn-active">Profile</a></li>
				<li><a href="map.php" id="map" data-icon="custommap">BuddyMap</a></li>
				<li><a href="favorites.php" id="favs" data-icon="customfavs">Buddies</a></li>
			</ul>
		</div>
	</div>


</div><!-- /page one -->
</body>
</html>