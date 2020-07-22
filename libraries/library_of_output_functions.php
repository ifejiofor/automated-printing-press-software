<?php
function displayPageContentsForCustomerLogin( $errorMessageText = NULL )
{
   $pageContents = buildLevel2Heading( 'Login as Customer' );

   if ( isset( $_GET['NewCustomerHasJustBeenRegistered'] ) ) {
      $pageContents .=
         buildParagraphContainingTextThatLooksBolder( 'Registration successful! Enter your details below to log in' );
   }

   if ( $errorMessageText != NULL ) {
      $pageContents .= buildParagraphContainingErrorMessage( $errorMessageText );
   }

   $pageContents .= buildCustomerLoginForm();

   display( $pageContents );
}


function buildCustomerLoginForm()
{
   $fieldsForCustomerLoginForm = array();
   $defaultValueForEmailField = isset( $_POST['email'] ) ? $_POST['email'] : '';
   $linkToCustomerRegistrationPage =
      buildLinkThatLooksLikeLink( 'Click here to register as a customer', 'customer_registration.php' );

   $fieldsForCustomerLoginForm[] = buildFieldContainingTextInput( 'Email', 'email', $defaultValueForEmailField );
   $fieldsForCustomerLoginForm[] = buildFieldContainingPasswordInput( 'Password', 'password' );
   $fieldsForCustomerLoginForm[] = buildField( '', '', 'Don\'t have an account yet? ' . $linkToCustomerRegistrationPage );
   $fieldsForCustomerLoginForm[] = buildFieldContainingSubmitButton( 'Log In', 'loginButton' );

   return buildForm( $fieldsForCustomerLoginForm );
}


function displayPageContentsForProductionManagerLogin( $errorMessageText = NULL )
{
   $pageContents = buildLevel2Heading( 'Login as Production Manager' );

   if ( $errorMessageText != NULL ) {
      $pageContents .= buildParagraphContainingErrorMessage( $errorMessageText );
   }

   $pageContents .= buildProductionManagerLoginForm();

   display( $pageContents );
}


function buildProductionManagerLoginForm()
{
   $fieldsForProductionManagerLoginForm = array();

   $fieldsForProductionManagerLoginForm[] =
      buildFieldContainingPasswordInput( 'Production Manager Security Password', 'password' );
   $fieldsForProductionManagerLoginForm[] =
      buildFieldContainingSubmitButton( 'Log In', 'loginButton' );

   return buildForm( $fieldsForProductionManagerLoginForm );
}


function displayPageContentsForMemberOfStaffLogin( $errorMessageText = NULL )
{
   $pageContents = buildLevel2Heading( 'Login as Member of Staff' );

   if ( $errorMessageText != NULL ) {
      $pageContents .= buildParagraphContainingErrorMessage( $errorMessageText );
   }

   $pageContents .= buildMemberOfStaffLoginForm();

   display( $pageContents );
}


function buildMemberOfStaffLoginForm()
{
   $fieldsForMemberOfStaffLoginForm = array();

   $defaultValueForIdOfMemberOfStaff =
      isset( $_POST['prefixedIdOfMemberOfStaff'] ) ? $_POST['prefixedIdOfMemberOfStaff'] : '';

   $fieldsForMemberOfStaffLoginForm[] = 
      buildFieldContainingTextInput( 'Member of Staff ID', 'prefixedIdOfMemberOfStaff',
      $defaultValueForIdOfMemberOfStaff );
   $fieldsForMemberOfStaffLoginForm[] =
      buildFieldContainingPasswordInput( 'Password', 'password' );
   $fieldsForMemberOfStaffLoginForm[] =
      buildFieldContainingSubmitButton( 'Log In', 'loginButton' );

   return buildForm( $fieldsForMemberOfStaffLoginForm );
}


function displayPageContentsForScheduleCreationOrModification( $errorMessageText = NULL )
{
   $percentOfRequiredJobDone = retrievePercentOfJobDone( $_GET['idOfRequiredJob'] );
   $textForHeading = $percentOfRequiredJobDone == NULL ? 'Create Schedule for ' : 'Modify Schedule for ';

   $pageContents = buildLevel2HeadingAppendedWithNameOfRequiredJob( $textForHeading );

   if ( $errorMessageText != NULL ) {
      $pageContents .= buildParagraphContainingErrorMessage( $errorMessageText );
   }

   $pageContents .= buildListOfSubtasksScheduledForRequiredJobAndAreCompleted();
   $pageContents .= buildScheduleCreationOrModificationForm();

   display( $pageContents );
}


function displayPageContentsForJobRegistration( $errorMessageText = NULL )
{
   $pageContentsForJobRegistration = buildLevel2Heading( 'Register New Print Job' );

   if ( $errorMessageText != NULL ) {
      $pageContentsForJobRegistration .= buildParagraphContainingErrorMessage( $errorMessageText );
   }

   $pageContentsForJobRegistration .= buildJobRegistrationForm();

   $pageContentsForListOfRegisteredJobs = buildLevel2Heading( 'All Registered Print Jobs' );
   $pageContentsForListOfRegisteredJobs .= buildListOfAllRegisteredJobs();

   $generalPageContents = buildSectionThatHasOneQuarterWidth( $pageContentsForJobRegistration );
   $generalPageContents .= buildSectionThatHasThreeQuartersWidth( $pageContentsForListOfRegisteredJobs );

   display( $generalPageContents );
}


function buildJobRegistrationForm()
{
   $fields = array();
   $defaultNameOfJob = isset( $_POST['nameOfJob'] ) ? $_POST['nameOfJob']: '';
   $defaultIdOfJobType = isset( $_POST['idOfJobType'] ) ? $_POST['idOfJobType']: '';
   $defaultCustomerEmail = isset( $_POST['customerEmail'] ) ? $_POST['customerEmail']: '';

   $fields[] = buildFieldContainingTextInput( 'Print job name', 'nameOfJob', $defaultNameOfJob );
   $fields[] = buildFieldContainingInputForSelectingJobType( 'Print job type', 'idOfJobType', $defaultIdOfJobType );
   $fields[] =
      buildFieldContainingTextInput( 'Email of customer who owns the job', 'customerEmail', $defaultCustomerEmail );
   $fields[] = buildFieldContainingSubmitButton( 'Register', 'registrationButton' );
   return buildForm( $fields );
}


function buildListOfAllRegisteredJobs()
{
   $rowsOfDataAboutAllRegisteredJobs = retrieveRowsOfDataAboutAllJobs();

   if ( sizeof( $rowsOfDataAboutAllRegisteredJobs ) == 0 ) {
      return buildParagraph( 'No registered job.' );
   }

   $tableRowsOfDataAboutAllRegisterdJobs = array();
   $tableRowsOfDataAboutAllRegisterdJobs[] =
      buildTableHeaderRow( array( 'Name of Job', 'Type of Job', 'Customer', 'Registration Time' ) );

   for ( $i = 0; $i < MAXIMUM_NUMBER_OF_PRINT_JOBS_THAT_SHOULD_BE_DISPLAYED &&
      $i < sizeof( $rowsOfDataAboutAllRegisteredJobs ); $i++ )
   {
      $row = $rowsOfDataAboutAllRegisteredJobs[$i];
      $tableRowsOfDataAboutAllRegisterdJobs[] = buildTableRowOfDetailsAboutRegisteredJob( $row );
   }

   $listOfAllRegisteredJobs = buildTable( $tableRowsOfDataAboutAllRegisterdJobs );

   if ( sizeof( $rowsOfDataAboutAllRegisteredJobs ) > MAXIMUM_NUMBER_OF_PRINT_JOBS_THAT_SHOULD_BE_DISPLAYED ) {
      $currentOffset = isset( $_GET['offset'] ) && consistsOfOnlyDigits( $_GET['offset'] ) ? $_GET['offset']: 0;
      $linkForViewingMoreJobs =
         buildLinkThatLooksLikeLink( 'View More &gt;&gt;',
         'job_registration.php?offset=' . ( $currentOffset + MAXIMUM_NUMBER_OF_PRINT_JOBS_THAT_SHOULD_BE_DISPLAYED ) );
      $listOfAllRegisteredJobs .= $linkForViewingMoreJobs;
   }

   return $listOfAllRegisteredJobs;
}


function buildTableRowOfDetailsAboutRegisteredJob( $rowOfDataAboutRegisteredJob )
{
   $nameOfJob = $rowOfDataAboutRegisteredJob['print_job_name'];
   $nameOfJobType = retrieveNameOfJobType( $rowOfDataAboutRegisteredJob['print_job_type_id'] );
   $nameOfCustomer = retrieveNameOfCustomer( $rowOfDataAboutRegisteredJob['customer_emailaddress'] );
   $timeRegistered = $rowOfDataAboutRegisteredJob['print_job_registrationtime'];
   return buildTableRow( array( $nameOfJob, $nameOfJobType, $nameOfCustomer, $timeRegistered ) );
}


function displayPageContentsForCustomerRegistration( $errorMessageText = NULL )
{
   $pageContents = buildLevel2Heading( 'Register as Customer' );

   if ( $errorMessageText != NULL ) {
      $pageContents .= buildParagraphContainingErrorMessage( $errorMessageText );
   }

   $pageContents .= buildCustomerRegistrationForm();

   display( $pageContents );
}


