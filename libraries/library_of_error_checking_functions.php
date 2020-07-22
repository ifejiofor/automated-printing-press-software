<?php
function validateDataStoredInWorkingSchedule()
{
   for ( $serialNumber = $_SESSION['serialNumberOfFirstSubtaskInWorkingSchedule']; 
      $serialNumber <= $_SESSION['serialNumberOfLastSubtaskInWorkingSchedule']; $serialNumber++ )
   {
      if ( isset( $_POST['serialNumberOfSubtaskToBeDeleted'] ) &&
         $_POST['serialNumberOfSubtaskToBeDeleted'] == $serialNumber )
      {
         continue;
      }

      $errorMessage = validateWhetherEmptyDataIsStoredAboutSubtaskInWorkingSchedule( $serialNumber );

      if ( $errorMessage != NULL ) {
         return $errorMessage;
      }

      $errorMessage = validateWhetherInvalidTimeIsStoredAboutSubtaskInWorkingSchedule( $serialNumber );

      if ( $errorMessage != NULL ) {
         return $errorMessage;
      }
      
      $errorMessage = validateWhetherInconsistentTimeIsStoredAboutSubtaskInWorkingSchedule( $serialNumber );

      if ( $errorMessage != NULL ) {
         return $errorMessage;
      }

      $errorMessage = validateWhetherSubtaskIsRepeatedInWorkingSchedule( $serialNumber );

      if ( $errorMessage != NULL ) {
         return $errorMessage;
      }
   }

   return NULL;
}


function validateWhetherEmptyDataIsStoredAboutSubtaskInWorkingSchedule( $serialNumberOfSubtask )
{
   if ( $_SESSION['idOfSubtaskInWorkingSchedule'.$serialNumberOfSubtask] == '' ) {
      return 'Please, select a value for "Subtask ' . $serialNumberOfSubtask . '"';
   }

   if ( $_SESSION['idOfMemberOfStaffInWorkingSchedule'.$serialNumberOfSubtask] == '' ) {
      return 'Please, select the member of staff who is to perform "Subtask ' . $serialNumberOfSubtask . '"';
   }

   if ( $_SESSION['proposedStartTimeInWorkingSchedule'.$serialNumberOfSubtask] == '' ) {
      return 'Please, input the proposed start time of "Subtask ' . $serialNumberOfSubtask . '"';
   }

   if ( $_SESSION['proposedCompletionTimeInWorkingSchedule'.$serialNumberOfSubtask] == '' ) {
      return 'Please, input the proposed completion time of "Subtask ' . $serialNumberOfSubtask . '"';
   }

   return NULL;
}


function validateWhetherInvalidTimeIsStoredAboutSubtaskInWorkingSchedule( $serialNumberOfSubtask )
{
   $errorInProposedStartTime =
      validateTime( $_SESSION['proposedStartTimeInWorkingSchedule'.$serialNumberOfSubtask] );

   if ( $errorInProposedStartTime != NULL ) {
      return 'Invalid proposed start time for "Subtask ' . $serialNumberOfSubtask . '"';
   }

   $errorInProposedCompletionTime =
      validateTime( $_SESSION['proposedCompletionTimeInWorkingSchedule'.$serialNumberOfSubtask] );

   if ( $errorInProposedCompletionTime != NULL ) {
      return 'Invalid proposed completion time for "Subtask ' . $serialNumberOfSubtask . '"';
   }

   return NULL;
}


