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

  /* Index.php: Main file */

  // Do some includes.
  include_once("jasConfig.php");
  include_once("config.php");
  include_once("libError.php");
  include_once("header.php");
  include_once("menu.php");

  if (!file_exists("config.php")){
    $message="Config file not found !";
    $hint="Don't forget to copy \"config.php.dist\" to \"config.php\", then edit ";
    $hint.="it to suit your needs.";
    ER_Handler::getInstance()->logCrit("No Config file", $message, $hint);
  }

?>    <div class="report_page">
<?php
  // Include a file to fill the main body of the page, based on the $_GET[section] variable.
  // If the requested file is not found, fallback to $DEFAULT_STARTPAGE (Defined in config.php)
  if (isset($_GET['section']) && file_exists($_GET['section'].".php")){
    include_once($_GET['section'].".php");
  }
  else{
    include_once($DEFAULT_STARTPAGE.".php");
  }
?>    </div>
<?php
  // Display errors here
  ER_Handler::getInstance()->displayEvents();

  // Ending includes...
  @include_once("footer.php");
?>