function buildCustomerRegistrationForm()
{
   $fields = array();
   $defaultEmail = isset( $_POST['email'] ) ? $_POST['email']: '';
   $defaultFirstName = isset( $_POST['firstName'] ) ? $_POST['firstName']: '';
   $defaultLastName = isset( $_POST['lastName'] ) ? $_POST['lastName']: '';
   $defaultLoginPassword = isset( $_POST['loginPassword'] ) ? $_POST['loginPassword']: '';
   $defaultRetypedLoginPassword = isset( $_POST['retypedLoginPassword'] ) ? $_POST['retypedLoginPassword']: '';

   $fields[] = buildFieldContainingTextInput( 'Email', 'email', $defaultEmail );
   $fields[] = buildFieldContainingTextInput( 'First name', 'firstName', $defaultFirstName );
   $fields[] = buildFieldContainingTextInput( 'Last name', 'lastName', $defaultLastName );
   $fields[] = buildFieldContainingPasswordInput( 'Login password', 'loginPassword', $defaultLoginPassword );
   $fields[] =
      buildFieldContainingPasswordInput( 'Re-type login password',
      'retypedLoginPassword', $defaultRetypedLoginPassword );
   $fields[] = buildFieldContainingSubmitButton( 'Register', 'registrationButton' );

   return buildForm( $fields );
}


function displayPageContentsForMemberOfStaffRegistration( $errorMessageText = NULL )
{
   $pageContents = buildLevel2Heading( 'Register a New Member of Staff' );

   if ( $errorMessageText != NULL ) {
      $pageContents .= buildParagraphContainingErrorMessage( $errorMessageText );
   }

   $pageContents .= buildMemberOfStaffRegistrationForm();

   display( $pageContents );
}


function buildMemberOfStaffRegistrationForm()
{
   $fields = array();
   $defaultValueForFirstName = isset( $_POST['firstName'] ) ? $_POST['firstName'] : '';
   $defaultValueForLastName = isset( $_POST['lastName'] ) ? $_POST['lastName'] : '';

   $fields[] = buildFieldContainingTextInput( 'Member of staff first name', 'firstName', $defaultValueForFirstName );
   $fields[] = buildFieldContainingTextInput( 'Member of staff last name', 'lastName', $defaultValueForLastName );
   $fields[] = buildFieldContainingPasswordInput( 'Member of staff login password', 'loginPassword' );
   $fields[] = buildFieldContainingSubmitButton( 'Register', 'registrationButton' );

   return buildForm( $fields );
}


function displayPageContentsForDeletingMemberOfStaff( $errorMessageText = NULL )
{
   $pageContents = buildLevel2Heading( 'Delete Member of Staff' );

   if ( $errorMessageText != NULL ) {
      $pageContents .= buildParagraphContainingErrorMessage( $errorMessageText );
   }

   $pageContents .= buildFormForDeletingMemberOfStaff();

   display( $pageContents );
}


function buildFormForDeletingMemberOfStaff()
{
   $defaultMemberOfStaff =
      isset( $_POST['idOfMemberOfStaffToBeDeleted'] ) ? $_POST['idOfMemberOfStaffToBeDeleted'] : '';

   $fieldContainingSelectInput =
      buildFieldContainingInputForSelectingMemberOfStaff( 'Select member of staff to delete',
      'idOfMemberOfStaffToBeDeleted', $defaultMemberOfStaff );
   $fieldContainingSubmitButton =
      buildFieldContainingSubmitButton( 'Delete', 'deleteButton' );

   return buildForm( array( $fieldContainingSelectInput, $fieldContainingSubmitButton ) );
}


function displayPageContentsForConfirmingDeletionOfRequiredMemberOfStaff()
{
   $pageContents = buildLevel2Heading( 'Delete Member of Staff' );
   $pageContents .= buildLevel3Heading( 'Are you sure you want to delete this member of staff?' );
   $pageContents .= buildDivisionOfDetailsAboutRequiredMemberOfStaff();
   $pageContents .= buildConfirmationButtons( 'delete_member_of_staff.php' );
   display( $pageContents );
}


function buildDivisionOfDetailsAboutRequiredMemberOfStaff()
{
   $detailsAboutRequiredMemberOfStaff = '';

   $description = buildSpanContainingTextThatLooksBolder( 'Member of Staff ID: ' );
   $detail = 'MOS/' . $_GET['idOfRequiredMemberOfStaff'];
   $detailsAboutRequiredMemberOfStaff .= buildParagraph( $description . $detail );

   $description = buildSpanContainingTextThatLooksBolder( 'Name: ' );
   $detail = retrieveNameOfMemberOfStaff( $_GET['idOfRequiredMemberOfStaff'] );
   $detailsAboutRequiredMemberOfStaff .= buildParagraph( $description . $detail );

   return buildDivisionThatIsCentralized( $detailsAboutRequiredMemberOfStaff );
}


function displayPageContentsForConfirmingRequiredScheduledSubtaskAsCompleted()
{
   $pageContents = buildLevel2Heading( 'Report Subtask as Completed' );
   $pageContents .= buildLevel3Heading( 'Are you sure you want to report this subtask as completed?' );
   $pageContents .= buildDivisionOfDetailsAboutRequiredScheduledSubtask();
   $pageContents .= buildConfirmationButtons( 'list_of_subtasks_assigned_to_member_of_staff.php' );
   display( $pageContents );
}


function buildDivisionOfDetailsAboutRequiredScheduledSubtask()
{
   $detailsAboutRequiredScheduledSubtask = '';

   $description = buildSpanContainingTextThatLooksBolder( 'Name of Subtask: ' );
   $detail = retrieveNameOfSubtask( $_GET['idOfRequiredSubtask'] );
   $detailsAboutRequiredScheduledSubtask .= buildParagraph( $description . $detail );

   $description = buildSpanContainingTextThatLooksBolder( 'Associated Print Job: ' );
   $detail = retrieveNameOfJob( $_GET['idOfRequiredJob'] );
   $detailsAboutRequiredScheduledSubtask .= buildParagraph( $description . $detail );

   $description = buildSpanContainingTextThatLooksBolder( 'Proposed Start Time: ' );
   $detail =
      retrieveProposedStartTimeOfSubtaskScheduledForJob( $_GET['idOfRequiredJob'], $_GET['idOfRequiredSubtask'] );
   $detailsAboutRequiredScheduledSubtask .= buildParagraph( $description . $detail );

   $description = buildSpanContainingTextThatLooksBolder( 'Proposed Completion Time: ' );
   $detail =
      retrieveProposedCompletionTimeOfSubtaskScheduledForJob( $_GET['idOfRequiredJob'], $_GET['idOfRequiredSubtask'] );
   $detailsAboutRequiredScheduledSubtask .= buildParagraph( $description . $detail );

   return buildDivisionThatIsCentralized( $detailsAboutRequiredScheduledSubtask );
}


function buildConfirmationButtons( $urlOfDeclinationPage )
{
   $assertionButton =
      buildLinkThatLooksLikeBigButton( 'Yes',
      $_SERVER['PHP_SELF'] . '?assertionButtonHasBeenPressed' . buildStringOfDataFromUrl() );
   $declinationButton =
      buildLinkThatLooksLikeBigButton( 'No',
      $urlOfDeclinationPage );

   $tableRowContainingConfirmationButtons = buildTableRow( array( $assertionButton, $declinationButton ) );
   return buildTable( array( $tableRowContainingConfirmationButtons ) );
}


function buildStringOfDataFromUrl()
{
   $stringOfDataFromUrl = '';

   foreach ( $_GET as $key => $value ) {
      $stringOfDataFromUrl .= '&' . $key . '=' . $value;
   }

   return $stringOfDataFromUrl;
}


function displayPageContentsForFirstStepOfJobCosting( $errorMessageText = NULL )
{
   $pageContents = buildLevel2Heading( 'Get Job Costing' );

   $spanContainingStepNumber = buildSpanContainingTextThatLooksBolder( 'STEP 1 OF 2: ' );
   $pageContents .= buildLevel3Heading( $spanContainingStepNumber . 'Select type of job'  );

   if ( $errorMessageText != NULL ) {
      $pageContents .= buildParagraphContainingErrorMessage( $errorMessageText );
   }

   $pageContents .= buildFormForSelectingJobType();

   display( $pageContents );
}


function displayPageContentsForSecondStepOfJobCosting( $errorMessageText = NULL )
{
   $pageContents = buildLevel2Heading( 'Get Job Costing' );

   $spanContainingStepNumber = buildSpanContainingTextThatLooksBolder( 'STEP 2 OF 2: ' );
   $nameOfJobType = retrieveNameOfJobType( $_GET['idOfRequiredJobType'] );
   $textForLevel3Heading = $spanContainingStepNumber . 'Specify other details about the ' . $nameOfJobType;
   $pageContents .= buildLevel3Heading( $textForLevel3Heading );

   if ( $errorMessageText != NULL ) {
      $pageContents .= buildParagraphContainingErrorMessage( $errorMessageText );
   }

   if ( isCodexJob( $_GET['idOfRequiredJobType'] ) ) {
      $pageContents .= buildFormForSpecifyingDetailsOfCodexJob();
   }
   else {
      $pageContents .= buildFormForSpecifyingDetailsOfSheetJob();
   }

   display( $pageContents );
}


