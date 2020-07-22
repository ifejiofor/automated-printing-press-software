<?php
session_start();
require_once 'libraries/library_of_boolean_functions.php';
require_once 'libraries/library_of_constants.php';

if ( userIsLoggedInAsCustomer() ) {
   header( 'Location: list_of_jobs_sent_by_customer.php' );
}
else if ( userIsLoggedInAsProductionManager() ) {
   header( 'Location: list_of_jobs_in_print_shop.php' );
}
else if ( userIsLoggedInAsFrontDeskOfficer() ) {
   header( 'Location: job_registration.php' );
}
else if ( userIsLoggedInAsMemberOfStaff() ) {
   header( 'Location: list_of_subtasks_assigned_to_member_of_staff.php' );
}
else {
   header( 'Location: home.php' );
}
?>