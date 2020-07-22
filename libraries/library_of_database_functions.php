<?php
function retrievePasswordOfCustomer( $emailOfCustomer )
{
   $query = 'SELECT customer_loginpassword FROM customers WHERE customer_emailaddress = "' . $emailOfCustomer . '"';
   $rowsFromResultOfQuery = queryDatabase( $query );
   return getColumnValueFromFirstRow( $rowsFromResultOfQuery, 'customer_loginpassword' );
}


function retrievePasswordOfMemberOfStaff( $idOfMemberOfStaff )
{
   $query =
      'SELECT member_of_staff_loginpassword FROM members_of_staff
      WHERE member_of_staff_id = "' . $idOfMemberOfStaff . '"';
   $rowsFromResultOfQuery = queryDatabase( $query );
   return getColumnValueFromFirstRow( $rowsFromResultOfQuery, 'member_of_staff_loginpassword' );
}


function retrieveNameOfCustomer( $emailOfCustomer )
{
   $query =
      'SELECT customer_firstname, customer_lastname FROM customers
      WHERE customer_emailaddress = "' . $emailOfCustomer . '"';
   $rowsFromResultOfQuery = queryDatabase( $query );

   $firstNameOfCustomer = getColumnValueFromFirstRow( $rowsFromResultOfQuery, 'customer_firstname' );
   $lastNameOfCustomer = getColumnValueFromFirstRow( $rowsFromResultOfQuery, 'customer_lastname' );

   if ( $firstNameOfCustomer == NO_ROW_FOUND ) {
      return NO_ROW_FOUND;
   }
   else {
      return $firstNameOfCustomer . ' ' . $lastNameOfCustomer;
   }
}


function retrieveNameOfMemberOfStaff( $idOfMemberOfStaff )
{
   $query =
      'SELECT member_of_staff_firstname, member_of_staff_lastname FROM members_of_staff
      WHERE member_of_staff_id = "' . $idOfMemberOfStaff . '"';
   $rowsFromResultOfQuery = queryDatabase( $query );

   $firstNameOfMemberOfStaff = getColumnValueFromFirstRow( $rowsFromResultOfQuery, 'member_of_staff_firstname' );
   $lastNameOfMemberOfStaff = getColumnValueFromFirstRow( $rowsFromResultOfQuery, 'member_of_staff_lastname' );

   if ( $firstNameOfMemberOfStaff == NO_ROW_FOUND ) {
      return NO_ROW_FOUND;
   }
   else {
      return $firstNameOfMemberOfStaff . ' ' . $lastNameOfMemberOfStaff;
   }
}


function retrieveNameOfSubtask( $idOfSubtask )
{
   $query = 'SELECT subtask_name FROM subtasks WHERE subtask_id = "' . $idOfSubtask . '"';
   $rowsFromResultOfQuery = queryDatabase( $query );
   return getColumnValueFromFirstRow( $rowsFromResultOfQuery, 'subtask_name' );
}


function retrieveNameOfJob( $idOfJob )
{
   $query = 'SELECT print_job_name FROM print_jobs WHERE print_job_id = "' . $idOfJob . '"';
   $rowsFromResultOfQuery = queryDatabase( $query );
   return getColumnValueFromFirstRow( $rowsFromResultOfQuery, 'print_job_name' );
}


function retrieveNameOfJobType( $idOfJobType )
{
   $query = 'SELECT print_job_type_name FROM print_job_types WHERE print_job_type_id = "' . $idOfJobType . '"';
   $rowsFromResultOfQuery = queryDatabase( $query );
   return getColumnValueFromFirstRow( $rowsFromResultOfQuery, 'print_job_type_name' );
}


function retrieveClassOfJobType( $idOfJobType )
{
   $query = 'SELECT print_job_type_class FROM print_job_types WHERE print_job_type_id = "' . $idOfJobType . '"';
   $rowsFromResultOfQuery = queryDatabase( $query );
   return getColumnValueFromFirstRow( $rowsFromResultOfQuery, 'print_job_type_class' );
}


function retrievePercentOfJobDone( $idOfJob )
{
   $query = 'SELECT print_job_percentdone FROM print_jobs WHERE print_job_id = "' . $idOfJob . '"';
   $rowsFromResultOfQuery = queryDatabase( $query );
   return getColumnValueFromFirstRow( $rowsFromResultOfQuery, 'print_job_percentdone' );
}