function displayJobCost( $jobCost )
{
   $pageContents = buildLevel2Heading( 'Job Costing' );

   if ( isCodexJob( $_GET['idOfRequiredJobType'] ) ) {
      $pageContents .= buildDivisionOfDetailsAboutSpecifiedCodexJob();
   }
   else {
      $pageContents .= buildDivisionOfDetailsAboutSpecifiedSheetJob();
   }

   $description = buildSpanContainingTextThatLooksBolder( 'Job Cost: ' );
   $formattedJobCost = formatJobCostToDisplayableFormat( $jobCost );
   $paragraphContainingJobCost = buildParagraphContainingTextThatLooksBolder( $description . $formattedJobCost );
   $divisionContainingJobCost = buildDivisionThatIsCentralized( $paragraphContainingJobCost );

   $pageContents .= $divisionContainingJobCost;
   $pageContents .= buildLinkThatLooksLikeBigButton( 'OK', 'list_of_jobs_sent_by_customer.php' );

   display( $pageContents );
}


function formatJobCostToDisplayableFormat( $numericJobCost )
{
   settype( $numericJobCost, 'string');
   $formattedJobCost = '';
   $numberOfDigitsAppendedAfterCommaWasAppended = 0;

   for ( $i = strlen( $numericJobCost ) - 1; $i >= 0; $i-- ) {
      if ( $numberOfDigitsAppendedAfterCommaWasAppended == 3 ) {
         $formattedJobCost .= ',';
         $numberOfDigitsAppendedAfterCommaWasAppended = 0;
      }

      $digit = $numericJobCost[$i];
      $formattedJobCost .= $digit;
      $numberOfDigitsAppendedAfterCommaWasAppended++;
   }

   $formattedJobCost = strrev( $formattedJobCost );
   $formattedJobCost = 'N' . $formattedJobCost;

   return $formattedJobCost;
}


function buildDivisionOfDetailsAboutSpecifiedCodexJob()
{
   $detailsAboutSpecifiedCodexJob = '';

   $description = buildSpanContainingTextThatLooksBolder( 'Type of job: ' );
   $detail = retrieveNameOfJobType( $_GET['idOfRequiredJobType'] );
   $detailsAboutSpecifiedCodexJob .= buildParagraph( $description . $detail );

   $description = buildSpanContainingTextThatLooksBolder( 'Page size: ' );
   $detail = $_POST['pageSize'];
   $detailsAboutSpecifiedCodexJob .= buildParagraph( $description . $detail );

   $description = buildSpanContainingTextThatLooksBolder( 'Colour of cover: ' );
   $detail = $_POST['colourOfCover'];
   $detailsAboutSpecifiedCodexJob .= buildParagraph( $description . $detail );

   $description = buildSpanContainingTextThatLooksBolder( 'Colour of inside pages: ' );
   $detail = $_POST['colourOfInsidePages'];
   $detailsAboutSpecifiedCodexJob .= buildParagraph( $description . $detail );

   $description = buildSpanContainingTextThatLooksBolder( 'Number of inside pages: ' );
   $detail = $_POST['numberOfInsidePages'];
   $detailsAboutSpecifiedCodexJob .= buildParagraph( $description . $detail );

   $description = buildSpanContainingTextThatLooksBolder( 'Number of copies: ' );
   $detail = $_POST['numberOfCopies'];
   $detailsAboutSpecifiedCodexJob .= buildParagraph( $description . $detail );

   return buildDivisionThatIsCentralized( $detailsAboutSpecifiedCodexJob );
}


function buildDivisionOfDetailsAboutSpecifiedSheetJob()
{
   $detailsAboutSpecifiedSheetJob = '';

   $description = buildSpanContainingTextThatLooksBolder( 'Type of job: ' );
   $detail = retrieveNameOfJobType( $_GET['idOfRequiredJobType'] );
   $detailsAboutSpecifiedSheetJob .= buildParagraph( $description . $detail );

   $description = buildSpanContainingTextThatLooksBolder( 'Paper size: ' );
   $detail = $_POST['paperSize'];
   $detailsAboutSpecifiedSheetJob .= buildParagraph( $description . $detail );

   $description = buildSpanContainingTextThatLooksBolder( 'Colour: ' );
   $detail = $_POST['colour'];
   $detailsAboutSpecifiedSheetJob .= buildParagraph( $description . $detail );

   $description = buildSpanContainingTextThatLooksBolder( 'Single or double sides: ' );
   $detail = $_POST['numberOfPrintedSides'];
   $detailsAboutSpecifiedSheetJob .= buildParagraph( $description . $detail );

   $description = buildSpanContainingTextThatLooksBolder( 'Number of copies: ' );
   $detail = $_POST['numberOfCopies'];
   $detailsAboutSpecifiedSheetJob .= buildParagraph( $description . $detail );

   return buildDivisionThatIsCentralized( $detailsAboutSpecifiedSheetJob );
}


function buildListOfSubtasksScheduledForRequiredJobAndAreCompleted()
{
   $tableRowsOfSubtasks = array();

   $contentsOfTableHeaderRow =
      array( 'Serial No.', 'Subtask', 'Assigned To',
      'Proposed Start Time', 'Proposed Completion Time', '' );

   $tableRowsOfSubtasks[] = buildTableHeaderRow( $contentsOfTableHeaderRow );

   $rowsOfDataAboutSubtasks =
      retrieveRowsOfDataAboutSubtasksScheduledForJobAndAreCompleted( $_GET['idOfRequiredJob'] );

   for ( $i = 0; $i < sizeof( $rowsOfDataAboutSubtasks ); $i++ ) {
      $row = $rowsOfDataAboutSubtasks[$i];

      $serialNumber = $row['scheduled_subtask_serialnumber'];
      $nameOfSubtask = retrieveNameOfSubtask( $row['subtask_id'] );
      $nameOfMemberOfStaff = retrieveNameOfMemberOfStaff( $row['member_of_staff_id'] );
      $proposedStartTime = $row['scheduled_subtask_proposedstarttime'];
      $proposedCompletionTime = $row['scheduled_subtask_proposedcompletiontime'];
      $completionStatus = $row['scheduled_subtask_completionstatus'];

      $contentsOfTableRow =
         array( $serialNumber, $nameOfSubtask, $nameOfMemberOfStaff,
         $proposedStartTime, $proposedCompletionTime, $completionStatus );

      $tableRowsOfSubtasks[] = buildTableRow( $contentsOfTableRow );
   }

   return buildTable( $tableRowsOfSubtasks );
}


function buildScheduleCreationOrModificationForm()
{
   if ( workingScheduleDoesNotExistInSession() ) {
      initializeWorkingScheduleWithDataFromDatabase();
   }

   $tableRowsOfFormElements = array();

   for ( $serialNumber = $_SESSION['serialNumberOfFirstSubtaskInWorkingSchedule'];
      $serialNumber <= $_SESSION['serialNumberOfLastSubtaskInWorkingSchedule']; $serialNumber++ )
   {
      $name = 'idOfSubtask' . $serialNumber;
      $defaultValue = $_SESSION['idOfSubtaskInWorkingSchedule'.$serialNumber];
      $inputForSelectingSubtask = buildInputForSelectingSubtask( $name, $defaultValue );

      $name = 'idOfMemberOfStaff' . $serialNumber;
      $defaultValue = $_SESSION['idOfMemberOfStaffInWorkingSchedule'.$serialNumber];
      $inputForSelectingMemberOfStaff = buildInputForSelectingMemberOfStaff( $name, $defaultValue );

      $name = 'proposedStartTime' . $serialNumber;
      $defaultValue = $_SESSION['proposedStartTimeInWorkingSchedule'.$serialNumber];
      $inputForSpecifyingProposedStartTime = buildTimeInput( $name, $defaultValue );

      $name = 'proposedCompletionTime' . $serialNumber;
      $defaultValue = $_SESSION['proposedCompletionTimeInWorkingSchedule'.$serialNumber];
      $inputForSpecifyingProposedCompletionTime = buildTimeInput( $name, $defaultValue );

      $buttonForDeletingSubtask =
         buildSubmitButton( 'Delete Subtask', 'serialNumberOfSubtaskToBeDeleted', $serialNumber );

      $contentsOfTableRow =
         array( $serialNumber, $inputForSelectingSubtask, $inputForSelectingMemberOfStaff,
         $inputForSpecifyingProposedStartTime, $inputForSpecifyingProposedCompletionTime, $buttonForDeletingSubtask );

      $tableRowsOfFormElements[] = buildTableRow( $contentsOfTableRow );
   }

   $buttonForAddingAnotherSubtask =
      buildSubmitButton( 'Add Another Subtask', 'addAnotherSubtaskButton' );
   $tableRowsOfFormElements[] = 
      buildTableRowContainingDataThatSpansSixColumns( array( $buttonForAddingAnotherSubtask ) );

   $cancelButton =
      buildLinkThatLooksLikeBigButton( 'Cancel', 'progress_of_job.php?idOfRequiredJob=' . $_GET['idOfRequiredJob'] );
   $buttonForAuthenticatingSchedule =
      buildBigSubmitButton( 'Authenticate Schedule', 'authenticateScheduleButton' );
   $tableRowsOfFormElements[] =
      buildTableRowContainingDataThatSpansThreeColumns( array( $buttonForAuthenticatingSchedule, $cancelButton ) );

   return
      buildFormContainingTabularFields( $tableRowsOfFormElements,
      $_SERVER['PHP_SELF'] . '?idOfRequiredJob=' . $_GET['idOfRequiredJob'] );
}