function validateWhetherInconsistentTimeIsStoredAboutSubtaskInWorkingSchedule( $serialNumberOfSubtask )
{
   $time1 = $_SESSION['proposedStartTimeInWorkingSchedule'.$serialNumberOfSubtask];
   $time2 = $_SESSION['proposedCompletionTimeInWorkingSchedule'.$serialNumberOfSubtask];

   if ( determineWhichTimeIsEarlier( $time1, $time2 ) == TIME_2_IS_EARLIER ) {
      return
      'The proposed start time you selected for "Subtask ' . $serialNumberOfSubtask . '" ' .
      'is not earlier than the proposed completion time';
   }

   if ( $serialNumberOfSubtask > 1 ) {
      $serialNumberOfPreviousSubtask = $serialNumberOfSubtask - 1;

      if ( $serialNumberOfPreviousSubtask < $_SESSION['serialNumberOfFirstSubtaskInWorkingSchedule'] ) {
         $idOfPreviousSubtask =
            retrieveIdOfSubtaskScheduledForJob( $_GET['idOfRequiredJob'], $serialNumberOfPreviousSubtask );
         $proposedCompletionTimeOfPreviousSubtask =
            retrieveProposedCompletionTimeOfSubtaskScheduledForJob( $_GET['idOfRequiredJob'], $idOfPreviousSubtask );
      }
      else {
         $proposedCompletionTimeOfPreviousSubtask =
            $_SESSION['proposedCompletionTimeInWorkingSchedule'.$serialNumberOfPreviousSubtask];
      }

      $time1 = $proposedCompletionTimeOfPreviousSubtask;
      $time2 = $_SESSION['proposedStartTimeInWorkingSchedule'.$serialNumberOfSubtask];

      if ( determineWhichTimeIsEarlier( $time1, $time2 ) == TIME_2_IS_EARLIER ) {
         return
            '"Subtask ' . $serialNumberOfSubtask . '" can only start after ' . 
            'the completion time of "Subtask ' . $serialNumberOfPreviousSubtask . '"';
      }
   }

   return NULL;
}


function validateWhetherSubtaskIsRepeatedInWorkingSchedule( $serialNumberOfSubtask )
{
   $idOfSubtaskBeingValidated = $_SESSION['idOfSubtaskInWorkingSchedule'.$serialNumberOfSubtask];
   $rowsOfDataAboutScheduledSubtasksThatAreCompleted =
      retrieveRowsOfDataAboutSubtasksScheduledForJobAndAreCompleted( $_GET['idOfRequiredJob'] );

   for ( $i = 0; $i < sizeof( $rowsOfDataAboutScheduledSubtasksThatAreCompleted ); $i++ ) {
      $row = $rowsOfDataAboutScheduledSubtasksThatAreCompleted[$i];

      if ( $row['subtask_id'] == $idOfSubtaskBeingValidated ) {
         return 'The same subtask cannot be selected as both "Subtask ' .$row['scheduled_subtask_serialnumber'] . '"
            and "Subtask ' . $serialNumberOfSubtask . '"';
      }
   }

   for ( $serialNumberOfNextSubtask = $serialNumberOfSubtask + 1; 
      $serialNumberOfNextSubtask <= $_SESSION['serialNumberOfLastSubtaskInWorkingSchedule'];
      $serialNumberOfNextSubtask++ )
   {
      if ( $_SESSION['idOfSubtaskInWorkingSchedule'.$serialNumberOfNextSubtask] == $idOfSubtaskBeingValidated ) {
         return 'The same subtask cannot be selected as both "Subtask ' .$serialNumberOfSubtask . '"
            and "Subtask ' . $serialNumberOfNextSubtask . '"';
      }
   }

   return NULL;
}


function validateJobCostingDetails()
{
   if ( isCodexJob( $_GET['idOfRequiredJobType'] ) ) {
      return validateJobCostingDetailsForCodexJob();
   }
   else {
      return validateJobCostingDetailsForSheetJob();
   }
}


function validateJobCostingDetailsForCodexJob()
{
   if ( $_POST['pageSize'] == '' ) {
      return 'Please, select page size';
   }

   if ( $_POST['colourOfCover'] == '' ) {
      return 'Please, select colour of cover';
   }

   if ( $_POST['colourOfInsidePages'] == '' ) {
      return 'Please, select colour of inside pages.';
   }

   if ( $_POST['numberOfInsidePages'] == '' ) {
      return 'Please, specify number of inside pages.';
   }

   if ( $_POST['numberOfCopies'] == '' ) {
      return 'Please, specify number of copies.';
   }

   if ( doesNotConsistOfOnlyDigits( $_POST['numberOfInsidePages'] ) ) {
      return 'You specified an invalid number of inside pages';
   }

   if ( doesNotConsistOfOnlyDigits( $_POST['numberOfCopies'] ) ) {
      return 'You specified an invalid number of copies';
   }

   return NULL;
}


