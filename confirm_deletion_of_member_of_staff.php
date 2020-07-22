<?php
require_once 'header_files/header_file_common_to_all_pages.php';
redirectToIndexPageIfIdOfRequiredMemberOfStaffIsInvalid();

if ( userHasNotPressedAssertionButton() ) {
   displayPageContentsForConfirmingDeletionOfRequiredMemberOfStaff();
}
else {
   deleteDataAboutMemberOfStaff( $_GET['idOfRequiredMemberOfStaff'] );
   unsetDataAboutAllMembersOfStaffFromSession();
   header( 'Location: list_of_all_members_of_staff.php' );
}
?>