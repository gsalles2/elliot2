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

  /* Menu.php: Displays the main menu */
       
       
?>    <div class="menu">
      <h2>Menu</h2>
      <ul>
        <li id="menu_general">
	  <span title="Main section">Home</span>
          <ul>
	    <li>
	      <a href="index.php?section=help" title="Get some help using JASmine">Ajuda</a>
	    </li>
          </ul>
	</li>
        <li id="menu_reports">
               <a href="index.php?section=summary" title="Display the summary"><span title="Reports section">Relatorios</span></a>
        </li>
	<li id="menu_find">
          <span title="Find a report or an object">Procurar</span>
          <ul>
            <li>
              <a href="index.php?section=find&amp;searchType=printer" title="Find a printer">Impressoras</a>
            </li>
	    <li>
		<a href="index.php?section=find&amp;searchType=user" title="Find a user">Usuários</a>
            </li>
           <!--  <li>
		<a href="index.php?section=find&amp;searchType=server" title="Find a server">Servidores</a>
            </li> -->
          </ul>
        </li>
      </ul>
    </div>