function buildFormForSpecifyingDetailsOfCodexJob()
{
   $fields = array();

   $defaultPageSize = isset( $_POST['pageSize'] ) ? $_POST['pageSize']: '';
   $defaultColourOfCover = isset( $_POST['colourOfCover'] ) ? $_POST['colourOfCover']: '';
   $defaultColourOfInsidePages = isset( $_POST['colourOfInsidePages'] ) ? $_POST['colourOfInsidePages']: '';
   $defaultNumberOfInsidePages = isset( $_POST['numberOfInsidePages'] ) ? $_POST['numberOfInsidePages']: '';
   $defaultNumberOfCopies = isset( $_POST['numberOfCopies'] ) ? $_POST['numberOfCopies']: '';

   $fields[] =
      buildFieldContainingInputForSelectingPaperSize( 'Page size', 'pageSize', $defaultPageSize );
   $fields[] =
      buildFieldContainingInputForSelectingInkColour( 'Colour of cover', 'colourOfCover', $defaultColourOfCover );
   $fields[] =
      buildFieldContainingInputForSelectingInkColour( 'Colour of inside pages', 
      'colourOfInsidePages', $defaultColourOfInsidePages );
   $fields[] =
      buildFieldContainingNumberInput( 'Number of inside pages', 'numberOfInsidePages', $defaultNumberOfInsidePages );
   $fields[] =
      buildFieldContainingNumberInput( 'Number of copies', 'numberOfCopies', $defaultNumberOfCopies );
   $fields[] =
      buildFieldContainingSubmitButton( 'Calculate Cost', 'buttonForCalculatingJobCost' );

   return buildForm( $fields, 'second_step_of_job_costing.php?idOfRequiredJobType=' . $_GET['idOfRequiredJobType'] );
}


function buildFormForSpecifyingDetailsOfSheetJob()
{
   $fields = array();

   $defaultPaperSize = isset( $_POST['paperSize'] ) ? $_POST['paperSize']: '';
   $defaultColour = isset( $_POST['colour'] ) ? $_POST['colour']: '';
   $defaultNumberOfSides = isset( $_POST['numberOfPrintedSides'] ) ? $_POST['numberOfPrintedSides']: '';
   $defaultNumberOfCopies = isset( $_POST['numberOfCopies'] ) ? $_POST['numberOfCopies']: '';

   $fields[] =
      buildFieldContainingInputForSelectingPaperSize( 'Paper size', 'paperSize', $defaultPaperSize );
   $fields[] =
      buildFieldContainingInputForSelectingInkColour( 'Colour', 'colour', $defaultColour );
   $fields[] =
      buildFieldContainingInputForSelectingNumberOfPrintedSides( 'Single or double sides',
      'numberOfPrintedSides', $defaultNumberOfSides );
   $fields[] =
      buildFieldContainingNumberInput( 'Number of copies', 'numberOfCopies', $defaultNumberOfCopies );
   $fields[] =
      buildFieldContainingSubmitButton( 'Calculate Cost', 'buttonForCalculatingJobCost' );

   return buildForm( $fields, 'second_step_of_job_costing.php?idOfRequiredJobType=' . $_GET['idOfRequiredJobType'] );
}


function buildFormForSelectingJobType()
{
   $formFields = array();
   $defaultJobType = isset( $_POST['idOfJobType'] ) ? $_POST['idOfJobType'] : '';

   $formFields[] = buildFieldContainingInputForSelectingJobType( 'Type of job', 'idOfJobType', $defaultJobType );
   $formFields[] = buildFieldContainingSubmitButton( 'Next', 'nextButton' );

   return buildForm( $formFields );
}


function buildInputForSelectingSubtask( $name, $defaultSubtask = '' )
{
   if ( dataAboutAllSubtasksDoesNotExistInSession() ) {
      storeDataAboutAllSubtasksIntoSession();
   }

   $optionInputs = array();
   $optionInputs[] = buildOptionInputThatIsNotSelected( '---', '' );

   for ( $i = 0; $i < $_SESSION['totalNumberOfSubtasksStoredInSession']; $i++ ) {
      if ( $_SESSION['idOfSubtask'.$i] == $defaultSubtask ) {
         $optionInputs[] =
            buildOptionInputThatIsSelected( $_SESSION['nameOfSubtask'.$i], $_SESSION['idOfSubtask'.$i] );
      }
      else {
         $optionInputs[] =
            buildOptionInputThatIsNotSelected( $_SESSION['nameOfSubtask'.$i], $_SESSION['idOfSubtask'.$i] );
      }
   }

   return buildSelectInput( $name, $optionInputs );
}


function buildFieldContainingInputForSelectingMemberOfStaff( $labelText, $name, $defaultMemberOfStaff = '' )
{
   $inputForSelectingMemberOfStaff = buildInputForSelectingMemberOfStaff( $name, $defaultMemberOfStaff );
   return buildField( $labelText, $name, $inputForSelectingMemberOfStaff );
}


function buildInputForSelectingMemberOfStaff( $name, $defaultMemberOfStaff = '' )
{
   if ( dataAboutAllMembersOfStaffDoesNotExistInSession() ) {
      storeDataAboutAllMembersOfStaffIntoSession();
   }

   $optionInputs = array();
   $optionInputs[] = buildOptionInputThatIsNotSelected( '---', '' );

   for ( $i = 0; $i < $_SESSION['totalNumberOfMembersOfStaffStoredInSession']; $i++ ) {
      if ( $_SESSION['idOfMemberOfStaff'.$i] == $defaultMemberOfStaff ) {
         $optionInputs[] =
            buildOptionInputThatIsSelected( $_SESSION['nameOfMemberOfStaff'.$i],
            $_SESSION['idOfMemberOfStaff'.$i] );
      }
      else {
         $optionInputs[] =
            buildOptionInputThatIsNotSelected( $_SESSION['nameOfMemberOfStaff'.$i],
            $_SESSION['idOfMemberOfStaff'.$i] );
      }
   }

   return buildSelectInput( $name, $optionInputs );
}


function buildFieldContainingInputForSelectingJobType( $labelText, $name, $defaultJobType = '' )
{
   $inputForSelectingJobType = buildInputForSelectingJobType( $name, $defaultJobType );
   return buildField( $labelText, $name, $inputForSelectingJobType );
}


function buildInputForSelectingJobType( $name, $defaultJobType = '' )
{
   $optionInputs = array();
   $optionInputs[] = buildOptionInputThatIsNotSelected( '---', '' );

   $rowsOfDataAboutAllJobTypes = retrieveRowsOfDataAboutAllJobTypes();

   for ( $i = 0; $i < sizeof( $rowsOfDataAboutAllJobTypes ); $i++ ) {
      $row = $rowsOfDataAboutAllJobTypes[$i];

      if ( $row['print_job_type_id'] == $defaultJobType ) {
         $optionInputs[] = buildOptionInputThatIsSelected( $row['print_job_type_name'], $row['print_job_type_id'] );
      }
      else {
         $optionInputs[] = buildOptionInputThatIsNotSelected( $row['print_job_type_name'], $row['print_job_type_id'] );
      }
   }

   return buildSelectInput( $name, $optionInputs );
}


function buildFieldContainingInputForSelectingPaperSize( $labelText, $name, $defaultPaperSize = '' )
{
   $inputForSelectingPaperSize = buildInputForSelectingPaperSize( $name, $defaultPaperSize );
   return buildField( $labelText, $name, $inputForSelectingPaperSize );
}


function buildInputForSelectingPaperSize( $name, $defaultPaperSize = '' )
{
   $availablePaperSizes = array( 'A0', 'A1', 'A2', 'A3', 'A4', 'A5', 'A6' );
   $optionInputs = array();
   $optionInputs[] = buildOptionInputThatIsNotSelected( '---', '' );

   for ( $i = 0; $i < sizeof( $availablePaperSizes ); $i++ ) {
      $paperSize = $availablePaperSizes[$i];

      if ( $paperSize == $defaultPaperSize ) {
         $optionInputs[] = buildOptionInputThatIsSelected( $paperSize, $paperSize );
      }
      else {
         $optionInputs[] = buildOptionInputThatIsNotSelected( $paperSize, $paperSize );
      }
   }

   return buildSelectInput( $name, $optionInputs );
}


function buildFieldContainingInputForSelectingInkColour( $labelText, $name, $defaultInkColour = '' )
{
   $inputForSelectingInkColour = buildInputForSelectingInkColour( $name, $defaultInkColour );
   return buildField( $labelText, $name, $inputForSelectingInkColour );
}