function retrieveIdOfSubtaskScheduledForJob( $idOfJob, $serialNumberOfSubtask )
{
   $query =
      'SELECT subtask_id FROM scheduled_subtasks
         WHERE print_job_id = "' . $idOfJob . '"AND scheduled_subtask_serialnumber = "' . $serialNumberOfSubtask . '"';
   $rowsFromResultOfQuery = queryDatabase( $query );
   return getColumnValueFromFirstRow( $rowsFromResultOfQuery, 'subtask_id' );
}


function retrieveProposedCompletionTimeOfSubtaskScheduledForJob( $idOfJob, $idOfSubtask )
{
   $query =
      'SELECT scheduled_subtask_proposedcompletiontime FROM scheduled_subtasks
      WHERE print_job_id = "' . $idOfJob . '" AND subtask_id = "' . $idOfSubtask . '"';
   $rowsFromResultOfQuery = queryDatabase( $query );
   return getColumnValueFromFirstRow( $rowsFromResultOfQuery, 'scheduled_subtask_proposedcompletiontime' );
}


function retrieveProposedStartTimeOfSubtaskScheduledForJob( $idOfJob, $idOfSubtask )
{
   $query =
      'SELECT scheduled_subtask_proposedstarttime FROM scheduled_subtasks
      WHERE print_job_id = "' . $idOfJob . '" AND subtask_id = "' . $idOfSubtask . '"';
   $rowsFromResultOfQuery = queryDatabase( $query );
   return getColumnValueFromFirstRow( $rowsFromResultOfQuery, 'scheduled_subtask_proposedstarttime' );
}


function retrieveCompletionStatusOfSubtaskScheduledForJob( $idOfJob, $idOfSubtask )
{
   $query =
      'SELECT scheduled_subtask_completionstatus FROM scheduled_subtasks
      WHERE print_job_id = "' . $idOfJob . '" AND subtask_id = "' . $idOfSubtask . '"';
   $rowsFromResultOfQuery = queryDatabase( $query );

   return getColumnValueFromFirstRow( $rowsFromResultOfQuery, 'scheduled_subtask_completionstatus' );
}


function retrieveSerialNumberOfSubtaskScheduledForJob( $idOfJob, $idOfSubtask )
{
   $query =
      'SELECT scheduled_subtask_serialnumber FROM scheduled_subtasks
      WHERE print_job_id = "' . $idOfJob . '" AND subtask_id = "' . $idOfSubtask . '"';
   $rowsFromResultOfQuery = queryDatabase( $query );

   return getColumnValueFromFirstRow( $rowsFromResultOfQuery, 'scheduled_subtask_serialnumber' );
}


function retrieveFrontDeskOfficerStatusOfMemberOfStaff( $idOfMemberOfStaff )
{
   $query =
      'SELECT member_of_staff_isfrontdeskofficer FROM members_of_staff
      WHERE member_of_staff_id = "' . $idOfMemberOfStaff . '"';
   $rowsFromResultOfQuery = queryDatabase( $query );

   return getColumnValueFromFirstRow( $rowsFromResultOfQuery, 'member_of_staff_isfrontdeskofficer' );
}


function retrieveDataAboutSubtaskScheduledForJobAndIsInProgress( $idOfJob )
{
   $query =
      'SELECT * FROM scheduled_subtasks
      WHERE scheduled_subtask_completionstatus = "In progress" AND print_job_id = "' . $idOfJob . '"';
   $rowsFromResultOfQuery = queryDatabase( $query );

   return sizeof( $rowsFromResultOfQuery ) == 0 ? NO_ROW_FOUND : $rowsFromResultOfQuery[INDEX_OF_FIRST_ROW];
}


function retrieveDataAboutSubtaskScheduledForJob( $idOfJob, $idOfSubtask )
{
   $query =
      'SELECT * FROM scheduled_subtasks
      WHERE print_job_id = "' . $idOfJob . '" AND subtask_id = "' . $idOfSubtask . '"';
   $rowsFromResultOfQuery = queryDatabase( $query );

   return sizeof( $rowsFromResultOfQuery ) == 0 ? NO_ROW_FOUND : $rowsFromResultOfQuery[INDEX_OF_FIRST_ROW];
}


