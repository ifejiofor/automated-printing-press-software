<?php
require_once 'header_files/header_file_common_to_all_pages.php';

redirectToIndexPageIfIdOfRequiredJobIsInvalid();
redirectToIndexPageIfRequiredJobHasAlreadyBeenCompleted();

if ( userHasNotPressedSubmitButton() ) {
   displayPageContentsForScheduleCreationOrModification();
}
else {
   updateWorkingScheduleWithDataInputtedByUser();
   $errorInDataInputtedByUser = validateDataStoredInWorkingSchedule();

   if ( $errorInDataInputtedByUser == NULL ) {
      performScheduleCreationOrModificationAction();
   }
   else {
      displayPageContentsForScheduleCreationOrModification( $errorInDataInputtedByUser );
   }
}
?>