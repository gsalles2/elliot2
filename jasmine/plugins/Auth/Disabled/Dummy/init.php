<?php
  /** @file plugins/Auth/Disabled/Dummy/init.php
  *  @version 0-23.05.2006
  *
  *  @brief Auth test plugin 
  *
  *  This plugin always says "no" to an authentification request.
  */

  // WARNING: This name MUST be unique across all plugins !
  $PG_current_class="AuthTestPlugin";

  /* Main class */
  class AuthTestPlugin {
    /* Variables */

    /* Contructor */
    function AuthTestPlugin() {
      //echo "This is the constructor of ".get_class($this)."<br />\n";
    }

    function validate($login, $password) {
      //echo "\$login : $login<br />\n";
      //echo "\$password : $password<br />\n";
      return false;
    }
    function install() {
      // Not stable, will implement later.
    }
  } // class AuthTestPlugin
