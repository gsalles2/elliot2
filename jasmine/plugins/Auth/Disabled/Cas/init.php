<?php
  /** @file plugins/Auth/Disabled/Cas/init.php
  *  @version 0-23.05.2006
  *
  *  @brief CAS auth plugin
	*
  *  This plugin permits CAS-based authentification, using PHP-CAS
	*  (http://esup-phpcas.sourceforge.net/)
  */

  // WARNING: This name MUST be unique across all plugins !
  $PG_current_class="CasAuthPlugin";

  /* Main class */
  class CasAuthPlugin {
    /* Variables */
		private $casServerHostname;		///< The CAS server hostname
		private $casServerPort;				///< The CAS server port
		private	$casServerURI;				///< The CAS server URI

    /**
		*  Contructor : Initialise what you want here.
		*
		*/
    function CasAuthPlugin() {
      // Include the config file, that sets the following variables
			include_once("config.php");
			
			// Use it to set the object properties
			$this->casServerHostname=$casServerHostname ;
			$this->casServerPort=$casServerPort ;
			$this->casServerURI=$casServerURI ;
    }

		/**
		*  This function always returns false, as CAS only supports automatic
		*  authentification.
		*
		*  @param login the login provided by the user
		*  @param password the password provided by the user
		*  @returns Always false for this plugin !
		*/
    function validate($login, $password) {
			return false;
    }

	  /**
    *  This method disconnects the user.
		*
		*  Notice that the CAS server actually needs to be notified of the
		*  disconnection, so we use phpCAS's relevant function.
    *
    *  @returns Nothing ! This method should always redirect to the main
		*           page.
    */
    function disconnect(){
			// Load the CAS module
			include_once('CAS/CAS.php');

			// Start CAS, and use it to disconnect
			phpCAS::client(CAS_VERSION_2_0, $this->casServerHostname, $this->casServerPort, $this->casServerURI);
      phpCAS::logout("http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"]);
			
			// Really ? Well, should not be ever executed, because phpCAS::logout()
			// redirects to another page, or exit()s the php script if failure.
      return true;
    }	

		/**
		*  This function returns false if the used auth method cannot be
		*  done without user action (ie needs filling login/password in the
		*  form...). If it can be done automatically (SSL, CAS, etc...), then
		*  try to authenticate the user, and return the username if it succeeds,
		*  false otherwise).
		*
		*  @returns The username if the authentification succeeds, false if
		*           it fails or is not applicable.
		*/
		function autoAuth() {
			// Instanciate phpCAS
			include_once('CAS/CAS.php');
			phpCAS::client(CAS_VERSION_2_0, $this->casServerHostname, $this->casServerPort, $this->casServerURI);
			
			// Do CAS authentication (force it)
			phpCAS::forceAuthentication();
			
			// If the CAS authentification was successful, phpCAS will get us back
			// here and return the user login. Just what we needed !
			return phpCAS::getUser();
		}
  } // class CasAuthPlugin
?>
