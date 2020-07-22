<?php
require_once 'header_files/header_file_common_to_all_pages.php';

if ( userHasNotPressedSubmitButton() ) {
   displayPageContentsForJobRegistration();
}
else {
   $errorMessageInJobRegistrationDetails = validateJobRegistrationDetails();

   if ( $errorMessageInJobRegistrationDetails == NULL ) {
      registerNewJob();
      header( 'Location: job_registration.php' );
   }
   else {
      displayPageContentsForJobRegistration( $errorMessageInJobRegistrationDetails );
   }
}
?>