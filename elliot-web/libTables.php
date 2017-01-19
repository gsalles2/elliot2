<?php
/* Terreau, a set of various PHP libraries.
 Copyright (C) 2005-2006  Nayco.

 (Please read the COPYING file)

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA. */

  /**
  *  @file libTables.php
  *  @version 30-03-2006
  *
  *  Functions to generate and manage html tables
  *
  *  @todo Verify the libError calls !
  *  @todo class "TBL_table" : Prevent users from adding rows that haven't got
  *        the right number of fields (!= the columns count).
  */

  include_once("libError.php");
     
  /**
  *  Class to handle html tables, display them, sort them...
  *
  *  NOTE: For the last 3 functions, if $print is set to 1, the result is immediately
  *        printed. If set to 0, it is return to the calling function as a string.
  */ 
  class TBL_table {
    // Class variables
    var $columnsNumber;		///< Number of columns in the table.
    var $rowsNumber;		///< Number of rows in the table.
    var $caption;		///< Contains the table's caption. 
    var $columns=array();	///< Array containing the headers' names.
    var $rows=array();		///< Array containing the rows arrays.
    protected $eh;		///< Points to the error handler

    /**
    *  Constructor
    *  
    *  The constructor only sets the error handler.
    */
    function __construct() {
      $this->eh=ER_Handler::getInstance();
    }

    // Class functions

    /**
    *  sets the table's columns number and names, from an array containing the
    *  columns' names.
    *
    *  @param $columns An array containing the headers' names.
    *  @returns False if parameters are missing, true otherwise.
    */
    function setColumns($columns){
      // If parameters are missing...
      if (empty ($columns) || !is_array($columns)){
				// Display an error and return false
        $source="table::setColumns()";
        $message="Wrong or missing columns values !";
        $hint="Please provide the \$columns array for this function.";
				$this->eh->logError($source, $message, $hint);
				return false;
      }
      // Else register the columns names.
      else {
        $this->columns=$columns;
				$this->columnsNumber=count($columns);
				return true;
      }
    }

    /**
    *  Adds a row to the table, from an array containing values.
    *
    *  @param $row An array containing the row's fields.
    *  @returns False if parameters are missing, true otherwise.
    *
    *  @todo Prevent a row to ba added if its number of fields is not the
    *          same than the columns' number.
    */
    function addRow($row){
      // If parameters are missing...
      if (empty ($row) || !is_array($row)){
				// Display an error and return false
        $source="table::addRow()";
        $message="Wrong or missing row array !";
        $hint="Please provide the \$row array for this function.";
				$this->eh->logError($source, $message, $hint);
        return false;
      }
      // Else register the row.
      else {
        $this->rows[]=$row;
      }
    }
    
    /**
    *  Sets the table's caption.
    *
    *  @param $caption Caption to display near the table.
    *  @returns False if parameters are missing, true otherwise.
    */
    function setCaption($caption){
      // If parameters are missing...
      if (empty($caption)){
				// Display an error and return false
        $source="table::setCaption()";
        $message="Missing caption !";
        $hint="Please provide \$caption for this function.";
      	$this->eh->logError($source, $message, $hint);
	      return false;
      }
      // Else set the table's caption.
      $this->caption=$caption;
			return true;
    }
    
    /**
    *  Displays a given row of the html table.
    *
    *  @param $rowNumber The number of row to display.
    *  @param $print If set to true, the resulting HTML code is echo'ed
    *                immediately, else it is returned for later display.
    *  @returns False if parameter is missing, A string (The HTML code)
    *           if $print is set to false,  true otherwise.
    */
    function displayRow($rowNumber, $print=0){
      // If parameters are missing...
      if (!is_array($this->rows[$rowNumber])){
				// Display an error and return false
        $source="table::displayRow()";
        $message="Missing row number !";
        $hint="Please provide \$rowNumber for this function.";
				$this->eh->logError($source, $message, $hint);
				return false;
      }
      $output="  <tr>\n";
      foreach($this->rows[$rowNumber] as $field){
        $output.="    <td>";
				$output.=(!empty($field))?$field:"&nbsp;";
				$output.= "</td>\n";
      }
      $output.="  </tr>\n";
      // If $print is true, echo it NOW !
      if ($print){
        echo $output;
				return true;
      }
      // Else return it for differed display.
      return $output;
    }

    /**
    *  Displays the html table's headers (&lt;TH&gt;).
    *
    *  @param $print If set to true, the resulting HTML code is echo'ed
    *                immediately, else it is returned for later display.
    *  @returns A string (The HTML code) if $print is set to false,  true otherwise.
    */
    function displayHeaders($print=0){
      $output="  <tr>\n";
      foreach($this->columns as $field){
        $output.="    <th>";
        $output.=(!empty($field))?$field:"&nbsp;";
        $output.="</th>\n";
      }
      $output.="  </tr>\n";
      // If $print is true, echo it NOW !
      if ($print){
        echo $output;
				return true;
      }
      // Else return it for differed display.
      return $output;
    }

    /**
    *  Displays the whole table.
    *
    *  @param $headerRepeat Write the header row each $headerRepeat data
    *                       row, or once if equal to 0 or not specified.
    *  @param $print If set to true, the resulting HTML code is echo'ed
    *                immediately, else it is returned for later display.
    *  @returns A string (The HTML code) if $print is set to false,  true otherwise.
    */
    function displayTable($headerRepeat=0, $print=0){
      $output="<!-- Begin table -->\n";
      $output.="<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">\n";
      $output.="<caption>".$this->caption."</caption>\n";
      $output.=$this->displayHeaders(false);
      for ($rowNumber=0; $rowNumber<count($this->rows); $rowNumber++){
    		// Should we display the headers this time ? Yes, if the table is
	    	// not empty, and the current row number is a multiple of 
				// $headerRepeat.
				if (($headerRepeat!=0) && ($rowNumber!=0)){
				  if (($rowNumber % $headerRepeat)==0)
				    $output.=$this->displayHeaders(false);
				}
        $output.=$this->displayRow($rowNumber, false);
      }
      $output.="</table>\n";
      $output.="<!-- End table -->\n";
      // If $print is true, echo it NOW !
      if ($print){
        echo $output;
        return true;
      }
      // Else return it for differed display.
      return $output;
    }
  } // class TBL_table
?>
