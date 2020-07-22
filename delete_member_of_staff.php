<?php
require_once 'header_files/header_file_common_to_all_pages.php';

if ( userHasNotPressedSubmitButton() ) {
   displayPageContentsForDeletingMemberOfStaff();
}
else {
   $errorMessageText = NULL;
   if ( $_POST['idOfMemberOfStaffToBeDeleted'] == '' ) {
      $errorMessageText = 'Please, select the member of staff to be deleted';
   }

   $rowsOfSubtasksAssignedToMemberOfStaffToBeDeleted =
      retrieveRowsOfDataAboutSubtasksAssignedToMemberOfStaff( $_POST['idOfMemberOfStaffToBeDeleted'] );

   if ( sizeof( $rowsOfSubtasksAssignedToMemberOfStaffToBeDeleted ) > 0 ) {
      $errorMessageText =
         'Before you can delete the selected member of staff, you have to unassign him/her from all assigned subtasks';
   }

   if ( $errorMessageText != NULL ) {
      displayPageContentsForDeletingMemberOfStaff( $errorMessageText );
   }
   else {
      header( 'Location: confirm_deletion_of_member_of_staff.php' .
         '?idOfRequiredMemberOfStaff=' . $_POST['idOfMemberOfStaffToBeDeleted'] );
   }
}
?>