function buildInputForSelectingInkColour( $name, $defaultInkColour = '' )
{
   $availableInkColours = array( 'Full Colour', 'Black', 'Black + One Colour', 'Black + Two Colours' );
   $optionInputs = array();
   $optionInputs[] = buildOptionInputThatIsNotSelected( '---', '' );

   for ( $i = 0; $i < sizeof( $availableInkColours ); $i++ ) {
      $inkColour = $availableInkColours[$i];

      if ( $inkColour == $defaultInkColour ) {
         $optionInputs[] = buildOptionInputThatIsSelected( $inkColour, $inkColour );
      }
      else {
         $optionInputs[] = buildOptionInputThatIsNotSelected( $inkColour, $inkColour );
      }
   }

   return buildSelectInput( $name, $optionInputs );
}


function buildFieldContainingInputForSelectingNumberOfPrintedSides( $labelText, $name, $defaultNumberOfPrintedSides )
{
   $inputForSelectingNumberOfPrintedSides =
      buildInputForSelectingNumberOfPrintedSides( $name, $defaultNumberOfPrintedSides );
   return buildField( $labelText, $name, $inputForSelectingNumberOfPrintedSides );
}


function buildInputForSelectingNumberOfPrintedSides( $name, $defaultNumberOfPrintedSides = '' )
{
   $optionInputs = array();
   $optionInputs[] = buildOptionInputThatIsNotSelected( '---', '' );

   if ( $defaultNumberOfPrintedSides == 1 ) {
      $optionInputs[] = buildOptionInputThatIsSelected( 'Single Sided', 1 );
   }
   else {
      $optionInputs[] = buildOptionInputThatIsNotSelected( 'Single Sided', 1 );
   }

   if ( $defaultNumberOfPrintedSides == 2 ) {
      $optionInputs[] = buildOptionInputThatIsSelected( 'Double Sided', 2 );
   }
   else {
      $optionInputs[] = buildOptionInputThatIsNotSelected( 'Double Sided', 2 );
   }

   return buildSelectInput( $name, $optionInputs );
}


function buildListOfJobsInPrintShop()
{
   $rowsOfDataAboutAllJobsInPrintShop = retrieveRowsOfDataAboutAllJobs();

   if ( sizeof( $rowsOfDataAboutAllJobsInPrintShop ) == 0 ) {
      return buildParagraph( 'No print job.' );
   }

   $tableRowsOfJobsInPrintShop = array();

   for ( $i = 0; $i < sizeof( $rowsOfDataAboutAllJobsInPrintShop ) &&
      $i < MAXIMUM_NUMBER_OF_PRINT_JOBS_THAT_SHOULD_BE_DISPLAYED; $i++ )
   {
      $row = $rowsOfDataAboutAllJobsInPrintShop[$i];

      $nameOfJob = $row['print_job_name'];
      $nameOfJobType = retrieveNameOfJobType( $row['print_job_type_id'] );
      $nameOfCustomer = retrieveNameOfCustomer( $row['customer_emailaddress'] );
      $formattedPercentDone = formatPrintJobPercentDoneToDisplayableFormat( $row['print_job_percentdone'] );

      if ( $row['print_job_percentdone'] == NULL ) {
         $button = 
            buildLinkThatLooksLikeButton( 'Create&nbsp;Schedule',
            'job_schedule_creation_or_modification.php?idOfRequiredJob=' . $row['print_job_id'] );
      }
      else {
         $button =
            buildLinkThatLooksLikeButton( '&nbsp;&nbsp;View&nbsp;Progress&nbsp;&nbsp;',
            'progress_of_job.php?idOfRequiredJob=' . $row['print_job_id'] );;
      }

      $contentsOfTableRow =
         array( $nameOfJob, $nameOfJobType, 'By ' . $nameOfCustomer, $formattedPercentDone, $button );

      $tableRowsOfJobsInPrintShop[] = buildTableRow( $contentsOfTableRow );
   }

   $listOfJobsInPrintShop = buildTable( $tableRowsOfJobsInPrintShop );

   if ( sizeof( $rowsOfDataAboutAllJobsInPrintShop ) > MAXIMUM_NUMBER_OF_PRINT_JOBS_THAT_SHOULD_BE_DISPLAYED ) {
      $currentOffset = ( isset( $_GET['offset'] ) && consistsOfOnlyDigits( $_GET['offset'] ) ) ? $_GET['offset'] : 0;

      $linkForViewingMoreJobs =
         buildLinkThatLooksLikeLink( 'View More &gt;&gt;',
         'list_of_jobs_in_print_shop.php?offset=' .
         ( $currentOffset + MAXIMUM_NUMBER_OF_PRINT_JOBS_THAT_SHOULD_BE_DISPLAYED ) );

      $listOfJobsInPrintShop .= buildDivision( $linkForViewingMoreJobs );
   }

   return $listOfJobsInPrintShop;
}


function buildListOfJobsSentByCustomer()
{
   $rowsOfDataAboutJobsSentByCustomer = retrieveRowsOfDataAboutJobsSentByCustomer( $_SESSION['idOfLoggedInUser'] );

   if ( sizeof( $rowsOfDataAboutJobsSentByCustomer ) == 0 ) {
      return buildParagraph( 'No print job.' );
   }

   $tableRowsOfJobsSentByCustomer = array();

   for ( $i = 0; $i < sizeof( $rowsOfDataAboutJobsSentByCustomer ) &&
      $i < MAXIMUM_NUMBER_OF_PRINT_JOBS_THAT_SHOULD_BE_DISPLAYED; $i++ )
   {
      $row = $rowsOfDataAboutJobsSentByCustomer[$i];

      $nameOfJob = $row['print_job_name'];
      $nameOfJobType = retrieveNameOfJobType( $row['print_job_type_id'] );
      $formattedPercentDone = formatPrintJobPercentDoneToDisplayableFormat( $row['print_job_percentdone'] );

      if ( $row['print_job_percentdone'] == NULL ) {
         $button = '';
      }
      else {
         $button =
            buildLinkThatLooksLikeButton( 'View&nbsp;Progress',
            'progress_of_job.php?idOfRequiredJob=' . $row['print_job_id'] );
      }

      $contentsOfTableRow = array( $nameOfJob, $nameOfJobType, $formattedPercentDone, $button );

      $tableRowsOfJobsSentByCustomer[] = buildTableRow( $contentsOfTableRow );
   }

   $listOfJobsSentByCustomer = buildTable( $tableRowsOfJobsSentByCustomer );

   if ( sizeof( $rowsOfDataAboutJobsSentByCustomer ) > MAXIMUM_NUMBER_OF_PRINT_JOBS_THAT_SHOULD_BE_DISPLAYED ) {
      $currentOffset = ( isset( $_GET['offset'] ) && consistsOfOnlyDigits( $_GET['offset'] ) ) ? $_GET['offset'] : 0;

      $linkForViewingMoreJobs =
         buildLinkThatLooksLikeLink( 'View More &gt;&gt;',
         'list_of_jobs_sent_by_customer.php?offset=' .
         ( $currentOffset + MAXIMUM_NUMBER_OF_PRINT_JOBS_THAT_SHOULD_BE_DISPLAYED ) );

      $listOfJobsSentByCustomer .= buildDivision( $linkForViewingMoreJobs );
   }

   return $listOfJobsSentByCustomer;
}


function buildDetailedProgressOfRequiredJob()
{
   $tableRowsOfDetailedProgress = array();

   $contentsOfTableHeaderRow =
      array( 'Subtask', 'Assigned To', 'Proposed Start Time', 'Proposed Completion Time', 'Status' );

   $tableRowsOfDetailedProgress[] = buildTableHeaderRow( $contentsOfTableHeaderRow );

   $rowsOfDataAboutScheduledSubtasks = retrieveRowsOfDataAboutSubtasksScheduledForJob( $_GET['idOfRequiredJob'] );

   for ( $i = 0; $i < sizeof( $rowsOfDataAboutScheduledSubtasks ); $i++ ) {
      $row = $rowsOfDataAboutScheduledSubtasks[$i];
      $nameOfSubtask = retrieveNameOfSubtask( $row['subtask_id'] );
      $nameOfMemberOfStaff = retrieveNameOfMemberOfStaff( $row['member_of_staff_id'] );
      $proposedStartTime = $row['scheduled_subtask_proposedstarttime'];
      $proposedCompletionTime = $row['scheduled_subtask_proposedcompletiontime'];
      $completionStatus = $row['scheduled_subtask_completionstatus'];

      $contentsOfTableRow =
         array( $nameOfSubtask, $nameOfMemberOfStaff, $proposedStartTime, $proposedCompletionTime, $completionStatus );

      $tableRowsOfDetailedProgress[] = buildTableRow( $contentsOfTableRow );
   }

   return buildTable( $tableRowsOfDetailedProgress );
}


