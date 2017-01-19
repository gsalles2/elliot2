<?php
  /** @file plugins/Auth/Disabled/Ssl/init.php
  *  @version 0-23.05.2006
  *
  *  @brief SSL auth plugin
  *
  *  This plugin permits SSL certificate-based authentification.
  */

  // WARNING: This name MUST be unique across all plugins !
  $PG_current_class="sslAuthPlugin";

  /* Main class */
  class sslAuthPlugin {
    /* Variables */
    //private $something;          //< some class variables.

    /*
		*  Contructor : Initialise what you want here.
		*
		*/
    function sslAuthPlugin() {
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
			// No form-based auth is available with a certificate !
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
		*	 If it can be done automatically (SSL, CAS, etc...), then try to
		*	 authenticate the user, and return true if it succeeds, false
		*	 otherwise).
		*
		*  @returns the username if the authentification succeeds, false if it fails
		*                or is not applicable.
		*  @todo Error handling !!!
		*  @todo return something better than "Unknown user" !!!
		*/
		function autoAuth() {
			include("config.php");
		
			// $certAttributeToDisplay
			if (isset($_SERVER['SSL_CLIENT_VERIFY']) && ($_SERVER['SSL_CLIENT_VERIFY']=="SUCCESS")) {
				$cert = openssl_x509_parse($_SERVER['SSL_CLIENT_CERT']);
				if ($cert) {
				  $dn_cert=$cert["name"];
					$pattern="/$certAttributeToDisplay=([^\/=]+)/";
					preg_match($pattern, $dn_cert, $matches);
				  // Debug
					/*echo "<p>You are : $matches[1]</p>\n";
					print "certificate: ".$dn_cert."<br />\n";
					echo "<p>pattern=$pattern</p>\n";
					print_r($matches);
				  echo "<pre>\n";
				  var_dump($cert);
				  echo "</pre>\n";
				  */
        }
				
				// Return the name to display !
				return (!empty($matches[1]))?$matches[1]:"Unknown user";
			}
			else {
				echo "<p>No certificate received from the web server</p>\n";
				return false;
			}
	  }
  } // class sslAuthPlugin
