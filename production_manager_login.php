<?php
require_once 'header_files/header_file_common_to_all_pages.php';

if ( userHasNotPressedSubmitButton() ) {
   displayPageContentsForProductionManagerLogin();
}
else {
   $errorMessageText = validateProductionManagerLoginDetails();

   if ( $errorMessageText == NULL ) {
      logProductionManagerIn();
      header( 'Location: index.php' );
   }
   else {
      displayPageContentsForProductionManagerLogin( $errorMessageText );
   }
}
?>