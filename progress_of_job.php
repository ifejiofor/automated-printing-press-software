<?php
require_once 'header_files/header_file_common_to_all_pages.php';

redirectToIndexPageIfIdOfRequiredJobIsInvalid();
redirectToIndexPageIfScheduleHasNotBeenCreatedForRequiredJob();

$urlOfPageContainingListOfJobs =
   userIsLoggedInAsCustomer() ? 'list_of_jobs_sent_by_customer.php' : 'list_of_jobs_in_print_shop.php';
$linkForGoingBack = buildLinkThatLooksLikeLink( '&lt;&lt; Go Back to List of Jobs', $urlOfPageContainingListOfJobs );

$pageContents = buildLevel2HeadingAppendedWithNameOfRequiredJob( 'Progress of ' );
$pageContents .= buildDivision( $linkForGoingBack );
$pageContents .= buildDetailedProgressOfRequiredJob();
$pageContents .= buildSummarisedProgressOfRequiredJob();

if ( userIsLoggedInAsProductionManager() ) {
   $percentOfJobDone = retrievePercentOfJobDone( $_GET['idOfRequiredJob'] );

   if ( $percentOfJobDone < 100 ) {
      $pageContents .= 
         buildLinkThatLooksLikeButton( 'Modify Schedule', 
         'job_schedule_creation_or_modification.php?idOfRequiredJob=' . $_GET['idOfRequiredJob'] );
   }
}

display( $pageContents );
?>