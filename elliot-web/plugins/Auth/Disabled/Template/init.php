<?php
  /** @file plugins/Auth/Disabled/Template/init.php
  *  @version 0-23.05.2006
  *
  *  @brief TEMPLATE auth plugin
  *
  *  This plugin is the base (that are belongs to us) to rely to
  *	 code your own auth plugin.
  */

  // WARNING: This name MUST be unique across all plugins !
  $PG_current_class="TEMPLATeAuthPlugin";

  /* Main class */
  class TEMPLATeAuthPlugin {
    /* Variables */
    private $something;          //< some class variables.

    /*
		*  Contructor : Initialise what you want here.
		*
		*/
    function TEMPLATeAuthPlugin() {
      //echo "This is the constructor of ".get_class($this)."<br />\n";
    }

		/**
		*  This function take user-provided login and password, and tries
		*  an arbitrary method to authenticate this user, like Ldap, MySQL,
		*	 etc...
		*
		*  @param login the login provided by the user
		*  @param password the password provided by the user
		*  @returns false if the authentification fails, or the username if
    *           it succeeds.
		*/
    function validate($login, $password) {
      //echo "\$login : $login<br />\n";
      //echo "\$password : $password<br />\n";

      // If the authentification succeeded, return the username.
      if ($success==true) {
	      return $userName;
      }
			else {
				return false;
			}
    }

	  /**
    *  This method disconnects the usser. If this auth method does not
    *  need disconnection (LDAP, databases...), we have nothing to do,
    *  so always return true !!!
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
		*	 If it can be done automatically (SSL, CAS, etc...), then try to
		*	 authenticate the user, and return the username if it succeeds, false
		*	 otherwise).
		*
		*  @returns The username if the authentification succeeds, false if
		*           it fails or is not applicable.
		*/
		function autoAuth() {
			return false;
		}
  } // class TEMPLATeAuthPlugin
