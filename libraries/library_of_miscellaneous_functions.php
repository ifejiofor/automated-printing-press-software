<?php
function logCustomerIn()
{
   $_SESSION['loginStatus'] = LOGGED_IN_AS_CUSTOMER;
   $_SESSION['idOfLoggedInUser'] = $_POST['email'];
}


function logMemberOfStaffIn()
{
   $idOfMemberOfStaff = substr( $_POST['prefixedIdOfMemberOfStaff'], 4 );
   $_SESSION['idOfLoggedInUser'] = $idOfMemberOfStaff;

   $loggedInUserIsFrontDeskOfficer = retrieveFrontDeskOfficerStatusOfMemberOfStaff( $idOfMemberOfStaff );

   if ( $loggedInUserIsFrontDeskOfficer ) {
      $_SESSION['loginStatus'] = LOGGED_IN_AS_FRONT_DESK_OFFICER;
   }
   else {
      $_SESSION['loginStatus'] = LOGGED_IN_AS_MEMBER_OF_STAFF;
   }
}


function logProductionManagerIn()
{
   $_SESSION['loginStatus'] = LOGGED_IN_AS_PRODUCTION_MANAGER;
}


function registerNewMemberOfStaff()
{
   $rowOfDataAboutNewMemberOfStaff = array();

   $rowOfDataAboutNewMemberOfStaff['member_of_staff_firstname'] =
      ucwords( strtolower( $_POST['firstName'] ) );
   $rowOfDataAboutNewMemberOfStaff['member_of_staff_lastname'] =
      ucwords( strtolower( $_POST['lastName'] ) );
   $rowOfDataAboutNewMemberOfStaff['member_of_staff_loginpassword'] =
      password_hash( $_POST['loginPassword'], PASSWORD_DEFAULT );

   $idOfMemberOfStaffThatWasJustInserted = insertDataAboutNewMemberOfStaff( $rowOfDataAboutNewMemberOfStaff );
   return $idOfMemberOfStaffThatWasJustInserted;
}


function registerNewCustomer()
{
   $rowOfDataAboutNewCustomer = array();

   $rowOfDataAboutNewCustomer['customer_emailaddress'] = $_POST['email'];
   $rowOfDataAboutNewCustomer['customer_firstname'] = $_POST['firstName'];
   $rowOfDataAboutNewCustomer['customer_lastname'] = $_POST['lastName'];
   $rowOfDataAboutNewCustomer['customer_loginpassword'] = password_hash( $_POST['loginPassword'], PASSWORD_DEFAULT );
   insertDataAboutNewCustomer( $rowOfDataAboutNewCustomer );
}


function registerNewJob()
{
   $rowOfDataAboutNewJob = array();

   $rowOfDataAboutNewJob['print_job_name'] = $_POST['nameOfJob'];
   $rowOfDataAboutNewJob['print_job_type_id'] = $_POST['idOfJobType'];
   $rowOfDataAboutNewJob['customer_emailaddress'] = strtolower( $_POST['customerEmail'] );
   insertDataAboutNewJob( $rowOfDataAboutNewJob );
}


function markRequiredScheduledSubtaskAsCompleted()
{
   updateCompletionStatusOfSubtaskScheduledForJob( $_GET['idOfRequiredJob'],
      $_GET['idOfRequiredSubtask'], 'Completed' );

   $serialNumberOfScheduledSubtask =
      retrieveSerialNumberOfSubtaskScheduledForJob( $_GET['idOfRequiredJob'], $_GET['idOfRequiredSubtask'] );
   $serialNumberOfNextScheduledSubtask =
      $serialNumberOfScheduledSubtask + 1;
   $idOfNextScheduledSubtask =
      retrieveIdOfSubtaskScheduledForJob( $_GET['idOfRequiredJob'], $serialNumberOfNextScheduledSubtask );
   updateCompletionStatusOfSubtaskScheduledForJob( $_GET['idOfRequiredJob'],
      $idOfNextScheduledSubtask, 'In progress' );

   $percentOfRequiredJobDone = calculatePercentOfJobDone( $_GET['idOfRequiredJob'] );
   updatePercentOfJobDone( $_GET['idOfRequiredJob'], $percentOfRequiredJobDone );
}


