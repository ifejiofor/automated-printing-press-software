<?php
require_once 'header_files/header_file_common_to_all_pages.php';

$pageContents = buildLevel2Heading( 'Members of Staff' );
$pageContents .= buildLevel3Heading( 'All members of staff of your print shop are listed below.' );

$pageContents .= buildListOfAllMembersOfStaff();

$linkToMemberOfStaffRegistrationPage =
   buildLinkThatLooksLikeButton(
   'Register&nbsp;a&nbsp;New&nbsp;Member&nbsp;of&nbsp;Staff',
   'member_of_staff_registration.php' );
$pageContents .= buildDivision( $linkToMemberOfStaffRegistrationPage );

$linkToMemberOfStaffDeletionPage =
   buildLinkThatLooksLikeButton(
   '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Delete&nbsp;a&nbsp;Member&nbsp;of&nbsp;Staff&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
   'delete_member_of_staff.php' );
$pageContents .= buildDivision( $linkToMemberOfStaffDeletionPage );

display( $pageContents );
?>