function buildSummarisedProgressOfRequiredJob()
{
   $dataAboutScheduledSubtaskThatIsInProgress =
      retrieveDataAboutSubtaskScheduledForJobAndIsInProgress( $_GET['idOfRequiredJob'] );

   if ( $dataAboutScheduledSubtaskThatIsInProgress == NO_ROW_FOUND ) {
      return '';
   }

   $proposedCompletionTimeOfScheduledSubtaskThatIsInProgress =
      $dataAboutScheduledSubtaskThatIsInProgress['scheduled_subtask_proposedcompletiontime'];
   $proposedStartTimeOfScheduledSubtaskThatIsInProgress =
      $dataAboutScheduledSubtaskThatIsInProgress['scheduled_subtask_proposedstarttime'];

   $currentTime = date( 'Y-m-d H:i:s' );

   if ( determineWhichTimeIsEarlier( $currentTime, $proposedStartTimeOfScheduledSubtaskThatIsInProgress ) ==
      TIME_1_IS_EARLIER )
   {
      $summaryOfProgress = 'ahead of schedule';
   }
   else if ( determineWhichTimeIsEarlier( $proposedCompletionTimeOfScheduledSubtaskThatIsInProgress, $currentTime ) ==
      TIME_1_IS_EARLIER )
   {
      $summaryOfProgress = 'behind schedule';
   }
   else {
      $summaryOfProgress = 'in line with schedule';
   }

   $summarisedProgressOfRequiredJob = buildLevel3Heading( 'SUMMARY' );
   $summarisedProgressOfRequiredJob .= buildParagraph( 'The print job is ' . $summaryOfProgress );
   return $summarisedProgressOfRequiredJob;
}


function buildListOfAllMembersOfStaff()
{
   $rowsOfDataAboutAllMembersOfStaff = retrieveRowsOfDataAboutAllMembersOfStaff();

   if ( sizeof( $rowsOfDataAboutAllMembersOfStaff ) == 0 ) {
      return buildParagraph( 'No member of staff.' );
   }

   $tableRowsDetailsAboutMembersOfStaff = array();

   for ( $i = 0; $i < sizeof( $rowsOfDataAboutAllMembersOfStaff ); $i++ ) {
      $rowOfDataAboutMemberOfStaff = $rowsOfDataAboutAllMembersOfStaff[$i];
      $tableRowsDetailsAboutMembersOfStaff[] =
         buildTableRowOfDetailsAboutMemberOfStaff( $rowOfDataAboutMemberOfStaff );
   }

   return buildTable( $tableRowsDetailsAboutMembersOfStaff );
}


function buildTableRowOfDetailsAboutMemberOfStaff( $rowOfDataAboutMemberOfStaff )
{
   $idOfMemberOfStaff = $rowOfDataAboutMemberOfStaff['member_of_staff_id'];
   $prefixedIdOfMemberOfStaff = 'MOS/' . $idOfMemberOfStaff;
   $nameOfMemberOfStaff =
      $rowOfDataAboutMemberOfStaff['member_of_staff_firstname'] . ' ' .
      $rowOfDataAboutMemberOfStaff['member_of_staff_lastname'];
   $buttonForViewingAssignedSubtasks =
      buildLinkThatLooksLikeButton( 'View&nbsp;Assigned&nbsp;Subtasks',
      'list_of_subtasks_assigned_to_member_of_staff.php?idOfRequiredMemberOfStaff=' . $idOfMemberOfStaff );

   $contentsOfTableRow = array( $nameOfMemberOfStaff, $prefixedIdOfMemberOfStaff, $buttonForViewingAssignedSubtasks );

   return buildTableRow( $contentsOfTableRow, $idOfMemberOfStaff );
}


function buildNavigationForNavigatingThroughSubtasks()
{
   $slotForIdOfRequiredMemberOfStaff =
      isset( $_GET['idOfRequiredMemberOfStaff'] ) ?
      '&idOfRequiredMemberOfStaff=' . $_GET['idOfRequiredMemberOfStaff'] : '';

   $navigation = '
         <nav>
            <ul>';

   if ( subtasksThatAreInProgressShouldBeDisplayed() ) {
      $navigation .= '
               <li>' . buildLinkThatLooksLikeActiveTab( 'In Progress',
               $_SERVER['PHP_SELF'] . '?requiredCompletionStatus=In Progress' . $slotForIdOfRequiredMemberOfStaff ) .
               '</li>';
   }
   else {
      $navigation .= '
               <li>' . buildLinkThatLooksLikeInactiveTab( 'In Progress',
               $_SERVER['PHP_SELF'] . '?requiredCompletionStatus=In Progress' . $slotForIdOfRequiredMemberOfStaff ) .
               '</li>';
   }

   if ( subtasksThatArePendingShouldBeDisplayed() ) {
      $navigation .= '
               <li>' . buildLinkThatLooksLikeActiveTab( 'Pending',
               $_SERVER['PHP_SELF'] . '?requiredCompletionStatus=Pending' . $slotForIdOfRequiredMemberOfStaff ) .
               '</li>';
   }
   else {
      $navigation .= '
               <li>' . buildLinkThatLooksLikeInactiveTab( 'Pending',
               $_SERVER['PHP_SELF'] . '?requiredCompletionStatus=Pending' . $slotForIdOfRequiredMemberOfStaff ) .
               '</li>';
   }

   if ( subtasksThatAreCompletedShouldBeDisplayed() ) {
      $navigation .= '
               <li>' . buildLinkThatLooksLikeActiveTab( 'Completed',
               $_SERVER['PHP_SELF'] . '?requiredCompletionStatus=Completed' . $slotForIdOfRequiredMemberOfStaff ) .
               '</li>';
   }
   else {
      $navigation .= '
               <li>' . buildLinkThatLooksLikeInactiveTab( 'Completed',
               $_SERVER['PHP_SELF'] . '?requiredCompletionStatus=Completed' . $slotForIdOfRequiredMemberOfStaff ) .
               '</li>';
   }

   $navigation .= '
            </ul>
         </nav>
   ';

   return $navigation;
}


function buildListOfSubtasksAssignedToMemberOfStaff( $idOfMemberOfStaff )
{
   if ( subtasksThatAreInProgressShouldBeDisplayed() ) {
      $rowsOfDataAboutSubtasks =
         retrieveRowsOfDataAboutSubtasksAssignedToMemberOfStaffAndAreInProgress( $idOfMemberOfStaff );
   }
   else if ( subtasksThatArePendingShouldBeDisplayed() ) {
      $rowsOfDataAboutSubtasks =
         retrieveRowsOfDataAboutSubtasksAssignedToMemberOfStaffAndArePending( $idOfMemberOfStaff );
   }
   else if ( subtasksThatAreCompletedShouldBeDisplayed() ) {
      $rowsOfDataAboutSubtasks =
         retrieveRowsOfDataAboutSubtasksAssignedToMemberOfStaffAndAreCompleted( $idOfMemberOfStaff );
   }

   if ( sizeof( $rowsOfDataAboutSubtasks ) == 0 ) {
      return buildParagraph( 'No subtask.' );
   }

   $tableRowsOfSubtasks = array();

   if ( userIsLoggedInAsMemberOfStaff() && subtasksThatAreInProgressShouldBeDisplayed() ) {
      $contentsOfTableHeaderRow =
         array( 'Subtask', 'Associated Print Job', 'Proposed Start Time', 'Proposed Completion Time', '' );
   }
   else {
      $contentsOfTableHeaderRow =
         array( 'Subtask', 'Associated Print Job', 'Proposed Start Time', 'Proposed Completion Time' );
   }

   $tableRowsOfSubtasks[] = buildTableHeaderRow( $contentsOfTableHeaderRow );

   for ( $i = 0; $i < MAXIMUM_NUMBER_OF_SUBTASKS_THAT_SHOULD_BE_DISPLAYED &&
      $i < sizeof( $rowsOfDataAboutSubtasks ); $i++ )
   {
      $rowOfDataAboutSubtask = $rowsOfDataAboutSubtasks[$i];
      $tableRowsOfSubtasks[] = buildTableRowOfDetailsAboutSubtaskAssignedToMemberOfStaff( $rowOfDataAboutSubtask );
   }

   $listOfSubtasksAssignedToMemberOfStaff = buildTable( $tableRowsOfSubtasks );

   if ( sizeof( $rowsOfDataAboutSubtasks ) > MAXIMUM_NUMBER_OF_SUBTASKS_THAT_SHOULD_BE_DISPLAYED ) {
      $linkForViewingMoreSubtasks = buildLinkForViewingMoreSubtasksAssignedToMemberOfStaff();
      $listOfSubtasksAssignedToMemberOfStaff .= buildDivision( $linkForViewingMoreSubtasks );
   }

   return $listOfSubtasksAssignedToMemberOfStaff;
}


function buildTableRowOfDetailsAboutSubtaskAssignedToMemberOfStaff( $rowOfDataAboutSubtaskAssignedToMemberOfStaff )
{
   $idOfSubtask = $rowOfDataAboutSubtaskAssignedToMemberOfStaff['subtask_id'];
   $idOfJob = $rowOfDataAboutSubtaskAssignedToMemberOfStaff['print_job_id'];

   $nameOfSubtask = retrieveNameOfSubtask( $idOfSubtask );
   $nameOfJob = retrieveNameOfJob( $idOfJob );
   $proposedStartTime = $rowOfDataAboutSubtaskAssignedToMemberOfStaff['scheduled_subtask_proposedstarttime'];
   $proposedCompletionTime = $rowOfDataAboutSubtaskAssignedToMemberOfStaff['scheduled_subtask_proposedcompletiontime'];

   if ( userIsLoggedInAsMemberOfStaff() && subtasksThatAreInProgressShouldBeDisplayed() ) {
      $buttonForReportingSubtaskCompletion =
         buildLinkThatLooksLikeButton( 'Report&nbsp;as&nbsp;Completed',
         'confirm_subtask_as_completed.php?idOfRequiredJob=' . $idOfJob . '&idOfRequiredSubtask=' . $idOfSubtask );

      $contentsOfTableRow =
         array( $nameOfSubtask, $nameOfJob, $proposedStartTime,
         $proposedCompletionTime, $buttonForReportingSubtaskCompletion );
   }
   else {
      $contentsOfTableRow =
         array( $nameOfSubtask, $nameOfJob, $proposedStartTime,
         $proposedCompletionTime );
   }

   return buildTableRow( $contentsOfTableRow );
}


