<?php
require_once 'header_files/header_file_common_to_all_pages.php';

$pageContents = buildLevel2HeadingAppendedWithNameOfProductionManager( 'Welcome, ' );
$pageContents .= buildLevel3Heading( 'All registered print jobs are listed below.' );
$pageContents .= buildListOfJobsInPrintShop();

display( $pageContents );
?>