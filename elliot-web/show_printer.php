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

  /* Show_printer.php: Displays stats for a given printer, 
     passed with $_GET['printer']. */
     
  // Includes
  include_once("libJasReports.php");
  

  // Connect to the DB
  DB_connect($DB_host,$DB_login,$DB_pass);
  DB_select($DB_db);

  // Get the printer name
  $printer=$_GET['printer'];
  // Escape the string for later display
  $printerDisplayName=htmlentities($printer);
  
  // Get some stats
  $printerTotalPages=jas_getPrinterTotalPages($printer);
  
  // Get printer's last month history
  $printerJobHistory=jas_getPrinterLastJobs($printer, 30);
?>
    <!-- Begin printer stats -->
      <h2>Stats for printer "<?php echo $printerDisplayName; ?>"</h2>
      <p>
        <em>Here are some stats for <strong><?php echo $printerDisplayName; ?></strong>: First,
          display all time total number of pages printed by this
          printer, then list the jobs printed within the last 30 days.</em>
      </p>
      <h3>Total number of pages</h3>
      <p>
<?php
  if ($printerTotalPages)
    echo "        <em>$printerTotalPages pages were printed on $printerDisplayName</em>\n";
  else
    echo "        An error occured, or this printer has never printed anything. Check the error messages.\n";
?>
      </p>
      <h3>Last 30 days history</h3>
      <?=($printerJobHistory)?$printerJobHistory:"<p>An error occured, please check the error messages.</p>"?>
    <!-- End printer stats -->