function retrieveDataAboutSubtaskAssignedToMemberOfStaff( $idOfMemberOfStaff, $idOfJob, $idOfSubtask )
{
   $query =
      'SELECT * FROM scheduled_subtasks
      WHERE member_of_staff_id = "' . $idOfMemberOfStaff . '"
      AND print_job_id = "' . $idOfJob . '"
      AND subtask_id = "' . $idOfSubtask . '"';
   $rowsFromResultOfQuery = queryDatabase( $query );

   return sizeof( $rowsFromResultOfQuery ) == 0 ? NO_ROW_FOUND : $rowsFromResultOfQuery[INDEX_OF_FIRST_ROW];
}


function retrieveRowsOfDataAboutSubtasksScheduledForJobAndAreCompleted( $idOfJob )
{
   $query =
      'SELECT * FROM scheduled_subtasks
      WHERE scheduled_subtask_completionstatus = "Completed" AND print_job_id = "' . $idOfJob . '"
      ORDER BY scheduled_subtask_serialnumber';
   return queryDatabase( $query );
}


function retrieveRowsOfDataAboutSubtasksScheduledForJobAndAreNotCompleted( $idOfJob )
{
   $query =
      'SELECT * FROM scheduled_subtasks
      WHERE scheduled_subtask_completionstatus != "Completed" AND print_job_id = "' . $idOfJob . '"
      ORDER BY scheduled_subtask_serialnumber';
   return queryDatabase( $query );
}


function retrieveRowsOfDataAboutAllMembersOfStaff()
{
   $query = 'SELECT * FROM members_of_staff ORDER BY member_of_staff_firstname';
   return queryDatabase( $query );
}


function retrieveRowsOfDataAboutAllSubtasks()
{
   $query = 'SELECT * FROM subtasks ORDER BY subtask_name';
   return queryDatabase( $query );
}


function retrieveRowsOfDataAboutAllJobTypes()
{
   $query = 'SELECT * FROM print_job_types ORDER BY print_job_type_name';
   return queryDatabase( $query );
}


function retrieveRowsOfDataAboutSubtasksScheduledForJob( $idOfJob )
{
   $query =
      'SELECT * FROM scheduled_subtasks
      WHERE print_job_id = "' . $idOfJob . '" ORDER BY scheduled_subtask_serialnumber';
   return queryDatabase( $query );
}


function retrieveRowsOfDataAboutAllJobs()
{
   $offset = ( isset( $_GET['offset'] ) && consistsOfOnlyDigits( $_GET['offset'] ) ) ? $_GET['offset'] : 0;

   $query = 'SELECT * FROM print_jobs ORDER BY print_job_registrationtime DESC
      LIMIT ' . $offset . ', ' . ( MAXIMUM_NUMBER_OF_PRINT_JOBS_THAT_SHOULD_BE_DISPLAYED + 1 );
   return queryDatabase( $query );
}


function retrieveRowsOfDataAboutJobsSentByCustomer( $emailOfCustomer )
{
   $offset = ( isset( $_GET['offset'] ) && consistsOfOnlyDigits( $_GET['offset'] ) ) ? $_GET['offset'] : 0;

   $query =
      'SELECT * FROM print_jobs
      WHERE customer_emailaddress = "' . $emailOfCustomer . '"
      ORDER BY print_job_registrationtime DESC
      LIMIT ' . $offset . ', ' . ( MAXIMUM_NUMBER_OF_PRINT_JOBS_THAT_SHOULD_BE_DISPLAYED + 1 );
   return queryDatabase( $query );
}


function retrieveRowsOfDataAboutSubtasksAssignedToMemberOfStaff( $idOfMemberOfStaff )
{
   $offset = ( isset( $_GET['offset'] ) && consistsOfOnlyDigits( $_GET['offset'] ) ) ? $_GET['offset'] : 0;

   $query =
      'SELECT * FROM scheduled_subtasks
      WHERE member_of_staff_id = "' . $idOfMemberOfStaff . '"
      ORDER BY scheduled_subtask_proposedstarttime DESC, scheduled_subtask_proposedcompletiontime DESC
      LIMIT ' . $offset . ', ' . ( MAXIMUM_NUMBER_OF_SUBTASKS_THAT_SHOULD_BE_DISPLAYED + 1 );
   return queryDatabase( $query );
}


