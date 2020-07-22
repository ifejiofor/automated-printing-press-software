<?php
// This file should be included in all other pages apart from index.php and log_out.php

session_start();
require_once 'libraries/library_of_boolean_functions.php';
require_once 'libraries/library_of_constants.php';
require_once 'libraries/library_of_database_functions.php';
require_once 'libraries/library_of_error_checking_functions.php';
require_once 'libraries/library_of_filesystem_functions.php';
require_once 'libraries/library_of_miscellaneous_functions.php';
require_once 'libraries/library_of_output_functions.php';

$filenameOfCurrentPage = basename( $_SERVER['PHP_SELF'] );

if ( $filenameOfCurrentPage != 'job_schedule_creation_or_modification.php' && workingScheduleExistsInSession() ) {
   destroyWorkingSchedule();
}

redirectToIndexPageIfLoggedInUserDoNotHaveAccessRightsToCurrentPage();
?>