function storeDataAboutAllSubtasksIntoSession()
{
   $rowsOfDataAboutAllSubtasks = retrieveRowsOfDataAboutAllSubtasks();

   for ( $i = 0; $i < sizeof( $rowsOfDataAboutAllSubtasks ); $i++ ) {
      $row = $rowsOfDataAboutAllSubtasks[$i];
      $_SESSION['idOfSubtask' . $i] = $row['subtask_id'];
      $_SESSION['nameOfSubtask' . $i] = $row['subtask_name'];
   }

   $_SESSION['totalNumberOfSubtasksStoredInSession'] = sizeof( $rowsOfDataAboutAllSubtasks );
}


function storeDataAboutAllMembersOfStaffIntoSession()
{
   $rowsOfDataAboutAllMembersOfStaff = retrieveRowsOfDataAboutAllMembersOfStaff();

   for ( $i = 0; $i < sizeof( $rowsOfDataAboutAllMembersOfStaff ); $i++ ) {
      $row = $rowsOfDataAboutAllMembersOfStaff[$i];
      $_SESSION['idOfMemberOfStaff'.$i] = $row['member_of_staff_id'];
      $_SESSION['nameOfMemberOfStaff'.$i] = $row['member_of_staff_firstname'] . ' ' . $row['member_of_staff_lastname'];
   }

   $_SESSION['totalNumberOfMembersOfStaffStoredInSession'] = sizeof( $rowsOfDataAboutAllMembersOfStaff );
}


function initializeWorkingScheduleWithDataFromDatabase()
{
   $rowsOfDataAboutScheduledSubtasksThatAreNotCompleted =
      retrieveRowsOfDataAboutSubtasksScheduledForJobAndAreNotCompleted( $_GET['idOfRequiredJob'] );

   if ( sizeof( $rowsOfDataAboutScheduledSubtasksThatAreNotCompleted ) == 0 ) {
      $_SESSION['idOfSubtaskInWorkingSchedule1'] = '';
      $_SESSION['idOfMemberOfStaffInWorkingSchedule1'] = '';
      $_SESSION['proposedStartTimeInWorkingSchedule1'] = '';
      $_SESSION['proposedCompletionTimeInWorkingSchedule1'] = '';

      $_SESSION['serialNumberOfFirstSubtaskInWorkingSchedule'] = 1;
      $_SESSION['serialNumberOfLastSubtaskInWorkingSchedule'] = 1;
   }
   else {
      for ( $i = 0; $i < sizeof( $rowsOfDataAboutScheduledSubtasksThatAreNotCompleted ); $i++ ) {
         $row = $rowsOfDataAboutScheduledSubtasksThatAreNotCompleted[$i];
         $serialNumber = $row['scheduled_subtask_serialnumber'];

         $_SESSION['idOfSubtaskInWorkingSchedule'.$serialNumber] =
            $row['subtask_id'];
         $_SESSION['idOfMemberOfStaffInWorkingSchedule'.$serialNumber] =
            $row['member_of_staff_id'];
         $_SESSION['proposedStartTimeInWorkingSchedule'.$serialNumber] = 
            str_replace( ' ', 'T', $row['scheduled_subtask_proposedstarttime'] );
         $_SESSION['proposedCompletionTimeInWorkingSchedule'.$serialNumber] =
            str_replace( ' ', 'T', $row['scheduled_subtask_proposedcompletiontime'] );
      }

      $firstRow = $rowsOfDataAboutScheduledSubtasksThatAreNotCompleted[INDEX_OF_FIRST_ROW];
      $indexOfLastRow = sizeof( $rowsOfDataAboutScheduledSubtasksThatAreNotCompleted ) - 1;
      $lastRow = $rowsOfDataAboutScheduledSubtasksThatAreNotCompleted[$indexOfLastRow];

      $_SESSION['serialNumberOfFirstSubtaskInWorkingSchedule'] = $firstRow['scheduled_subtask_serialnumber'];
      $_SESSION['serialNumberOfLastSubtaskInWorkingSchedule'] = $lastRow['scheduled_subtask_serialnumber'];
   }
}


