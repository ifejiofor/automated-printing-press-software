<?php
require_once 'header_files/header_file_common_to_all_pages.php';

if ( userIsLoggedInAsMemberOfStaff() ) {
   $idOfMemberOfStaff = $_SESSION['idOfLoggedInUser'];

   $pageContents = buildLevel2HeadingAppendedWithNameOfLoggedInMemberOfStaff( 'Welcome, ' );
   $pageContents .= buildLevel3Heading( 'All subtasks assigned to you are listed below.' );
}
else if ( userIsLoggedInAsProductionManager() ) {
   redirectToIndexPageIfIdOfRequiredMemberOfStaffIsInvalid();
   $idOfMemberOfStaff = $_GET['idOfRequiredMemberOfStaff'];

   $pageContents = buildLevel2HeadingAppendedWithNameOfRequiredMemberOfStaff( 'Subtasks Assigned to ' );
}

$pageContents .= buildNavigationForNavigatingThroughSubtasks();
$pageContents .= buildListOfSubtasksAssignedToMemberOfStaff( $idOfMemberOfStaff );

display( $pageContents );
?>