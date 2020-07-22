<?php
require_once 'header_files/header_file_common_to_all_pages.php';

if ( userHasNotPressedSubmitButton() ) {
   displayPageContentsForMemberOfStaffLogin();
}
else {
   $errorInMemberOfStaffLoginDetails = validateMemberOfStaffLoginDetails();

   if ( $errorInMemberOfStaffLoginDetails == NULL ) {
      logMemberOfStaffIn();
      header( 'Location: index.php' );
   }
   else {
      displayPageContentsForMemberOfStaffLogin( $errorInMemberOfStaffLoginDetails );
   }
}
?>