function updateWorkingScheduleWithDataInputtedByUser()
{
   for ( $serialNumber = $_SESSION['serialNumberOfFirstSubtaskInWorkingSchedule'];
      $serialNumber <= $_SESSION['serialNumberOfLastSubtaskInWorkingSchedule']; $serialNumber++ )
   {
      $_SESSION['idOfSubtaskInWorkingSchedule'.$serialNumber] =
         $_POST['idOfSubtask'.$serialNumber];
      $_SESSION['idOfMemberOfStaffInWorkingSchedule'.$serialNumber] =
         $_POST['idOfMemberOfStaff'.$serialNumber];
      $_SESSION['proposedStartTimeInWorkingSchedule'.$serialNumber] =
         $_POST['proposedStartTime'.$serialNumber];
      $_SESSION['proposedCompletionTimeInWorkingSchedule'.$serialNumber] =
         $_POST['proposedCompletionTime'.$serialNumber];      
   }
}


function unsetDataAboutAllSubtasksFromSession()
{
   for ( $i = 0; $i < $_SESSION['totalNumberOfSubtasksStoredInSession']; $i++ ) {
      unset( $_SESSION['idOfSubtask' . $i] );
      unset( $_SESSION['nameOfSubtask' . $i] );
   }

   unset( $_SESSION['totalNumberOfSubtasksStoredInSession'] ); 
}


function unsetDataAboutAllMembersOfStaffFromSession()
{
   for ( $i = 0; $i < $_SESSION['totalNumberOfMembersOfStaffStoredInSession']; $i++ ) {
      unset( $_SESSION['idOfMemberOfStaff'.$i] );
      unset( $_SESSION['nameOfMemberOfStaff'.$i] );
   }

   unset( $_SESSION['totalNumberOfMembersOfStaffStoredInSession'] );
}


function destroyWorkingSchedule()
{
   for ( $serialNumber = $_SESSION['serialNumberOfFirstSubtaskInWorkingSchedule'];
      $serialNumber <= $_SESSION['serialNumberOfLastSubtaskInWorkingSchedule']; $serialNumber++ )
   {
      unset( $_SESSION['idOfSubtaskInWorkingSchedule'.$serialNumber] );
      unset( $_SESSION['idOfMemberOfStaffInWorkingSchedule'.$serialNumber] );
      unset( $_SESSION['proposedStartTimeInWorkingSchedule'.$serialNumber] );
      unset( $_SESSION['proposedCompletionTimeInWorkingSchedule'.$serialNumber] );
   }

   unset( $_SESSION['serialNumberOfFirstSubtaskInWorkingSchedule'] );
   unset( $_SESSION['serialNumberOfLastSubtaskInWorkingSchedule'] );
}


function performScheduleCreationOrModificationAction()
{
   if ( isset( $_POST['addAnotherSubtaskButton'] ) ) {
      addAnotherSubtaskIntoWorkingSchedule();
      header( 'Location: job_schedule_creation_or_modification.php?idOfRequiredJob=' . $_GET['idOfRequiredJob'] );
   }
   else if ( isset( $_POST['serialNumberOfSubtaskToBeDeleted'] ) ) {
      deleteSubtaskFromWorkingSchedule( $_POST['serialNumberOfSubtaskToBeDeleted'] );
      header( 'Location: job_schedule_creation_or_modification.php?idOfRequiredJob=' . $_GET['idOfRequiredJob'] );
   }
   else if ( isset( $_POST['authenticateScheduleButton'] ) ) {
      authenticateSchedule();
      header( 'Location: progress_of_job.php?idOfRequiredJob=' . $_GET['idOfRequiredJob'] );
   }
}


