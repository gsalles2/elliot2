<?php
/* JASmine, print accounting system for Cups.
 Copyright (C) Nayco.

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
     
  /* Find.php: Provides a search page to find printers and users */
       
  /* Includes */
  include_once("libJasReports.php");
  
  /* Displays the search form */     
  function display_search_form ($objectType, $searchString=""){
  
    $me=htmlentities($_SERVER["PHP_SELF"]);
    $searchString=htmlentities($searchString);
    $objectType=(!empty($objectType))?htmlentities($objectType):"";
    $check_user=($objectType=="" || $objectType=="user")?"checked ":"";
    $check_printer=($objectType=="printer")?"checked ":"";
    $check_server=($objectType=="server")?"checked ":"";
    
    echo "<!-- Begin search form -->\n";
    echo "<div id=\"search_form\">\n";
    echo "  <form name=\"search_form\" action=\"$me?section=find\" method=\"post\">\n";
    echo "    <label>\n";
    echo "      <input type=\"text\" name=\"iSearchString\" value=\"$searchString\" />\n";
    echo "      Text to search\n";
    echo "    </label>\n";
    echo "    <fieldset>\n";
    echo "      <legend>Object type</legend>\n";
    echo "      <label>\n";
    echo "        <input type=\"radio\" name=\"iSearchType\" value=\"user\" $check_user/>\n";
    echo "      Users</label>\n";
    echo "      <label>\n";
    echo "        <input type=\"radio\" name=\"iSearchType\" value=\"printer\" $check_printer/>\n";
    echo "      Printer</label>\n";
    echo "      <label>\n";
    echo "        <input type=\"radio\" name=\"iSearchType\" value=\"server\" $check_server/>\n";
    echo "      Servers</label>\n";
    echo "    </fieldset>\n";
    echo "    <input type=\"submit\" value=\"submit\" \>\n";
    echo "    <input type=\"reset\" value=\"clear\" />\n";
    echo "  </form>\n";
    echo "</div> \n";
    echo "<!-- End search form -->\n";
  }
  
  /* Function to fetch the results, and display them 
     TODO: WRITE A BETTER DESCRIPTION !!! */
  function display_results($searchString, $objectType){  
    echo "<!-- Begin search results -->\n";
    if(!$result=jas_searchObject($searchString, $objectType)){
      echo "<p><em>Query failed.</em></p>\n";
      return false;
    }
    else{
      if ($result>0){
        echo "<p>\n";
	echo "  <em>The following results were returned:</em>\n";
        echo "  <ul>\n";
	foreach ($result as $line){
	  $lineDisplay=htmlentities($line);
	  echo "    <li><a href=\"?section=show_$objectType&amp;$objectType=$line\">$lineDisplay</a></li>\n";
	}
	echo "  </ul>\n";
	echo "</p>\n";
      }
      else{
        echo "  <em>No result.</em>\n";
      }
    }      
    echo "<!-- End search results -->\n";
  }
  
  // Begin to process the page...
  $iSearchString=$_POST['iSearchString'];
  if(empty($_POST['iSearchType'])){
    if(empty($_GET['searchType']))
      $iObjectType="user";
    else
      $iObjectType=$_GET['searchType'];
   }
  else
    $iObjectType=$_POST['iSearchType'];
?>
    <h2>Search for objects</h2>
<?php
  /*echo "\$_POST['iSearchString']=".$_POST['iSearchString']."\n<br />";
  echo "\$_POST['iSearchType']=".$_POST['iSearchType']."\n<br />";
  echo "\$_GET['searchType']=".$_GET['searchType']."\n<br />";
  echo "\$iSearchString=$iSearchString\n<br />";
  echo "\$iObjectType=$iObjectType\n<br />"; */
?>
    <h3>Help</h3>
    <p>Some help...</p>
<?php
  display_search_form($iObjectType, $iSearchString);

  if (!empty($_POST['iSearchString'])){
    echo "    <h3>Results</h3>\n";
    DB_connect($DB_host,$DB_login,$DB_pass);
    DB_select($DB_db);
    display_results($iSearchString, $iObjectType);
  }
?>
