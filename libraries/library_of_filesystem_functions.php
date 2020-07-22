<?php
function readLineOfTextFromDataFile( $fileName, $lineNumber )
{
   $lineOfText = '';

   $filePath = 'data_files/' . $fileName;
   $handleToFile = fopen( $filePath, 'r' ) or die( buildErrorMessageForFileOpenFailure() );

   for ( $currentLineNumber = 1; $currentLineNumber <= $lineNumber; $currentLineNumber++ ) {
      $lineOfText = fgets( $handleToFile ) or die( buildErrorMessageForFileReadFailure() );
   }

   fclose( $handleToFile );

   return $lineOfText;
}


function getColumnValueFromLineOfText( $lineOfText, $columnNumber )
{
   $columnValue = '';
   $indexOfFirstTabAfterPreviousColumn = 0 - NUMBER_OF_TABS_AFTER_EACH_COLUMN;

   $currentColumnNumber = 1;

   while ( $currentColumnNumber <= $columnNumber ) {
      $indexOfFirstCharacterInCurrentColumn = $indexOfFirstTabAfterPreviousColumn + NUMBER_OF_TABS_AFTER_EACH_COLUMN;
      $indexOfFirstTabAfterCurrentColumn = strpos( $lineOfText, "\t", $indexOfFirstCharacterInCurrentColumn );

      if ( $indexOfFirstTabAfterCurrentColumn === false ) {
         return '';
      }

      $numberOfCharactersInCurrentColumn = $indexOfFirstTabAfterCurrentColumn - $indexOfFirstCharacterInCurrentColumn;
      $columnValue = substr( $lineOfText, $indexOfFirstCharacterInCurrentColumn, $numberOfCharactersInCurrentColumn );

      $currentColumnNumber++;
      $indexOfFirstTabAfterPreviousColumn = $indexOfFirstTabAfterCurrentColumn;
   }

   return $columnValue;
}
?>