function addAnotherSubtaskIntoWorkingSchedule()
{
   $serialNumberOfSubtaskToBeAdded = $_SESSION['serialNumberOfLastSubtaskInWorkingSchedule'] + 1;

   $_SESSION['idOfSubtaskInWorkingSchedule'.$serialNumberOfSubtaskToBeAdded] = '';
   $_SESSION['idOfMemberOfStaffInWorkingSchedule'.$serialNumberOfSubtaskToBeAdded] = '';
   $_SESSION['proposedStartTimeInWorkingSchedule'.$serialNumberOfSubtaskToBeAdded] = '';
   $_SESSION['proposedCompletionTimeInWorkingSchedule'.$serialNumberOfSubtaskToBeAdded] = '';

   $_SESSION['serialNumberOfLastSubtaskInWorkingSchedule'] = $serialNumberOfSubtaskToBeAdded;
}


function deleteSubtaskFromWorkingSchedule( $serialNumberOfSubtaskToBeDeleted )
{
   for ( $serialNumber = $serialNumberOfSubtaskToBeDeleted; 
      $serialNumber < $_SESSION['serialNumberOfLastSubtaskInWorkingSchedule']; $serialNumber++ )
   {
      $_SESSION['idOfSubtaskInWorkingSchedule'.$serialNumber] =
         $_SESSION['idOfSubtaskInWorkingSchedule'.( $serialNumber + 1 )];
      $_SESSION['idOfMemberOfStaffInWorkingSchedule'.$serialNumber] =
         $_SESSION['idOfMemberOfStaffInWorkingSchedule'.( $serialNumber + 1 )];
      $_SESSION['proposedStartTimeInWorkingSchedule'.$serialNumber] =
         $_SESSION['proposedStartTimeInWorkingSchedule'.( $serialNumber + 1 )];
      $_SESSION['proposedCompletionTimeInWorkingSchedule'.$serialNumber] =
         $_SESSION['proposedCompletionTimeInWorkingSchedule'.( $serialNumber + 1 )];      
   }

   unset( $_SESSION['idOfSubtaskInWorkingSchedule'.$serialNumber] );
   unset( $_SESSION['idOfMemberOfStaffInWorkingSchedule'.$serialNumber] );
   unset( $_SESSION['proposedStartTimeInWorkingSchedule'.$serialNumber] );
   unset( $_SESSION['proposedCompletionTimeInWorkingSchedule'.$serialNumber] );
   $_SESSION['serialNumberOfLastSubtaskInWorkingSchedule']--;
}


function authenticateSchedule()
{
   deleteDataAboutSubtasksScheduledForJobAndAreNotCompleted( $_GET['idOfRequiredJob'] );

   $rowsOfDataAboutSubtasksFromWorkingSchedule = populateArrayWithRowsOfDataAboutSubtasksFromWorkingSchedule();
   insertDataAboutSubtasksToBeScheduledForJob( $_GET['idOfRequiredJob'], $rowsOfDataAboutSubtasksFromWorkingSchedule );

   $percentOfJobDone = calculatePercentOfJobDone( $_GET['idOfRequiredJob'] );
   updatePercentOfJobDone( $_GET['idOfRequiredJob'], $percentOfJobDone );
}


