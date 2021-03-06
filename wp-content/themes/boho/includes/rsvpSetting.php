<h2>Hi <?php echo $current_user->user_firstname;?></h2>
<?php
	function instructionGen($guestType){
		$str = '';
		$adviceAlert = '<br/>It is essential to book taxis and accomodation in advance as the area is quite remote';

		switch($guestType){
			case 0:
				$str = "The evening entertainment starts at 6.30pm, please feel free to arrive from 6pm." . $adviceAlert;
			break;
			case 1:
				$str = "Please arrive at 1pm to be seated before the ceremony." . $adviceAlert;
			break;
			default:
				$str = "We will provide details on when to arrive shortly!";
		}

		return $str;
	}

	$usrSettingsListings = "";
	$tabList = "";
	$subGroupUl = '<ul class="rsvp-group rsvp-group--inner-listing current" data-num="0">';
	$h3 = '<h3>Please RSVP</h3>';

	foreach ($checkRsvp as $rsvpSettingObj) {
		$tabList .= '<li class="rsvp-group rsvp-group--tab current" data-num="0">' . $rsvpSettingObj->display_name . '</li>';
		//$usrSettingsListings .= '<li class="rsvp-group rsvp-group--tab"><h4>' . $rsvpSettingObj->display_name . '</h4>';

		$attendingStr = ($rsvpSettingObj->Attending) ? "Yes" : "No";
		$usrSettingsListings .=  $subGroupUl . "<li>Attending: " . $attendingStr . "</li>";

		$vegetarianStr = ($rsvpSettingObj->Vegetarian) ? "Yes" : "No";
		$usrSettingsListings .=  "<li>Vegetarian: " . $vegetarianStr . "</li>";

		$parkingStr = ($rsvpSettingObj->ParkingCar) ? "Yes" : "No";
		$usrSettingsListings .=  "<li>Parking Car?: " . $parkingStr . "</li>";

		$notesStr = $rsvpSettingObj->Notes;
		$usrSettingsListings .=  "<li>Your comments: " . $notesStr . "</li>";

		$usrSettingsListings .=  '<li>' . instructionGen($rsvpSettingObj->DayGuest) . "</li>";
		$usrSettingsListings .=  "<li><a href='/rsvp?edit_rsvp=true' class='fa fa-pencil-square-o'>Edit your settings?</a></li></ul></li>";
	}
	

	if(isset($hasChildren) && $hasChildren > 0){
		$h3 = '<h3>RSVP for you and your group by ' . RSVP_DATE . '</h3>';
		foreach ($checkChild as $checkChildKey=>$child) {
			$dataNum = $checkChildKey + 1;
			$subGroupUl = '<ul class="rsvp-group rsvp-group--inner-listing"  data-num="' . $dataNum . '">';
			$tabList .= '<li class="rsvp-group rsvp-group--tab" data-num="' . $dataNum . '">' . $child->display_name . '</li>';
			$linkToChildUsr = '<a href="/rsvp?childId=' . $child->UserId . '&edit_child=true" title="' . $child->display_name . '" class="fa fa-pencil-square-o">Edit this user</a>';
			$attendingStatus = ($child->Attending) ? "Yes" : "No";
			$vegStatus = ($child->Vegetarian) ? "Yes" : "No";
			$parkStatus = ($child->ParkingCar) ? "Yes" : "No";
			$notes = (strlen($child->Notes)) ? $child->Notes : "None";
			//$currentClass = ($checkChildKey===0) ? ' class="currentUsr"' : '';
			$usrSettingsListings .=  $subGroupUl . '<li>Attending: ' . $attendingStatus . '</li><li>Vegetarian: ' . $vegStatus . '</li><li>Parking Car?: ' . $parkStatus . '</li><li>Your Notes: ' . $notes . '</li>';
			$usrSettingsListings .=  '<li>' . instructionGen($child->DayGuest) . "</li>";
			$usrSettingsListings .=  '<li>' . $linkToChildUsr . '</li>';
			$usrSettingsListings .= "</ul></li>";
		}
		
	}
	if(!$hasChildren){
		$tabList = "";//no need to show this
	}else{
		$tabList = '<ul class="rsvp-group rsvp-group-list">' . $tabList . '</ul>';
	}

	echo $h3 . '<div class="rsvp-group rsvp-group--holder">' . $tabList . $usrSettingsListings . '</div>';

	if(current_user_can('create_users')){//only admins can create users
		echo "<h4>Only admins can use these links</h4>";
		echo "<br /><a href='" . get_permalink() . "?edit_is_day_guest=true'>Edit guests types</a>";
		echo '<br /><a href="/rsvp/?edit_guest_relationship=true">Edit Family Connections<a>';
		echo "<br /><a href='" . get_permalink() . "?show_responses=true' class='fa fa-pencil-square-o'>See RSVP responses</a>";
	}
?>
<script>
require(['app/rsvpTabs']);
</script>