function validateJobCostingDetailsForSheetJob()
{
   if ( $_POST['paperSize'] == '' ) {
      return 'Please, select paper size';
   }

   if ( $_POST['colour'] == '' ) {
      return 'Please, select colour';
   }

   if ( $_POST['numberOfPrintedSides'] == '' ) {
      return 'Please, select whether the job will involve single or double sides of paper';
   }

   if ( $_POST['numberOfCopies'] == '' ) {
      return 'Please, specify number of copies.';
   }

   if ( doesNotConsistOfOnlyDigits( $_POST['numberOfCopies'] ) ) {
      return 'You specified an invalid number of copies';
   }

   return NULL;
}


function validateJobRegistrationDetails()
{
   if ( $_POST['nameOfJob'] == '' ) {
      return 'Please, enter the name of the new print job';
   }

   if ( $_POST['idOfJobType'] == '' ) {
      return 'Please, select the print job type';
   }

   if ( $_POST['customerEmail'] == '' ) {
      return 'Please, enter the email of the customer who owns the job';
   }

   $errorMessageInNameOfJob = validateNameOfJob( $_POST['nameOfJob'] );

   if ( $errorMessageInNameOfJob != NULL ) {
      return $errorMessageInNameOfJob;
   }

   $errorMessageInCustomerEmail = validateEmail( $_POST['customerEmail'] );

   if ( $errorMessageInCustomerEmail != NULL ) {
      return $errorMessageInCustomerEmail;
   }

   $nameOfCustomer = retrieveNameOfCustomer( $_POST['customerEmail'] );

   if ( $nameOfCustomer == NO_ROW_FOUND ) {
      return 'The email you entered does not belong to any customer';
   }

   return NULL;
}


function validateProductionManagerLoginDetails()
{
   if ( $_POST['password'] == '' ) {
      return 'Please, enter the Production Manager Security Password.';
   }

   $passwordReadFromFile =
      readLineOfTextFromDataFile( 'production_manager.dat', LINE_NUMBER_OF_LINE_WHERE_PASSWORD_IS_STORED );

   if ( password_verify( $_POST['password'], $passwordReadFromFile ) == false ) {
      return 'Incorrect password.';
   }

   return NULL;
}


function validateMemberOfStaffRegistrationDetails()
{
   if ( $_POST['firstName'] == '' ) {
      return 'Please, enter member of staff first name';
   }

   if ( $_POST['lastName'] == '' ) {
      return 'Please, enter member of staff last name';
   }

   if ( $_POST['loginPassword'] == '' ) {
      return 'Please, enter member of staff login password';
   }

   if ( validateNameOfPerson( $_POST['firstName'] ) != NULL ) {
      return 'Invalid data entered for member of staff first name';
   }

   if ( validateNameOfPerson( $_POST['lastName'] ) != NULL ) {
      return 'Invalid data entered for member of staff last name';
   }

   return NULL;
}


function validateCustomerRegistrationDetails()
{
   if ( $_POST['email'] == '' ) {
      return 'Please, enter your email';
   }

   if ( $_POST['firstName'] == '' ) {
      return 'Please, enter your first name';
   }

   if ( $_POST['lastName'] == '' ) {
      return 'Please, enter your last name';
   }

   if ( $_POST['loginPassword'] == '' ) {
      return 'Please, enter your login password';
   }

   if ( $_POST['retypedLoginPassword'] == '' ) {
      return 'Please, re-type your login password';
   }

   $errorMessage = validateEmail( $_POST['email'] );

   if ( $errorMessage != NULL ) {
      return $errorMessage;
   }

   $customerWhoAlreadyUsesThisEmail = retrieveNameOfCustomer( $_POST['email'] );

   if ( $customerWhoAlreadyUsesThisEmail != NULL ) {
      return 'The email you entered already belongs to another customer';
   }

   $errorMessage = validateNameOfPerson( $_POST['firstName'] );

   if ( $errorMessage != NULL ) {
      return 'Invalid first name';
   }

   $errorMessage = validateNameOfPerson( $_POST['lastName'] );

   if ( $errorMessage != NULL ) {
      return 'Invalid last name';
   }

   if ( $_POST['loginPassword'] != $_POST['retypedLoginPassword'] ) {
      return 'Passwords do not match';
   }

   return NULL;
}


