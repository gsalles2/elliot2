<?php
/* Elliot, print accounting system for Cups.

 (Please read the COPYING file)

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 as published by the Free Software Foundation; either version 
 of the License, or (at your option) any later version.2

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA. */

  /* Summary.php: Displays a summary of overall  printing
     activity. */

  include_once("libJasReports.php");

  DB_connect($DB_host,$DB_login,$DB_pass);
  DB_select($DB_db);

  $totalusers=jas_getUserRankings(1000);
  
?><!-- Inicio do Sumario -->

<h2>Relatório completo dos usuários</h2>

<?=($totalusers)?$totalusers:"<p>An error occured, please check the error messages.</p>"?>

<h3>Exportar dados</h3>
<p>
	Selecione o tipo de extensão que você quer seu relatório.
</p>

<p>
<a href="export.php"><input type="submit" value="XLS" ></a>
<input type="submit" value="PDF" >
<input type="submit" value="TXT" >
</p>

<!-- End Summary -->