function retrieveRowsOfDataAboutSubtasksAssignedToMemberOfStaffAndAreCompleted( $idOfMemberOfStaff )
{
   $offset = ( isset( $_GET['offset'] ) && consistsOfOnlyDigits( $_GET['offset'] ) ) ? $_GET['offset'] : 0;

   $query =
      'SELECT * FROM scheduled_subtasks
      WHERE member_of_staff_id = "' . $idOfMemberOfStaff . '"
      AND scheduled_subtask_completionstatus = "Completed"
      ORDER BY scheduled_subtask_proposedstarttime DESC, scheduled_subtask_proposedcompletiontime DESC
      LIMIT ' . $offset . ', ' . ( MAXIMUM_NUMBER_OF_SUBTASKS_THAT_SHOULD_BE_DISPLAYED + 1 );
   return queryDatabase( $query );
}


function retrieveRowsOfDataAboutSubtasksAssignedToMemberOfStaffAndAreInProgress( $idOfMemberOfStaff )
{
   $offset = ( isset( $_GET['offset'] ) && consistsOfOnlyDigits( $_GET['offset'] ) ) ? $_GET['offset'] : 0;

   $query =
      'SELECT * FROM scheduled_subtasks
      WHERE member_of_staff_id = "' . $idOfMemberOfStaff . '"
      AND scheduled_subtask_completionstatus = "In progress"
      ORDER BY scheduled_subtask_proposedstarttime DESC, scheduled_subtask_proposedcompletiontime DESC
      LIMIT ' . $offset . ', ' . ( MAXIMUM_NUMBER_OF_SUBTASKS_THAT_SHOULD_BE_DISPLAYED + 1 );
   return queryDatabase( $query );
}


function retrieveRowsOfDataAboutSubtasksAssignedToMemberOfStaffAndArePending( $idOfMemberOfStaff )
{
   $offset = ( isset( $_GET['offset'] ) && consistsOfOnlyDigits( $_GET['offset'] ) ) ? $_GET['offset'] : 0;

   $query =
      'SELECT * FROM scheduled_subtasks
      WHERE member_of_staff_id = "' . $idOfMemberOfStaff . '"
      AND scheduled_subtask_completionstatus = "Pending"
      ORDER BY scheduled_subtask_proposedstarttime DESC, scheduled_subtask_proposedcompletiontime DESC
      LIMIT ' . $offset . ', ' . ( MAXIMUM_NUMBER_OF_SUBTASKS_THAT_SHOULD_BE_DISPLAYED + 1 );
   return queryDatabase( $query );
}


function deleteDataAboutSubtasksScheduledForJobAndAreNotCompleted( $idOfJob )
{
   $query =
      'DELETE FROM scheduled_subtasks
      WHERE scheduled_subtask_completionstatus != "completed" AND print_job_id = "' . $idOfJob . '"';
   queryDatabase( $query );
}


function deleteDataAboutMemberOfStaff( $idOfMemberOfStaff )
{
   $query = 'DELETE FROM members_of_staff WHERE member_of_staff_id = "' . $idOfMemberOfStaff . '"';
   queryDatabase( $query );
}


function insertDataAboutSubtasksToBeScheduledForJob( $idOfJob, $rowsOfDataAboutSubtasksToBeScheduled )
{
   for ( $i = 0; $i < sizeof( $rowsOfDataAboutSubtasksToBeScheduled ); $i++ ) {
      $row = $rowsOfDataAboutSubtasksToBeScheduled[$i];

      $query =
         'INSERT INTO scheduled_subtasks ( 
            print_job_id,
            subtask_id,
            member_of_staff_id,
            scheduled_subtask_serialnumber, 
            scheduled_subtask_proposedstarttime,
            scheduled_subtask_proposedcompletiontime,
            scheduled_subtask_completionstatus )
         VALUES ( 
            "' . $idOfJob . '",
            "' . $row['subtask_id'] . '",
            "' . $row['member_of_staff_id'] . '",
            "' . $row['scheduled_subtask_serialnumber'] . '",
            "' . $row['scheduled_subtask_proposedstarttime'] . '",
            "' . $row['scheduled_subtask_proposedcompletiontime'] . '",
            "' . $row['scheduled_subtask_completionstatus'] . '" )';

         queryDatabase( $query );
   }
}