function validateCustomerLoginDetails()
{
   if ( $_POST['email'] == '' ) {
      return 'Please, enter your email';
   }

   if ( $_POST['password'] == '' ) {
      return 'Please, enter your password';
   }

   $errorInEmailAddress = validateEmail( $_POST['email'] );

   if ( $errorInEmailAddress != NULL ) {
      return $errorInEmailAddress;
   }

   $passwordRetrievedFromDatabase = retrievePasswordOfCustomer( $_POST['email'] );

   if ( $passwordRetrievedFromDatabase == NO_ROW_FOUND ) {
      return 'The email you entered does not belong to any customer';
   }

   if ( password_verify( $_POST['password'], $passwordRetrievedFromDatabase ) == false ) {
      return 'Incorrect password';
   }

   return NULL;
}


function validateMemberOfStaffLoginDetails()
{
   if ( $_POST['prefixedIdOfMemberOfStaff'] == '' ) {
      return 'Please, enter your Member of Staff ID.';
   }

   if ( $_POST['password'] == '' ) {
      return 'Please, enter your password.';
   }

   $errorInMemberOfStaffId = validateIdOfMemberOfStaff( $_POST['prefixedIdOfMemberOfStaff'] );

   if ( $errorInMemberOfStaffId != NULL ) {
      return $errorInMemberOfStaffId;
   }

   $idOfMemberOfStaff = substr( $_POST['prefixedIdOfMemberOfStaff'], 4 );
   $passwordRetrievedFromDatabase = retrievePasswordOfMemberOfStaff( $idOfMemberOfStaff );

   if ( $passwordRetrievedFromDatabase == NO_ROW_FOUND ) {
      return 'The Member of Staff ID you entered does not belong to any member of staff.';
   }

   if ( password_verify( $_POST['password'], $passwordRetrievedFromDatabase ) == false ) {
      return 'Incorrect password.';
   }

   return NULL;
}


function validateIdOfMemberOfStaff( $prefixedIdOfMemberOfStaff )
{
   $memberOfStaffIdIsInvalid = false;

   $prefixWithinIdOfMemberOfStaff = substr( $prefixedIdOfMemberOfStaff, 0, 4 );
   $idOfMemberOfStaff = substr( $prefixedIdOfMemberOfStaff, 4 );

   if ( strtoupper( $prefixWithinIdOfMemberOfStaff ) != 'MOS/' ) {
      return 'Invalid Member of Staff ID';
   }

   if ( $idOfMemberOfStaff == '' || doesNotConsistOfOnlyDigits( $idOfMemberOfStaff ) ) {
      return 'Invalid Member of Staff ID';
   }

   return NULL;
}


function validateEmail( $email )
{
   for ( $i = 0; $i < strlen( $email ) && isEmailCharacter( $email[$i] ); $i++ )
      ;

   if ( $i >= strlen( $email ) || $email[$i] != '@' ) {
      return 'Invalid email';
   }

   for ( $i += 1; $i < strlen( $email ) && isAlphabet( $email[$i] ); $i++ )
      ;

   if ( $i >= strlen( $email ) || $email[$i] != '.' ) {
      return 'Invalid email';
   }

   for ( $i += 1; $i < strlen( $email ) && isAlphabet( $email[$i] ); $i++ )
      ;

   if ( $i < strlen( $email ) ) {
      return 'Invalid email';
   }

   return NULL;
}


