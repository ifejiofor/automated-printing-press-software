<?php
require_once 'header_files/header_file_common_to_all_pages.php';

if ( userHasNotPressedSubmitButton() ) {
   displayPageContentsForFirstStepOfJobCosting();
}
else {
   if ( $_POST['idOfJobType'] == '' ) {
      $errorMessageText = 'You must select type of job before proceeding';
      displayPageContentsForFirstStepOfJobCosting( $errorMessageText );
   }
   else {
      header( 'Location: second_step_of_job_costing.php?idOfRequiredJobType=' . $_POST['idOfJobType'] );
   }
}
?>