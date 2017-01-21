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
       
       
?>   

 <div class="menu"><!-- Aqui começa o menu, cada <li> é um menu -->
      <ul>
	<li id="menu_general">
		 <span title="Main section">Home</span>
	</li>
	
	<li id="menu_reports">
		<a href="index.php?section=summary" title="Display the summary"><span title="Reports section">Relatorios</span></a>
        </li>
	
	<li id="menu_find">
		<a href="index.php?section=find&amp;searchType=user"><span title="Procura de relatórios"> Procurar</span></a>
<!-- Sub Menus

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
	  <span  href="index.php?section=help" title="Ajuda para usar o Elliot">Ajuda</span>
	</li>   
      </ul>
    </div>
