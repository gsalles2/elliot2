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
  *  @file libDB_mysql.php
  *  Provides abstraction functions to deal with Mysql servers.
  *
  *  This is not "hardcore" abstraction, but more a bunch of wrapper functions
  *  to catch errors and display them cleanly through libError.php. Other
  *  functions in this library are various database-related ones. This library
  *  checks for the mysql php extension presence when loaded, and dies if not
  *  present.
  *
  *  @version 0-24.06.2006
  *  @todo Modify this library to make it Object Oriented.
  *  @todo When converting to a class, do not forget to close the MySQL
  *        connection in the destructor.
  */

  include_once("libError.php");

  // Before all, check that MySQL support is installed for PHP
  // to avoid the following functions to die silently !
  if (!extension_loaded('mysql')){
    $message="MySQL support does not seem to be installed on this server";
    $hint="Check that the MySQL support for PHP is installed: It is usually ";
    $hint.="a package called something like \"php-mysql\" on GNU/Linux distribs.";
    $hint.="Do not forget to restart your web server if needed.";
    ER_Handler::getInstance()->logCrit("No MySQL support", $message, $hint);
    // this is a little dirty and violent, but hopefully won't happen
    // too often...
    ER_Handler::getInstance()->displayEvents();
    die();
  }

  /**
  *  Opens a connection to a Mysql server, and returns the connection ID
  *  
  *  @param $host The host to connect to.
  *  @param $user The login used to connect to the server.
  *  @param $pass The password used to connect to the server.
  *  @returns A MySQL connection ID, or false if the connection fails.
  */
  function DB_connect($host,$user,$pass){
    if ($id=@mysql_connect($host,$user,$pass)) // Assignment !
      return $id;
    else {
      $message="Unable to connect to host \"$host\": ".mysql_error();
      $hint="Check that the MySQL host is up, and that you gave the right hostname.";
      ER_Handler::getInstance()->logCrit("DB_connect", $message, $hint);
      return false;
    }
  }

  /**
  *  Selects a database on the current connection ID.
  *
  *  @param $db Name of the database to select.
  *  @returns True if success or false on failure.
  */
  function DB_select($db){
    if (@mysql_select_db($db))
      return true;
    else {
      $message="Unable to select database \"$db\": ".mysql_error();
      $hint="Check that this database exists, and that you gave the right name.";
      ER_Handler::getInstance()->logCrit("DB_select", $message, $hint);
      return false;
    }
  }

  /**
  *  Runs a query
  *
  *  @param $query A string containing the MySQL query.
  *  @returns The result ID or false if the query fails
  */
  function DB_query($query){
    if ($result=@mysql_query($query)) // Assignment !
      return $result;
    else {
      $message="Unable to run query \"$query\": ".mysql_error();
      $hint="Check the syntax of this query, and that the requested data exists.";
      ER_Handler::getInstance()->logCrit("DB_query", $message, $hint);
      return false;
    }
  }

  /**
  *  Dumps a query result in an HTML table.
  *
  *  This function returns the result of a query as an HTML table.
  *  Content and format of the result are guessed automatically.
  *  Alternatively, the result can be printed immediately, not returned,
  *  based on the value of the $print variable
  *
  *  @param $result A valid query result ID
  *  @param $print Whether to return the result in a string or echo it
  *                directly. 0 means "return a string", 1 means "echo
  *                it now"
  */
  function DB_dump_result($result, $print=0){
    if (! $result)
      return false;
    if (! $fields_nb=mysql_num_fields($result))
      return false;

    $output="<!-- Starting query result dump  -->\n";
    $output.="  <table border=\"1\" cellpadding=\"2\" cellspacing=\"0\">\n";
    $output.="    <tr>\n";

    for ($i=0; $i<$fields_nb; $i++){
      $field_names[$i]=mysql_field_name($result, $i);
      $output.="      <th>$field_names[$i]</th>\n";
    }

    $output.="  </tr>\n";

    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $output.="    <tr>\n";
      foreach($field_names as $this_field){  // foreach($row) does not work here without "MYSQL_ASSOC"
        $output.=($row[$this_field])?"      <td>$row[$this_field]</td>\n":"<td>&nbsp;</td>\n";
      }
      $output.="    </tr>\n";
    }

    $output.="  </table>\n";
    $output.="<!-- End query result dump -->\n";

    // Finally output the result, with either method, based
    // on the coder's choice.
    if ($print){
      echo $output;
    }
    return $output;
  }

  /**
  *  Function that generates an HTML dropdown list from a query result.
  *
  *  To use this, you need to specify the names of two fields of the query :
  *  $text_field (The text to display in the select list) and $id_field (The
  *  corresponding uniique ID). One can too provide the current value of that
  *  field, if known, so that this value is selected as default in the list
  *  (Useful for example when displaying a form to modify an entry...)
  *
  *  @param $result A valid MySQL result.
  *  @param $text_field The query field to use to get the legend for each &lt;select&gt; line.
  *  @param $id_field The query field to use to get the unique id for each &lt;select&gt; line
  *                   (Usually the primary key of the MySQL table).
  *  @param $current_value The id of the item to mark as selected. Usually the result of
  *                        the corresponding field in the last form submit.
  *  @returns A string containing the &lt;select&gt; list, which lines match the query, ready
  *            to be echo()'ed;
  *  @todo Rename the input vars to remove "_" ?
  */
  function DB_dropdown_list($result, $text_field, $id_field, $current_value=""){
    $output="<select name=i_$text_field>\n";
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      if ($current_value==$row[$id_field])
        $selected=" selected";
      else
        $selected="";
       $output.="  <option value=$row[$id_field]".$selected.">".stripslashes($row[$text_field])."</option>\n";
    }
    $output.="</select>\n";
    return $output;
  }

  /**
  *  Function to clean inputs before querying a database, mandatory to protect the
  *  project from "SQL injections".
  *
  *  To unescape the string, "stripslashes()" is enough. Optionnally, one can request
  *  this function not to single-quote the result, by setting $quote to 'false'. This
  *  function was stolen from http://php.net examples ;-)
  *
  *  @param $string The string to escape before using in a MySQL query.
  *  @param $quote Set to 'true' to force enclosure of the output with single-quotes,
  *                false otherwise.
  *  @returns The escaped string, ready for a MySQL query.
  *
  *  @todo Rewrite the error handling, and use another criticity.
  */
  function DB_escape_string($string, $quote=false){
    // Stripslashes if slashes already present.
    if (get_magic_quotes_gpc()) {
      $string = stripslashes($string);
    }
    // Escape if not integer value.
    if (!is_numeric($string)) {
      // This one will fail if no connection to the SQL server, so:
      if($string=@mysql_real_escape_string($string)){ // Assignment !!!
        $string=($quote==true)?"'$string'":$string;
      }
      else{
        $string="'".mysql_escape_string($string)."'";
        $message="Unable to real_escape string: ".mysql_error();
        $hint="This happens when the MySQL server cannot be reached: Check that it is up !";
	ER_Handler::getInstance()->logCrit("DB_escape_string", $message, $hint);
      }
    }
    return $string;
  }
?>