function validateTime( $time )
{
   /*
      NB:
      The time format that is accepted as valid by this function is 'yyyy-dd-mmThh:mm:ss or 'yyyy-dd-mmThh:mm'
      For example, '2018-30-05T17:54:23' or '2018-30-05T17:54'
   */

   if ( strlen( $time ) != 16 && strlen( $time ) != 19 ) {
      return 'Invalid time';
   }

   if ( strlen( $time ) ==  16 ) {
      $time .= ':00';
   }

   $year = substr( $time, 0, 4 );
   $delimiterBetweenYearAndMonth = substr( $time, 4, 1 );
   $month = substr( $time, 5, 2 );
   $delimiterBetweenMonthAndDay = substr( $time, 7, 1 );
   $day = substr( $time, 8, 2 );
   $delimiterBetweenDayAndHour = substr( $time, 10, 1 );
   $hour = substr( $time, 11, 2 );
   $delimiterBetweenHourAndMinute = substr( $time, 13, 1 );
   $minute = substr( $time, 14, 2 );
   $delimiterBetweenMinuteAndSecond = substr( $time, 16, 1 );
   $second = substr( $time, 17, 2 );

   if ( doesNotConsistOfOnlyDigits( $year ) || doesNotConsistOfOnlyDigits( $month ) ||
      doesNotConsistOfOnlyDigits( $day ) || doesNotConsistOfOnlyDigits( $hour ) ||
      doesNotConsistOfOnlyDigits( $minute ) || doesNotConsistOfOnlyDigits( $second ) )
   {
      return 'Invalid time';
   }

   if ( $delimiterBetweenYearAndMonth != '-' || $delimiterBetweenMonthAndDay != '-' ) {
      return 'Invalid time';
   }

   if ( $delimiterBetweenDayAndHour != 'T' ) {
      return 'Invalid time';
   }

   if ( $delimiterBetweenHourAndMinute != ':' || $delimiterBetweenMinuteAndSecond != ':' ) {
      return 'Invalid time';
   }

   if ( dayDoesNotBelongToMonth( $day, $month, $year ) ) {
      return 'Invalid time';
   }

   if ( $hour > 24 || $minute > 60 || $second > 60 ) {
      return 'Invalid time';
   }

   return NULL;
}


function validateNameOfPerson( $nameOfPerson )
{
   if ( doesNotConsistOfOnlyAlphabets( $nameOfPerson ) ) {
      return 'Invalid name';
   }

   return NULL;
}


function validateNameOfJob( $nameOfJob )
{
   if ( strpos($nameOfJob, '--' ) ) {
      return 'You entered an invalid name of job. A valid name of job cannot contain two consecutive hyphens';
   }

   return NULL;
}


function redirectToIndexPageIfIdOfRequiredJobIsInvalid()
{
   if ( !isset( $_GET['idOfRequiredJob'] ) ) {
      header( 'Location: index.php' );
   }

   if ( doesNotConsistOfOnlyDigits( $_GET['idOfRequiredJob'] ) ) {
      header( 'Location: index.php' );
   }

   $nameOfRequiredJob = retrieveNameOfJob( $_GET['idOfRequiredJob'] );

   if ( $nameOfRequiredJob == NO_ROW_FOUND ) {
      header( 'Location: index.php' );
   }
}


function redirectToIndexPageIfIdOfRequiredJobTypeIsInvalid()
{
   if ( !isset( $_GET['idOfRequiredJobType'] ) ) {
      header( 'Location: index.php' );
   }

   if ( doesNotConsistOfOnlyDigits( $_GET['idOfRequiredJobType'] ) ) {
      header( 'Location: index.php' );
   }

   $nameOfRequiredJobType = retrieveNameOfJobType( $_GET['idOfRequiredJobType'] );

   if ( $nameOfRequiredJobType == NO_ROW_FOUND ) {
      header( 'Location: index.php' );
   }
}


