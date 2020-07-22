<?php
require_once 'header_files/header_file_common_to_all_pages.php';
redirectToIndexPageIfIdOfRequiredScheduledSubtaskIsInvalid();
redirectToIndexPageIfRequiredSubtaskWasNotAssignedToLoggedInMemberOfStaff();
redirectToIndexPageIfRequiredScheduledSubtaskIsNotInProgress();

if ( userHasNotPressedAssertionButton() ) {
   displayPageContentsForConfirmingRequiredScheduledSubtaskAsCompleted();
}
else {
   markRequiredScheduledSubtaskAsCompleted();
   header( 'Location: list_of_subtasks_assigned_to_member_of_staff.php' );
}
?>