function populateArrayWithRowsOfDataAboutSubtasksFromWorkingSchedule()
{
   $rowsOfData = array();

   for ( $serialNumber = $_SESSION['serialNumberOfFirstSubtaskInWorkingSchedule'];
      $serialNumber <= $_SESSION['serialNumberOfLastSubtaskInWorkingSchedule']; $serialNumber++ )
   {
      $row = array();
      $row['subtask_id'] =
         $_SESSION['idOfSubtaskInWorkingSchedule'.$serialNumber];
      $row['member_of_staff_id'] =
         $_SESSION['idOfMemberOfStaffInWorkingSchedule'.$serialNumber];
      $row['scheduled_subtask_serialnumber'] =
         $serialNumber;
      $row['scheduled_subtask_proposedstarttime'] =
         $_SESSION['proposedStartTimeInWorkingSchedule'.$serialNumber];
      $row['scheduled_subtask_proposedcompletiontime'] =
         $_SESSION['proposedCompletionTimeInWorkingSchedule'.$serialNumber];
      $row['scheduled_subtask_completionstatus'] = 'Pending';

      $rowsOfData[] = $row;
   }

   if ( sizeof( $rowsOfData ) > 0 ) {
      $rowsOfData[INDEX_OF_FIRST_ROW]['scheduled_subtask_completionstatus'] = 'In progress';
   }

   return $rowsOfData;
}


function calculatePercentOfJobDone( $idOfJob )
{
   $rowsOfDataAboutScheduledSubtasksThatAreCompleted =
      retrieveRowsOfDataAboutSubtasksScheduledForJobAndAreCompleted( $idOfJob );
   $rowsOfDataAboutAllScheduledSubtasks =
      retrieveRowsOfDataAboutSubtasksScheduledForJob( $idOfJob );

   $totalNumberOfScheduledSubtasksThatAreCompleted = sizeof( $rowsOfDataAboutScheduledSubtasksThatAreCompleted );
   $totalNumberOfAllScheduledSubtasks = sizeof( $rowsOfDataAboutAllScheduledSubtasks );

   $fractionOfJobDone = (double) $totalNumberOfScheduledSubtasksThatAreCompleted / $totalNumberOfAllScheduledSubtasks;
   $percentOfJobDone = (integer) ( $fractionOfJobDone * 100 );

   return $percentOfJobDone;
}


function calculateJobCost()
{
   if ( isCodexJob( $_GET['idOfRequiredJobType'] ) ) {
      return calculateJobCostForCodexJob();
   }
   else {
      return calculateJobCostForSheetJob();
   }
}


function calculateJobCostForCodexJob()
{
   $lineNumber = getLineNumberOfLineWhereUnitCostsAreStored( $_POST['pageSize'] );
   $columnNumber = getColumnNumberOfColumnWhereUnitCostsAreStored( $_POST['colourOfInsidePages'] );
   $lineOfTextContainingUnitCosts = readLineOfTextFromDataFile( 'unit_costs_for_thin_paper_jobs.dat', $lineNumber );
   $unitCostOfInsidePages = getColumnValueFromLineOfText( $lineOfTextContainingUnitCosts, $columnNumber );

   $lineNumber = getLineNumberOfLineWhereUnitCostsAreStored( $_POST['pageSize'] );
   $columnNumber = getColumnNumberOfColumnWhereUnitCostsAreStored( $_POST['colourOfCover'] );
   $lineOfTextContainingUnitCosts = readLineOfTextFromDataFile( 'unit_costs_for_thick_paper_jobs.dat', $lineNumber );
   $unitCostOFCover = getColumnValueFromLineOfText( $lineOfTextContainingUnitCosts, $columnNumber );

   $totalCostOfInsidePages = $unitCostOfInsidePages * $_POST['numberOfInsidePages'] * $_POST['numberOfCopies'];
   $totalCostOfCover = $unitCostOFCover * 2 * $_POST['numberOfCopies'];
   $jobCost = $totalCostOfInsidePages + $totalCostOfCover;

   return $jobCost;
}


function calculateJobCostForSheetJob()
{
   $lineNumber = getLineNumberOfLineWhereUnitCostsAreStored( $_POST['paperSize'] );
   $columnNumber = getColumnNumberOfColumnWhereUnitCostsAreStored( $_POST['colour'] );
   $lineOfTextContainingUnitCosts = readLineOfTextFromDataFile( 'unit_costs_for_thick_paper_jobs.dat', $lineNumber );
   $unitCost = getColumnValueFromLineOfText( $lineOfTextContainingUnitCosts, $columnNumber );

   $jobCost = $unitCost * $_POST['numberOfPrintedSides'] * $_POST['numberOfCopies'];

   return $jobCost;
}