function redirectToIndexPageIfIdOfRequiredMemberOfStaffIsInvalid()
{
   if ( !isset( $_GET['idOfRequiredMemberOfStaff'] ) ) {
      header( 'Location: index.php' );
   }

   if ( doesNotConsistOfOnlyDigits( $_GET['idOfRequiredMemberOfStaff'] ) ) {
      header( 'Location: index.php' );
   }

   $nameOfRequiredMemberOfStaff = retrieveNameOfMemberOfStaff( $_GET['idOfRequiredMemberOfStaff'] );

   if ( $nameOfRequiredMemberOfStaff == NO_ROW_FOUND ) {
      header( 'Location: index.php' );
   }
}


function redirectToIndexPageIfIdOfRequiredScheduledSubtaskIsInvalid()
{
   if ( !isset( $_GET['idOfRequiredJob'] ) || !isset( $_GET['idOfRequiredSubtask'] ) ) {
      header( 'Location: index.php' );
   }

   if ( doesNotConsistOfOnlyDigits( $_GET['idOfRequiredJob'] ) ||
      doesNotConsistOfOnlyDigits( $_GET['idOfRequiredSubtask'] ) )
   {
      header( 'Location: index.php' );
   }

   $completionStatusOfRequiredJob =
      retrieveCompletionStatusOfSubtaskScheduledForJob( $_GET['idOfRequiredJob'], $_GET['idOfRequiredSubtask'] );

   if ( $completionStatusOfRequiredJob == NO_ROW_FOUND ) {
      header( 'Location: index.php' );
   }
}


function redirectToIndexPageIfRequiredScheduledSubtaskIsNotInProgress()
{
   $completionStatusOfRequiredJob =
      retrieveCompletionStatusOfSubtaskScheduledForJob( $_GET['idOfRequiredJob'], $_GET['idOfRequiredSubtask'] );

   if ( strtolower( $completionStatusOfRequiredJob ) != 'in progress' ) {
      header( 'Location: index.php' );
   }
}


function redirectToIndexPageIfRequiredSubtaskWasNotAssignedToLoggedInMemberOfStaff()
{
   $dataAboutRequiredScheduledSubtask =
      retrieveDataAboutSubtaskAssignedToMemberOfStaff( $_SESSION['idOfLoggedInUser'],
      $_GET['idOfRequiredJob'], $_GET['idOfRequiredSubtask'] );

   if ( $dataAboutRequiredScheduledSubtask == NO_ROW_FOUND ) {
      header( 'Location: index.php' );
   }
}


function redirectToIndexPageIfScheduleHasNotBeenCreatedForRequiredJob()
{
   $percentOfJobDone = retrievePercentOfJobDone( $_GET['idOfRequiredJob'] );

   if ( $percentOfJobDone == NULL ) {
      header( 'Location: index.php' );
   }
}


function redirectToIndexPageIfRequiredJobHasAlreadyBeenCompleted()
{
   $percentOfJobDone = retrievePercentOfJobDone( $_GET['idOfRequiredJob'] );

   if ( $percentOfJobDone == 100 ) {
      header( 'Location: index.php' );
   }
}


function redirectToIndexPageIfLoggedInUserDoNotHaveAccessRightsToCurrentPage()
{
   if ( userIsLoggedInAsCustomer() && customersDoNotHaveAccessRightsToCurrentPage() ) {
      header( 'Location: index.php' );
   }
   else if ( userIsLoggedInAsProductionManager() && productionManagersDoNotHaveAccessRightsToCurrentPage() ) {
      header( 'Location: index.php' );
   }
   else if ( userIsLoggedInAsFrontDeskOfficer() && frontDeskOfficersDoNotHaveAccessRightsToCurrentPage() ) {
      header( 'Location: index.php' );
   }
   else if ( userIsLoggedInAsMemberOfStaff() && membersOfStaffDoNotHaveAccessRightsToCurrentPage() ) {
      header( 'Location: index.php' );
   }
   else if ( userIsNotLoggedIn() && loggedOutUsersDoNotHaveAccessRightsToCurrentPage() ) {
      header( 'Location: index.php' );
   }
}
?>