function buildLinkForViewingMoreSubtasksAssignedToMemberOfStaff()
{
   $currentOffset = ( isset( $_GET['offset'] ) && consistsOfOnlyDigits( $_GET['offset'] ) ) ? $_GET['offset'] : 0;
   $slotForRequiredCompletionStatus =
      isset( $_GET['requiredCompletionStatus'] ) ?
      '&requiredCompletionStatus=' . $_GET['requiredCompletionStatus'] : '';
   $slotForIdOfRequiredMemberOfStaff =
      isset( $_GET['idOfRequiredMemberOfStaff'] ) ?
      '&idOfRequiredMemberOfStaff=' . $_GET['idOfRequiredMemberOfStaff'] : '';

   return
      buildLinkThatLooksLikeLink( 'View More &gt;&gt;',
      'list_of_subtasks_assigned_to_member_of_staff.php?' .
      'offset=' . ( $currentOffset + MAXIMUM_NUMBER_OF_SUBTASKS_THAT_SHOULD_BE_DISPLAYED ) .
      $slotForRequiredCompletionStatus . $slotForIdOfRequiredMemberOfStaff );
}


function buildLevel2HeadingAppendedWithNameOfLoggedInCustomer( $text )
{
   $nameOfLoggedInCustomer = retrieveNameOfCustomer( $_SESSION['idOfLoggedInUser'] );
   $text .= $nameOfLoggedInCustomer;
   return buildLevel2Heading( $text );
}


function buildLevel2HeadingAppendedWithNameOfLoggedInMemberOfStaff( $text )
{
   $nameOfLoggedInMemberOfStaff = retrieveNameOfMemberOfStaff( $_SESSION['idOfLoggedInUser'] );
   $text .= $nameOfLoggedInMemberOfStaff;
   return buildLevel2Heading( $text );
}


function buildLevel2HeadingAppendedWithNameOfRequiredMemberOfStaff( $text )
{
   $nameOfRequiredMemberOfStaff = retrieveNameOfMemberOfStaff( $_GET['idOfRequiredMemberOfStaff'] );
   $text .= $nameOfRequiredMemberOfStaff;
   return buildLevel2Heading( $text );
}


function buildLevel2HeadingAppendedWithNameOfProductionManager( $text )
{
   $firstNameOfProductionManager =
      readLineOfTextFromDataFile( 'production_manager.dat',  LINE_NUMBER_OF_LINE_WHERE_FIRST_NAME_IS_STORED );
   $lastNameOfProductionManager =
      readLineOfTextFromDataFile( 'production_manager.dat', LINE_NUMBER_OF_LINE_WHERE_LAST_NAME_IS_STORED );
   $nameOfProductionManager = $firstNameOfProductionManager . ' ' . $lastNameOfProductionManager;
   $text .= $nameOfProductionManager;
   return buildLevel2Heading( $text );
}


function buildLevel2HeadingAppendedWithNameOfRequiredJob( $text )
{
   $nameOfRequiredJob = retrieveNameOfJob( $_GET['idOfRequiredJob'] );
   $text .= '"' . $nameOfRequiredJob . '"';
   return buildLevel2Heading( $text );
}


function buildLevel2Heading( $text )
{
   return '
         <h2>' . $text . '</h2>
   ';
}


function buildLevel3Heading( $text )
{
   return '
         <h3>' . $text . '</h3>
   ';
}


function buildParagraph( $text )
{
   return '
         <p>' . $text . '</p>
   ';
}


function buildParagraphContainingErrorMessage( $errorMessageText )
{
   return '
         <p class="apps-error-message">' . $errorMessageText . '</p>
   ';
}


function buildParagraphContainingTextThatLooksBolder( $text )
{
   return '
         <p class="apps-bolder-text">' . $text . '</p>';
}


function buildSpanContainingTextThatLooksBolder( $text )
{
   return '<span class="apps-bolder-text">' . $text . '</span>';
}


function buildLinkThatLooksLikeLink( $text, $href )
{
   return '<a href="' . $href . '" class="apps-link">' . $text . '</a>';
}


function buildLinkThatLooksLikeButton( $text, $href )
{
   return '<a href="' . $href . '" class="apps-button">' . $text . '</a>';
}


function buildLinkThatLooksLikeBigButton( $text, $href )
{
   return '<a href="' . $href . '" class="apps-big-button">' . $text . '</a>';
}


function buildLinkThatLooksLikeActiveTab( $text, $href )
{
   return '<a href="' . $href . '" class="apps-active-tab">' . $text . '</a>';
}


function buildLinkThatLooksLikeInactiveTab( $text, $href )
{
   return '<a href="' . $href . '" class="apps-inactive-tab">' . $text . '</a>';
}


function buildUnorderedList( $contentsOfList )
{
   $unorderedList = '
         <ul>';

   foreach ( $contentsOfList as $key => $listItem ) {
      $unorderedList .= '
            <li>' . $listItem . '</li>';
   }

   $unorderedList .= '
         </ul>
   ';

   return $unorderedList;
}


function buildSectionThatHasOneQuarterWidth( $text )
{
   return '
         <section class="apps-one-quarter-width">
            ' . $text . '
         </section>
   ';
}


function buildSectionThatHasThreeQuartersWidth( $text )
{
   return '
         <section class="apps-three-quarters-width">
            ' . $text . '
         </section>
   ';
}


function buildDivisionThatIsCentralized( $text )
{
   return '
         <div class="apps-centralized-div">
            ' . $text . '
         </div>
   ';
}


function buildDivision( $text )
{
   return '
         <div>
            ' . $text . '
         </div>
   ';
}


function buildForm( $fields, $action = NULL )
{
   $form = '
         <form method="POST" action="' . ( $action == NULL ? $_SERVER['PHP_SELF'] : $action ) . '" class="apps-form">';

   foreach ( $fields as $key => $field ) {
      $form .= $field;
   }

   $form .= '
         </form>
   ';

   return $form;
}


function buildFormContainingTabularFields( $tableRowsOfFormElements, $action = NULL )
{
   $form = '
         <form method="POST" action="' . ( $action == NULL ? $_SERVER['PHP_SELF'] : $action ) . '" ' .
            'class="apps-tabular-form apps-container-for-table">
            <table>';

   foreach ( $tableRowsOfFormElements as $key => $tableRow ) {
      $form .= $tableRow;
   }
 
   $form .= '
            </table>
         </form>
   ';

   return $form;
}


function buildFieldContainingNumberInput( $labelText, $name, $value = '' )
{
   $label = buildLabel( $labelText, $name );
   $numberInput = buildNumberInput( $name, $value );

   return '
            <div class="apps-form-field">
               ' . $label . '
               <div>' . $numberInput . '</div>
            </div>
   ';
}


function buildFieldContainingTextInput( $labelText, $name, $value = '' )
{
   $label = buildLabel( $labelText, $name );
   $textInput = buildTextInput( $name, $value );

   return '
            <div class="apps-form-field">
               ' . $label . '
               <div>' . $textInput . '</div>
            </div>
   ';
}


function buildFieldContainingPasswordInput( $labelText, $name, $value = '' )
{
   $label = buildLabel( $labelText, $name );
   $passwordInput = buildPasswordInput( $name, $value );

   return '
            <div class="apps-form-field">
               ' . $label . '
               <div>' . $passwordInput . '</div>
            </div>
   ';
}


function buildField( $labelText, $name, $input )
{
   $label = buildLabel( $labelText, $name );

   return '
            <div class="apps-form-field">
               ' . $label . '
               <div>' . $input . '</div>
            </div>
   ';
}


function buildFieldContainingSubmitButton( $text, $name, $value = '' )
{
   $submitButton = buildSubmitButton( $text, $name, $value );

   return '
            <div class="apps-form-field">
               ' . $submitButton . '
            </div>
   ';
}


function buildLabel( $text, $for )
{
   return '<label for="' . $for . '">' . $text . '</label>';
}


function buildNumberInput( $name, $value = '' )
{
   return '<input type="number" name="' . $name . '" value="' . $value . '" id="' . $name . '" />';
}


function buildTextInput( $name, $value = '' )
{
   return '<input type="text" name="' . $name . '" value="' . $value . '" id="' . $name . '" />';
}


function buildPasswordInput( $name, $value = '' )
{
   return '<input type="password" name="' . $name . '" value="' . $value . '" id="' . $name . '" />';
}


function buildTimeInput( $name, $value = '' )
{
   return '<input type="datetime-local" name="' . $name . '" value="' . $value . '" id="' . $name . '" />';
}


function buildOptionInputThatIsNotSelected( $text, $value )
{
   return '
                        <option value="' . $value . '">' . $text . '</option>';
}


function buildOptionInputThatIsSelected( $text, $value )
{
   return '
                        <option value="' . $value . '" selected>' . $text . '</option>';
}


