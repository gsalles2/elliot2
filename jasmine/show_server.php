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

  /* Show_server.php: Displays stats for a given server, 
     passed with $_GET['server']. */
     
  // Includes
  include_once("libJasReports.php");
  

  // Connect to the DB
  DB_connect($DB_host,$DB_login,$DB_pass);
  DB_select($DB_db);

  // Get the server name
  $server=$_GET['server'];
  // Escape the string for later display
  $serverDisplayName=htmlentities($server);
  
  // Get some stats
  $serverTotalPages=jas_getServerTotalPages($server);
  
  // Get server's last month history
  $serverJobHistory=jas_getServerLastJobs($server, 30);
?>
    <!-- Begin server stats -->
      <h2>Stats for server "<?php echo $serverDisplayName; ?>"</h2>
      <p>
        <em>Here are some stats for <strong><?php echo $serverDisplayName ?></strong>: First,
          display all time total number of pages printed by this
          server, then list the jobs printed within the last 30 days.</em>
      </p>
      <h3>Total number of pages</h3>
      <p>
<?php
  if ($serverTotalPages)
    echo "        <em>$serverTotalPages pages were printed on $serverDisplayName</em>\n";
  else
    echo "        An error occured, or this server has never printed anything. Check the error messages.\n";
?>
      </p>
      <h3>Last 30 days history</h3>
      <?=($serverJobHistory)?$serverJobHistory:"<p>An error occured, please check the error messages.</p>"?>
    <!-- End server stats -->

