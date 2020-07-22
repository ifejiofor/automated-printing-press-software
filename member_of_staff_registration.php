<?php
require_once 'header_files/header_file_common_to_all_pages.php';

if ( userHasNotPressedSubmitButton() ) {
   displayPageContentsForMemberOfStaffRegistration();
}
else {
   $errorMessageInRegistrationDetails = validateMemberOfStaffRegistrationDetails();

   if ( $errorMessageInRegistrationDetails != NULL ) {
      displayPageContentsForMemberOfStaffRegistration( $errorMessageInRegistrationDetails );
   }
   else {
      unsetDataAboutAllMembersOfStaffFromSession();
      $idOfMemberOfStaffThatWasJustRegistered = registerNewMemberOfStaff();
      header( 'Location: list_of_all_members_of_staff.php#' . $idOfMemberOfStaffThatWasJustRegistered );
   }
}
?>