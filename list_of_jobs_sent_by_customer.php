<?php
require_once 'header_files/header_file_common_to_all_pages.php';

$pageContents = buildLevel2HeadingAppendedWithNameOfLoggedInCustomer( 'Welcome, ' );
$pageContents .= buildLevel3Heading( 'Your print jobs are listed below.' );
$pageContents .= buildListOfJobsSentByCustomer();

display( $pageContents );
?>