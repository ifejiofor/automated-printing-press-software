<?php
function userIsLoggedInAsCustomer()
{
   return
      isset( $_SESSION['loginStatus'] ) && $_SESSION['loginStatus'] == LOGGED_IN_AS_CUSTOMER &&
      isset( $_SESSION['idOfLoggedInUser'] );
}


function userIsLoggedInAsProductionManager()
{
   return isset( $_SESSION['loginStatus'] ) && $_SESSION['loginStatus'] == LOGGED_IN_AS_PRODUCTION_MANAGER;
}


function userIsLoggedInAsFrontDeskOfficer()
{
   return
      isset( $_SESSION['loginStatus'] ) && $_SESSION['loginStatus'] == LOGGED_IN_AS_FRONT_DESK_OFFICER &&
      isset( $_SESSION['idOfLoggedInUser'] );
}


function userIsLoggedInAsMemberOfStaff()
{
   return
      isset( $_SESSION['loginStatus'] ) && $_SESSION['loginStatus'] == LOGGED_IN_AS_MEMBER_OF_STAFF &&
      isset( $_SESSION['idOfLoggedInUser'] );
}


function userIsNotLoggedIn()
{
   return !isset( $_SESSION['loginStatus'] );
}


function customersDoNotHaveAccessRightsToCurrentPage()
{
   return !customersHaveAccessRightsToCurrentPage();
}


function customersHaveAccessRightsToCurrentPage()
{
   $fileNameOfCurrentPage = basename( $_SERVER['PHP_SELF'] );

   switch ( $fileNameOfCurrentPage ) {
      case 'first_step_of_job_costing.php':
      case 'index.php':
      case 'list_of_jobs_sent_by_customer.php':
      case 'log_out.php':
      case 'progress_of_job.php':
      case 'second_step_of_job_costing.php':
         return true;
      default:
         return false;
   }
}


function productionManagersDoNotHaveAccessRightsToCurrentPage()
{
   return !productionManagersHaveAccessRightsToCurrentPage();
}


function productionManagersHaveAccessRightsToCurrentPage()
{
   $fileNameOfCurrentPage = basename( $_SERVER['PHP_SELF'] );

   switch ( $fileNameOfCurrentPage ) {
      case 'confirm_deletion_of_member_of_staff.php':
      case 'delete_member_of_staff.php':
      case 'index.php':
      case 'job_schedule_creation_or_modification.php':
      case 'list_of_all_members_of_staff.php':
      case 'list_of_jobs_in_print_shop.php':
      case 'list_of_subtasks_assigned_to_member_of_staff.php':
      case 'log_out.php':
      case 'member_of_staff_registration.php':
      case 'progress_of_job.php':
         return true;
      default:
         return false;
   }
}


function frontDeskOfficersDoNotHaveAccessRightsToCurrentPage()
{
   return !frontDeskOfficersHaveAccessRightsToCurrentPage();
}


function frontDeskOfficersHaveAccessRightsToCurrentPage()
{
   $fileNameOfCurrentPage = basename( $_SERVER['PHP_SELF'] );

   switch ( $fileNameOfCurrentPage ) {
      case 'index.php':
      case 'job_registration.php':
      case 'log_out.php':
         return true;
      default:
         return false;
   }
}


function membersOfStaffDoNotHaveAccessRightsToCurrentPage()
{
   return !membersOfStaffHaveAccessRightsToCurrentPage();
}


function membersOfStaffHaveAccessRightsToCurrentPage()
{
   $fileNameOfCurrentPage = basename( $_SERVER['PHP_SELF'] );

   switch ( $fileNameOfCurrentPage ) {
      case 'confirm_subtask_as_completed.php':
      case 'index.php':
      case 'list_of_subtasks_assigned_to_member_of_staff.php':
      case 'log_out.php':
         return true;
      default:
         return false;
   }
}


function loggedOutUsersDoNotHaveAccessRightsToCurrentPage()
{
   return !loggedOutUsersHaveAccessRightsToCurrentPage();
}


function loggedOutUsersHaveAccessRightsToCurrentPage()
{
   $fileNameOfCurrentPage = basename( $_SERVER['PHP_SELF'] );

   switch ( $fileNameOfCurrentPage ) {
      case 'customer_login.php':
      case 'customer_registration.php':
      case 'home.php':
      case 'index.php':
      case 'member_of_staff_login.php':
      case 'production_manager_login.php':
         return true;
      default:
         return false;
   }
}


function userHasNotPressedSubmitButton()
{
   return !$_POST;
}


