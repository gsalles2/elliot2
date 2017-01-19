<?php
  /** @file plugins/Auth/Disabled/Plain/init.php
  *  @version 0-23.05.2006
  *
  *  @brief Plaintext auth plugin
  *
  *  This plugin always provides authentification based on a
  *  plaintext file, like /etc/passwd. Useful if you cannot
  *  access a database or directory server.
  */

  // WARNING: This name MUST be unique across all plugins !
  $PG_current_class="plainAuthPlugin";

  /* Main class */
  class plainAuthPlugin {
    /* Variables */
    private $separator=":";
    private $authFile="users.db";

    /*
    *  Contructor : Initialise what you want here.
    *
    */
    function plainAuthPlugin() {
      //echo "This is the constructor of ".get_class($this)."<br />\n";
    }

    /**
    *  This function take user-provided login and password, and tries
    *  an arbitrary method to authenticate this user, like Ldap, MySQL,
    *  etc...
    *
    *  @param login the login provided by the user
    *  @param password the password provided by the user
    *  @returns false if the authentification fails, or the username if
    *           it succeeds.
    */
    function validate($login, $password) {
      //echo "\$login : $login<br />\n";
      //echo "\$password : $password<br />\n";
      $file=file($this->authFile, 1);
      foreach ($file as $id => $ligne) {
				if (rtrim($ligne)==$login.$this->separator.$password) {
	  			return true;
				}
      }
      return false;
    }

    /**
    *  This method disconnects the user. As we here use a certificate
    *  to authenticate, we have nothing to do to disconnect, so always
    *  return true !!!
    *
    *  @returns true if successful, false otherwise. Anyway, false can
    *           be ignored by the auth system, as it will clear the PHP
    *           session in both cases.
    */
    function disconnect(){
      return true;
    }

    /**
    *  This function returns false if the used auth method cannot be
    *  done without user action (ie fill login/password in the form...).
    *  If it can be done automatically (SSL, CAS, etc...), then try to
    *  authenticate the user, and return true if it succeeds, false
    *  otherwise).
    *
    *  @returns The username if the authentification succeeds, false if
    *           it fails or is not applicable.
    */
    function autoAuth() {
      return false;
    }
  } // class plainAuthPlugin