function insertDataAboutNewMemberOfStaff( $rowOfDataAboutNewMemberOfStaff )
{
   $query =
      'INSERT INTO members_of_staff (
         member_of_staff_firstname,
         member_of_staff_lastname,
         member_of_staff_loginpassword )
      VALUES (
         "' . $rowOfDataAboutNewMemberOfStaff['member_of_staff_firstname'] . '",
         "' . $rowOfDataAboutNewMemberOfStaff['member_of_staff_lastname'] . '",
         "' . $rowOfDataAboutNewMemberOfStaff['member_of_staff_loginpassword'] . '" )';

   return queryDatabase( $query );
}


function insertDataAboutNewJob( $rowOfDataAboutNewJob )
{
   $query =
      'INSERT INTO print_jobs (
         customer_emailaddress,
         print_job_type_id,
         print_job_name,
         print_job_registrationtime )
      VALUES (
         "' . $rowOfDataAboutNewJob['customer_emailaddress'] . '",
         "' . $rowOfDataAboutNewJob['print_job_type_id'] . '",
         "' . $rowOfDataAboutNewJob['print_job_name'] . '",
         NOW() )';
   return queryDatabase( $query );
}


function insertDataAboutNewCustomer( $rowOfDataAboutNewCustomer )
{
   $query =
      'INSERT INTO customers (
         customer_emailaddress,
         customer_firstname,
         customer_lastname,
         customer_loginpassword )
      VALUES (
         "' . $rowOfDataAboutNewCustomer['customer_emailaddress'] . '",
         "' . $rowOfDataAboutNewCustomer['customer_firstname'] . '",
         "' . $rowOfDataAboutNewCustomer['customer_lastname'] . '",
         "' . $rowOfDataAboutNewCustomer['customer_loginpassword'] . '" )';
   return queryDatabase( $query );
}


function updatePercentOfJobDone( $idOfJob, $percentDone )
{
   $query =
      'UPDATE print_jobs SET print_job_percentdone = "' . $percentDone . '"
      WHERE print_job_id = "' . $idOfJob . '"';
   queryDatabase( $query );
}


function updateCompletionStatusOfSubtaskScheduledForJob( $idOfJob, $idOfSubtask, $completionStatus )
{
   $query =
      'UPDATE scheduled_subtasks SET scheduled_subtask_completionstatus = "' . $completionStatus . '"
      WHERE print_job_id = "' . $idOfJob . '" AND subtask_id ="' . $idOfSubtask . '"';
   queryDatabase( $query );
}


function queryDatabase( $query )
{
   $handleToDatabase = establishDatabaseConnection();
   $resultOfQuery = mysqli_query( $handleToDatabase, $query ) or die( buildErrorMessageForDatabaseQueryFailure() );

   if ( substr( $query, 0, 6 ) == 'INSERT' ) {
      return mysqli_insert_id( $handleToDatabase );
   }
   else if ( $resultOfQuery === true ) {
      return true;
   }

   mysqli_close( $handleToDatabase );

   $rowsFromResultOfQuery = array();
   $row = mysqli_fetch_assoc( $resultOfQuery );

   while ( $row != NULL ) {
      $rowsFromResultOfQuery[] = $row;
      $row = mysqli_fetch_assoc( $resultOfQuery );
   }

   return $rowsFromResultOfQuery;
}


function establishDatabaseConnection()
{
   $handleToDatabase =
      mysqli_connect( 'localhost', 'ifechukwu', 'password', 'apps_database' ) or 
      die( buildErrorMessageForDatabaseConnectionFailure() );

   return $handleToDatabase;
}


function getColumnValueFromFirstRow( $rowsFromResultOfQuery, $nameOfColumn )
{
   if ( sizeof( $rowsFromResultOfQuery ) == 0 ) {
      return NO_ROW_FOUND;
   }
   else {
      $firstRow = $rowsFromResultOfQuery[INDEX_OF_FIRST_ROW];
      return $firstRow[$nameOfColumn];
   }
}
?>