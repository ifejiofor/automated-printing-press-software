<?php
require_once 'header_files/header_file_common_to_all_pages.php';

if ( userHasNotPressedSubmitButton() ) {
   displayPageContentsForCustomerRegistration();
}
else {
   $errorMessageText = validateCustomerRegistrationDetails();

   if ( $errorMessageText == NULL ) {
      registerNewCustomer();
      header( 'Location: customer_login.php?NewCustomerHasJustBeenRegistered' );
   }
   else {
      displayPageContentsForCustomerRegistration( $errorMessageText );
   }
}
?>