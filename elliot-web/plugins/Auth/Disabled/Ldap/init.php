<?php
  /** @file plugins/Auth/Disabled/Ldap/init.php
  *  @version 0-23.05.2006
  *
  *  @brief Ldap auth plugin 
  *
  *  This plugin permits ldap-based authentification.
  */

  // WARNING: This name MUST be unique across all plugins !
  $PG_current_class="ldapAuthPlugin";

  /* Main class */
  class ldapAuthPlugin {
    /* Variables */
		private $connectionID;		//< Ldap connection resource identifier.
		
    /* Contructor */
    function ldapAuthPlugin() {
      //echo "This is the constructor of ".get_class($this)."<br />\n";

			// Load the config file !
			require "config.php";
    }

    /**
    *  This function take user-provided login and password, and tries
    *  an to authenticate this user using the LDAP server set in
    *  config.php.
    *
    *  @param login the login provided by the user
    *  @param password the password provided by the user
    *  @returns false if the authentification fails, or the username if
    *           it succeeds.
    */
    function validate($login, $password) {
      //echo "\$login : $login<br />\n";
      //echo "\$password : $password<br />\n";
			
			// Connect to the ldap server
			$this->connectionID=ldap_connect($this->ldapServer);

			// First, bind anonymously and retrieve the full DN corresponding to
			// the login provided by the user, as well as the user name to display.
			$success=ldap_bind($this->connectionID);
			$searchString="(&(objectClass=person)($this->ldapSearchAttribute=$login))";
			$result=ldap_search($this->connectionID, $this->ldapBase, $searchString, array("dn", $this->userNameAttribute));
			$entries=ldap_get_entries($this->connectionID, $result);

			// Keep only the first entry
			$userFullDN=$entries[0]["dn"];
			$userNameToDisplay=$entries[0]["$this->userNameAttribute"][0];

			// If $userNameToDisplay retrievial failed, we won't authenticate, so
			// set it to true to save things.
			if (empty($userNameToDisplay))
			  $userNameToDisplay=true;
			
			// TODO GESTION D'ERREUR !

			// Now we can authenticate : Bind to the ldap server
			$success=ldap_bind($this->connectionID, $userFullDN, $password);
			ldap_close($this->connectionID);
			
			// If bind was successful, then authentification succeeded too, and return
			// the user name to display.
			if ($success==true) {
			  return $userNameToDisplay;
				}
		  else {
        return false;
			}
    }

    /**
    *  This method disconnects the user. As we here use a LDAP server
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
    *  authenticate the user, and return the username if it succeeds, false
    *  otherwise).
    *
    *  @returns The username if the authentification succeeds, false if
    *           it fails or is not applicable.
    */
	
    function autoAuth() {
			return false;
    }
  } // class ldapAuthPlugin