function getLineNumberOfLineWhereUnitCostsAreStored( $paperSize )
{
   switch ( $paperSize ) {
      case 'A0':
         $lineNumber = 2;
         break;
      case 'A1':
         $lineNumber = 3;
         break;
      case 'A2':
         $lineNumber = 4;
         break;
      case 'A3':
         $lineNumber = 5;
         break;
      case 'A4':
         $lineNumber = 6;
         break;
      case 'A5':
         $lineNumber = 7;
         break;
      case 'A6':
         $lineNumber = 8;
         break;
      default:
         $lineNumber = NULL;
         break;
   }

   return $lineNumber;
}


function getColumnNumberOfColumnWhereUnitCostsAreStored( $inkColour )
{
   switch ( $inkColour ) {
      case 'Black':
         $columnNumber = 2;
         break;
      case 'Black + One Colour':
         $columnNumber = 3;
         break;
      case 'Black + Two Colours':
         $columnNumber = 4;
         break;
      case 'Full Colour':
         $columnNumber = 5;
         break;
      default:
         $columnNumber = NULL;
         break;
   }

   return $columnNumber;
}


function determineWhichTimeIsEarlier( $time1, $time2 )
{
   if ( strlen( $time1 ) == 16 ) {
      $time1 .= ':00';
   }

   if ( strlen( $time2 ) == 16 ) {
      $time2 .= ':00';
   }

   $yearOfTime1 = substr( $time1, 0, 4 );
   $yearOfTime2 = substr( $time2, 0, 4 );

   if ( $yearOfTime1 < $yearOfTime2 ) {
      return TIME_1_IS_EARLIER;
   }
   else if ( $yearOfTime2 < $yearOfTime1 ) {
      return TIME_2_IS_EARLIER;
   }
   
   $monthOfTime1 = substr( $time1, 5, 2 );
   $monthOfTime2 = substr( $time2, 5, 2 );

   if ( $monthOfTime1 < $monthOfTime2 ) {
      return TIME_1_IS_EARLIER;
   }
   else if ( $monthOfTime2 < $monthOfTime1 ) {
      return TIME_2_IS_EARLIER;
   }

   $dayOfTime1 = substr( $time1, 8, 2 );
   $dayOfTime2 = substr( $time2, 8, 2 );

   if ( $dayOfTime1 < $dayOfTime2 ) {
      return TIME_1_IS_EARLIER;
   }
   else if ( $dayOfTime2 < $dayOfTime1 ) {
      return TIME_2_IS_EARLIER;
   }

   $hourOfTime1 = substr( $time1, 11, 2 );
   $hourOfTime2 = substr( $time2, 11, 2 );

   if ( $hourOfTime1 < $hourOfTime2 ) {
      return TIME_1_IS_EARLIER;
   }
   else if ( $hourOfTime2 < $hourOfTime1 ) {
      return TIME_2_IS_EARLIER;
   }

   $minuteOfTime1 = substr( $time1, 14, 2 );
   $minuteOfTime2 = substr( $time2, 14, 2 );

   if ( $minuteOfTime1 < $minuteOfTime2 ) {
      return TIME_1_IS_EARLIER;
   }
   else if ( $minuteOfTime2 < $minuteOfTime1 ) {
      return TIME_2_IS_EARLIER;
   }

   $secondOfTime1 = substr( $time1, 17, 2 );
   $secondOfTime2 = substr( $time2, 17, 2 );

   if ( $secondOfTime1 < $secondOfTime2 ) {
      return TIME_1_IS_EARLIER;
   }
   else if ( $secondOfTime2 < $secondOfTime1 ) {
      return TIME_2_IS_EARLIER;
   }

   return TIME_1_AND_TIME_2_ARE_EQUAL;
}
?>