function buildSelectInput( $name, $optionInputs )
{
   $selectInput = '
                     <select name="' . $name . '" id="' . $name . '">';

   foreach ( $optionInputs as $key => $optionInput ) {
      $selectInput .= $optionInput;
   }

   $selectInput .= '
                     </select>
               ';

   return $selectInput;
}


function buildSubmitButton( $text, $name, $value = '' )
{
   return
      '<button type="submit" name="' . $name . '" value="' . $value . '" class="apps-button">' .
      $text . '</button>';
}


function buildBigSubmitButton( $text, $name, $value = '' )
{
   return
      '<button type="submit" name="' . $name . '" value="' . $value . '" class="apps-big-button">' .
      $text . '</button>';
}


function buildTableHeaderRow( $contentsForTableHeaderRow )
{
   $numberOfColumns = sizeof( $contentsForTableHeaderRow );

   $tableHeaderRow = '
               <tr>';

   foreach ( $contentsForTableHeaderRow as $key => $content ) {
      $tableHeaderRow .= '
                  <th class="one-of-' . $numberOfColumns . '-columns">' . $content . '</th>';
   }

   $tableHeaderRow .= '
               </tr>
   ';

   return $tableHeaderRow;
}


function buildTableRow( $contentsForTableRow, $id = NULL )
{
   $numberOfColumns = sizeof( $contentsForTableRow );

   $tableRow = '
               <tr' . ( $id == NULL ? '' : ' id="' . $id . '"' ) . '>';

   foreach ( $contentsForTableRow as $key => $content ) {
      $tableRow .= '
                  <td class="one-of-' . $numberOfColumns . '-columns">' . $content . '</td>';
   }

   $tableRow .= '
               </tr>
   ';

   return $tableRow;
}


function buildTableRowContainingDataThatSpansThreeColumns( $contentsForTableRow )
{
   $tableRow = '
               <tr>';

   foreach ( $contentsForTableRow as $key => $content ) {
      $tableRow .= '
                  <td colspan="3">' . $content . '</td>';
   }

   $tableRow .= '
               </tr>
   ';

   return $tableRow;
}


function buildTableRowContainingDataThatSpansSixColumns( $contentsForTableRow )
{
   $tableRow = '
               <tr>';

   foreach ( $contentsForTableRow as $key => $content ) {
      $tableRow .= '
                  <td colspan="6">' . $content . '</td>';
   }

   $tableRow .= '
               </tr>
   ';

   return $tableRow;
}


function buildTable( $tableRows )
{
   $table = '
         <div class="apps-container-for-table">
            <table>';

   foreach ( $tableRows as $key => $tableRow ) {
      $table .= $tableRow;
   }

   $table .= '
            </table>
         </div>
   ';

   return $table;
}


function buildErrorMessageForDatabaseConnectionFailure()
{
   $errorMessage = buildParagraphContainingErrorMessage( 'Unable to connect to database' );
   return buildEntirePage( $errorMessage );
}


function buildErrorMessageForDatabaseQueryFailure()
{
   $errorMessage = buildParagraphContainingErrorMessage( 'Unable to query database' );
   return buildEntirePage( $errorMessage );
}


function buildErrorMessageForFileOpenFailure()
{
   $errorMessage = buildParagraphContainingErrorMessage( 'Unable to open file' );
   return buildEntirePage( $errorMessage );
}


function buildErrorMessageForFileReadFailure()
{
   $errorMessage = buildParagraphContainingErrorMessage( 'Unable to read file' );
   return buildEntirePage( $errorMessage );
}


function formatPrintJobPercentDoneToDisplayableFormat( $printJobPercentDoneFromDatabase )
{
   if ( $printJobPercentDoneFromDatabase == NULL ) {
      return 'Not yet commenced';
   }
   else if ( $printJobPercentDoneFromDatabase == 100 ) {
      return 'Completed';
   }
   else {
      return $printJobPercentDoneFromDatabase . '% done';
   }
}


function display( $pageContents )
{
   echo buildEntirePage( $pageContents );
}


function buildEntirePage( $pageContents )
{
   $titleOfCurrentPage = buildTitleOfCurrentPage();
   $menuBar = buildMenuBar();
   $paragraphContainingLoginStatus = buildParagraphContainingLoginStatus();

   return '
<!DOCTYPE html>

<html>
   <head>
      ' . $titleOfCurrentPage . '
      <link href="stylesheets/main_stylesheet.css" rel="stylesheet" type="text/css" />
   </head>

   <body>
      <header class="apps-main-header">
         <h1><a href="index.php">Automated Printing Press Software</a></h1>
      </header>
      ' . $menuBar . '

      <div class="apps-main-container">
         ' . $pageContents . '
      </div>

      <footer class="apps-main-footer">
         ' . $paragraphContainingLoginStatus . '
      </footer>
   </body>
</html>
   ';
}


function buildTitleOfCurrentPage()
{
   $filenameOfCurrentPage = basename( $_SERVER['PHP_SELF'] );

   switch ( $filenameOfCurrentPage ) {
      case 'confirm_deletion_of_member_of_staff.php':
         $titleOfCurrentPage = 'Delete a Member of Staff';
         break;
      case 'confirm_subtask_as_completed.php':
         $titleOfCurrentPage = 'Report Subtask as Completed';
      case 'customer_login.php':
         $titleOfCurrentPage = 'Customer Login';
         break;
      case 'customer_registration.php':
         $titleOfCurrentPage = '';
         break;
      case 'delete_member_of_staff.php':
         $titleOfCurrentPage = 'Delete a Member of Staff';
         break;
      case 'first_step_of_job_costing.php':
         $titleOfCurrentPage = 'Get Job Costing Step 1 |';
         break;
      case 'home.php':
         $titleOfCurrentPage = 'Home';
         break;
      case 'job_registration.php':
         $titleOfCurrentPage = 'Job Registration';
         break;
      case 'job_schedule_creation_or_modification.php':
         $percentOfRequiredJobDone = retrievePercentOfJobDone( $_GET['idOfRequiredJob'] );
         $titleOfCurrentPage = $percentOfRequiredJobDone == NULL ? 'Create Schedule' : 'Modify Schedule';
         break;
      case 'list_of_all_members_of_staff.php':
         $titleOfCurrentPage = 'All Members of Staff';
         break;
      case 'list_of_jobs_in_print_shop.php':
         $titleOfCurrentPage = 'Registered Print Jobs';
         break;
      case 'list_of_jobs_sent_by_customer.php':
         $titleOfCurrentPage = 'Print Jobs Sent by You';
         break;
      case 'list_of_subtasks_assigned_to_member_of_staff.php':
         $titleOfCurrentPage = 
            userIsLoggedInAsMemberOfStaff() ? 'Subtasks Assigned to You' : 'Subtasks Assigned to Member of Staff';
         break;
      case 'member_of_staff_login.php':
         $titleOfCurrentPage = 'Member of Staff Login';
         break;
      case 'member_of_staff_registration.php':
         $titleOfCurrentPage = 'Register a New Member of Staff';
         break;
      case 'production_manager_login.php':
         $titleOfCurrentPage = 'Production Manager Login';
         break;
      case 'progress_of_job.php':
         $titleOfCurrentPage = 'Print Job Progress';
         break;
      case 'second_step_of_job_costing.php':
         $titleOfCurrentPage = 'Job Costing Step 2';
         break;
      default:
         $titleOfCurrentPage = '';
         break;
   }

   return '<title>' . $titleOfCurrentPage . ' | Automated Printing Press Software</title>';
}


function buildMenuBar()
{
   if ( userIsLoggedInAsCustomer() ) {
      return '
      <nav class="apps-main-menu">
         <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="first_step_of_job_costing.php">Get Job Costing</a></li>
            <li><a href="log_out.php">Log Out</a></li>
         </ul>
      </nav>';
   }
   else if ( userIsLoggedInAsProductionManager() ) {
      return '
      <nav class="apps-main-menu">
         <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="list_of_all_members_of_staff.php">View Members of Staff</a></li>
            <li><a href="log_out.php">Log Out</a></li>
         </ul>
      </nav>';
   }
   else if ( userIsLoggedInAsFrontDeskOfficer() ) {
      return '
      <nav class="apps-main-menu">
         <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="log_out.php">Log Out</a></li>
         </ul>
      </nav>';
   }
   else if ( userIsLoggedInAsMemberOfStaff() ) {
      return '
      <nav class="apps-main-menu">
         <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="log_out.php">Log Out</a></li>
         </ul>
      </nav>';
   }
   else {
      return '';
   }
}


function buildParagraphContainingLoginStatus()
{
   if ( userIsLoggedInAsCustomer() ) {
      $loginStatus = 'Logged In as Customer';
   }
   else if ( userIsLoggedInAsMemberOfStaff() ) {
      $loginStatus = 'Logged In as Member of Staff';
   }
   else if ( userIsLoggedInAsFrontDeskOfficer() ) {
      $loginStatus = 'Logged In as Front Desk Officer';
   }
   else if ( userIsLoggedInAsProductionManager() ) {
      $loginStatus = 'Logged In as Production Manager';
   }
   else {
      $loginStatus = 'Not Logged In';
   }

   return '<p>' . $loginStatus . '</p>';
}
?>