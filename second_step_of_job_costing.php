<?php
require_once 'header_files/header_file_common_to_all_pages.php';
redirectToIndexPageIfIdOfRequiredJobTypeIsInvalid();

if ( userHasNotPressedSubmitButton() ) {
   displayPageContentsForSecondStepOfJobCosting();
}
else {
   $errorMessageText = validateJobCostingDetails();
 
   if ( $errorMessageText == NULL ) {
      $jobCost = calculateJobCost();
      displayJobCost( $jobCost );
   }
   else {
      displayPageContentsForSecondStepOfJobCosting( $errorMessageText );
   }
}
?>