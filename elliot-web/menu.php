<?php
/* Elliot, print accounting system for Cups.
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

  /* Menu.php: Displays the main menu */


    include_once("libJasReports.php");

  DB_connect($DB_host,$DB_login,$DB_pass);
  DB_select($DB_db);

  $Total=jas_getServertotal(5)       
?>

<div class="salles">
	<div class="box_menu">
			<div class="box_server">
			<h4><a href="index.php?section=show_server"> <?=($Total)?$Total:"<p>An error occured, please check the error messages.</p>"?></a></h4>
			</div>
	</div>
	<div class="menu"><!-- Aqui começa o menu, cada <li> é um menu -->
	      <ul>
		<li id="menu_general">
		<a href="index.php?sextion=index" title="Home"> <span title="Main section">Home</span></a>
		</li>
	
		<li id="menu_reports">
			<a href="index.php?section=summary" title="Gerar Relatórios"><span title="Seção de Relatórios">Relatorios</span></a>
	        </li>
	
		<li id="menu_find">
			<a href="index.php?section=find&amp;searchType=user"><span title="Procura de relatórios"> Procurar</span></a>

<!-- Sub Menus Exemplos:

<ul>
<li>
<a href="index.php?section=find&amp;searchType=printer" title="Find a printer">Impressoras</a>
</li>
<li>
<a href="index.php?section=find&amp;searchType=user" title="Find a user">Usuários</a>
</li>
</ul>
-->
		</li>
		<li>
		<a href="index.php?section=help "><span  title="Manual do Elliot">Ajuda</span></a>
		</li>   
	      </ul>
	</div>
</div>
