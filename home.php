<?php
require_once 'header_files/header_file_common_to_all_pages.php';

$linksToLoginPages = array();

$linksToLoginPages[] =
   buildLinkThatLooksLikeBigButton( 'Log In/Register as Customer', 'customer_login.php' );
$linksToLoginPages[] =
   buildLinkThatLooksLikeBigButton( 'Log In as Production Manager', 'production_manager_login.php' );
$linksToLoginPages[] =
   buildLinkThatLooksLikeBigButton( 'Log In as Member of Staff', 'member_of_staff_login.php' );

$listOfLinks = buildUnorderedList( $linksToLoginPages );

display( $listOfLinks );
?>