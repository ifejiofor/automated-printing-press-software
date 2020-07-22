<?php
require_once 'header_files/header_file_common_to_all_pages.php';

if ( userHasNotPressedSubmitButton() ) {
   displayPageContentsForCustomerLogin();
}
else {
   $errorInCustomerLoginDetails = validateCustomerLoginDetails();

   if ( $errorInCustomerLoginDetails == NULL ) {
      logCustomerIn();
      header( 'Location: index.php' );
   }
   else {
      displayPageContentsForCustomerLogin( $errorInCustomerLoginDetails );
   }
}
?>