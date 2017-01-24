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

?> 
<div class="report_page">


<h3>BEM VINDO AO ELLIOT</h3>
<br>
<p><h4>O Elliot é um sistema de relatórios integrado ao CUPS.</p>

<p>Uma versão remodulada do grandioso JASmine.</p>
<p>Por favor leiam os arquivos de licença e obtenha mais informações sobre os autores originas e também sobre nossas modificações.</p>

<p>Esperamos que gostem do Elliot, ele é bem simpático e futuramente será mais interativo ^^(ele é um pouco tímido aínda).</p>

<p><a href="index.php?section=help">Caso precise de ajuda veja nossos tutorias</a></p>

<p>Façam bom proveito.</h4></p>
<br>
<p><i>À bientôt</i></p>

</div>
<?php
  // Display errors here
  ER_Handler::getInstance()->displayEvents();

  // Ending includes...
  @include_once("footer.php");
?>