function userHasNotPressedAssertionButton()
{
   return !isset( $_GET['assertionButtonHasBeenPressed'] );
}


function workingScheduleDoesNotExistInSession()
{
   return !workingScheduleExistsInSession();
}


function workingScheduleExistsInSession()
{
   return isset ( $_SESSION['serialNumberOfFirstSubtaskInWorkingSchedule'] );
}


function dataAboutAllSubtasksDoesNotExistInSession()
{
   return !dataAboutAllSubtasksExistsInSession();
}


function dataAboutAllSubtasksExistsInSession()
{
   return isset( $_SESSION['totalNumberOfSubtasksStoredInSession'] );
}


function dataAboutAllMembersOfStaffDoesNotExistInSession()
{
   return !dataAboutAllMembersOfStaffExistsInSession();
}


function dataAboutAllMembersOfStaffExistsInSession()
{
   return isset( $_SESSION['totalNumberOfMembersOfStaffStoredInSession'] );
}


function doesNotConsistOfOnlyDigits( $string )
{
   return !consistsOfOnlyDigits( $string );
}


function consistsOfOnlyDigits( $string )
{
   for ( $i = 0; $i < strlen( $string ) && isDigit( $string[$i] ); $i++ )
      ;

   return $i == strlen( $string );
}


function isDigit( $character )
{
   return
      $character == '0' || $character == '1' || $character == '2' || $character == '3' || $character == '4' ||
      $character == '5' || $character == '6' || $character == '7' || $character == '8' || $character == '9';
}


function doesNotConsistOfOnlyAlphabets( $string )
{
   return !consistsOfOnlyAlphabets( $string );
}


function consistsOfOnlyAlphabets( $string )
{
   for ( $i = 0; $i < strlen( $string ) && isAlphabet( $string[$i] ); $i++ )
      ;

   return $i == strlen( $string );
}


function isAlphabet( $character )
{
   $character = strtolower( $character );

   return
      $character == 'a' || $character == 'b' || $character == 'c' || $character == 'd' || $character == 'e' ||
      $character == 'f' || $character == 'g' || $character == 'h' || $character == 'i' || $character == 'j' ||
      $character == 'k' || $character == 'l' || $character == 'm' || $character == 'n' || $character == 'o' ||
      $character == 'p' || $character == 'q' || $character == 'r' || $character == 's' || $character == 't' ||
      $character == 'u' || $character == 'v' || $character == 'w' || $character == 'x' || $character == 'y' ||
      $character == 'z';
}


function doesNotConsistOfOnlyEmailCharacters( $string )
{
   return !consistsOfOnlyEmailCharacters( $string );
}


function consistsOfOnlyEmailCharacters( $string )
{
   for ( $i = 0; $i < strlen( $string ) && isEmailCharacter( $string[$i] ); $i++ )
      ;

   return $i == strlen( $string );
}


function isEmailCharacter( $character )
{
   return isAlphabet( $character ) || isDigit( $character ) || $character == '.' || $character == '_';
}


function dayDoesNotBelongToMonth( $day, $month, $year )
{
   switch ( $month ) {
      case 1:
      case 3:
      case 5:
      case 7:
      case 8:
      case 10:
      case 12:
         $numberOfDaysInMonth = 31;
         break;

      case 4:
      case 6:
      case 9:
      case 11:
         $numberOfDaysInMonth = 30;
         break;

      case 2:
         $numberOfDaysInMonth = isLeapYear( $year ) ? 29 : 28;
         break;

      default:
         $numberOfDaysInMonth = 0;
         break;
   }

   return $day < 1 || $day > $numberOfDaysInMonth;
}


function isLeapYear( $year )
{
   return ( $year % 4 == 0 && $year % 100 != 0 ) || $year % 400 == 0;
}


function isCodexJob( $idOfJobType )
{
   $classOfJobType = retrieveClassOfJobType( $idOfJobType );
   return $classOfJobType == 'CODEX';
}


function subtasksThatAreInProgressShouldBeDisplayed()
{
   return
      !isset( $_GET['requiredCompletionStatus'] ) ||
      ( $_GET['requiredCompletionStatus'] != 'Pending' && $_GET['requiredCompletionStatus'] != 'Completed' );
}


function subtasksThatArePendingShouldBeDisplayed()
{
   return isset( $_GET['requiredCompletionStatus'] ) && $_GET['requiredCompletionStatus'] == 'Pending';
}


function subtasksThatAreCompletedShouldBeDisplayed()
{
   return isset( $_GET['requiredCompletionStatus'] ) && $_GET['requiredCompletionStatus'] == 'Completed';
}
?>