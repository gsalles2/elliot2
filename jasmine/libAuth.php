<?php
/* Terreau, a set of various PHP libraries.
 Copyright (C) 2005-2006  Nayco.

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

  /** @file libAuth.php 
  *  @version 0-23.05.2006
  *
  *  @brief This library provides objects and functions to handle user
  *  authentification and sessions.
  *
  *  The goal of this library is to make authentification in your
  *  project as simple as writing two lines of code.
  *  More to come...
  */

  // Includes
  include_once("libPlugins.php");
  include_once("libError.php");


  // Start a PHP session
  session_start();

  /**
  *  @brief This class does all the authentfication job.
  *
  *  This class will manage authentification, sessions, disconnection,
  *  auth plugins, etc... You only need to instanciate it, and use its
  *  showBox() method wherever you want the auth form to appear !
  */
  class AU_auth {
    // Private vars
    protected $connected;			///<  Is the user connected ?
    protected $userName;			///<  The login of the connected user
    protected $pluginsObject;	///<  Array pointing to all loaded auth plugins
    protected $eh;						///<  This points to the error handler.
		protected $authMethod;		///<  Plugin that was used to authenticate the user.
    
    // Private functions
    /**
    *  Shows a link so the user can disconnect.
    *  This is to be called in the constructor,
    */
    protected function showLink(){
      return "<a href=\"?AU_action=close\" >Close this session</a>";
    }

    /**
    *  Shows the authentification form.
    *  This is to be called in the constructor.
    */
    protected function showForm() {
      $output="  <form id=\"auth\" method=\"post\" action=\"".$_SERVER['PHP_SELF']."?AU_action=connect\">\n";
      $output.="    <label>\n";
      $output.="      login\n";
      $output.="      <input type=\"text\" name=\"AU_login\" />\n";
      $output.="    </label>\n";
      $output.="    <label>\n";
      $output.="      password\n";
      $output.="      <input type=\"password\" name=\"AU_password\" />\n";
      $output.="    </label>\n";
      $output.="    <input type=\"submit\" value=\"connection\" />\n";
      $output.="  </form>\n";
			$output.="  <form id=\"autoAuth\" method=\"post\" action=\"".$_SERVER['PHP_SELF']."?AU_action=autoConnect\">\n";
			$output.="    <label>\n";
			$output.="      Click here if you want to login using any available automatic method (SSL certificate, CAS...)\n";
			$output.="      <input type=\"submit\" value=\"Automatic connection\" />\n";
			$output.="    </label>\n";
			$output.="  </form>\n";

      return $output;
    }

    /**
    *  Actually does the authentification, calling all the plugins until one
    *  recognizes the user, then store its name to later use it to disconnect
		*  the user.
    *
    *  @param $login The login, should come from the auth form.
    *  @param $password The password, sould too come from the form.
    *  @returns True is the user authenticated sucessfully, false instead.
    */
    protected function validate($login, $password) {
      foreach ($this->pluginsObject->listPlugins() as $authPlugin) {
        $return=$this->pluginsObject->runPluginFunc($authPlugin, "validate", array($login, $password));
        if ($return!=false) {
          // If auth successful, connect the user with the username returned
					// by the plugin :
					$this->connect($return);
					// Keep a track of the plugin that did the auth, and exit the loop
					// successfully.
					$this->authMethod=$authPlugin;
					$_SESSION["AU_auth"]["authMethod"]=$authPlugin;
					return true;
				}
      }
      return false;
    }

		/**
		*  Try to automatically authenticate against all plugin that support
		*  autoAuth (like Cas, Ssl), that is, that can authenticate the user
		*  without providing login/password.
		*
		*  @returns true if the auth succeeded for at least one plugin, false
    *           otherwise.
		*/
		protected function autoAuth() {
      // Call each plugin's autoAuth() method, and if one succeeds, save
      // its name for later disconnection
			foreach ($this->pluginsObject->listPlugins() as $authPlugin) {
        $return=$this->pluginsObject->runPluginFunc($authPlugin, "autoAuth");
        if ($return!=false) {
          // If auth successful, connect the user :
					$this->connect($return);
          // Keep a track of the plugin that did the auth, and exit the loop
					// successfully.
          $this->authMethod=$authPlugin;
			    $_SESSION["AU_auth"]["authMethod"]=$authPlugin;
          return true;
        }
      }
			return false;
		}

    /**
    *  Saves the "connected" state and username in session vars
    *  The user will remain logged in until the disconnect() method is used.
    *
		*  @param $userName The name of the authenticated user to save.
    */
    protected function connect($userName) {
      $this->connected=true;
      $this->userName=$userName;
      $_SESSION["AU_auth"]["userName"]=$userName;
    }
    
    /**
    *  Disconnects the user by killing the relevant session vars
    */
    protected function disconnect() {
			// Tell the plugin that did the auth to disconnect the user !
			$this->pluginsObject->runPluginFunc($this->authMethod, "disconnect");
			
			// Mark the user as disconnected
      $this->connected=false;
      $this->userName="";
      $_SESSION["AU_auth"]=null;
    }
    
    /**
    *  Loads all the auth plugins and stores them into a member array, in order
    *  to call them when needed (Typically in the validate() method)
    *
    *  @param $pluginPath Path to the auth plugins directory. Each one is in a
    *                     subdirectory of its own.
    *  @returns True if succedeed, false instead.
    *  @todo get the return value of the PG_object to return false if the
             instanciation failed !
    */			  
    protected function loadPlugins($pluginPath) {
      if (empty($pluginPath)) {
        // Missing plugins path ! 
        $this->eh->logCrit("AU_Auth::loadPlugins()", "Missing plugins path", "Call this function with the good parameters !");
        return false;
      }
      $this->pluginsObject=new PG_object($pluginPath);
      return true;
    }

    // Public functions

    /**
    *  Returns the user state, connected or not ?
    *  @returns True if the user is connected, false instead.
    */
    function isConnected () {
      return $this->connected;
    }

    /**
    *  Returns the user name.
    *  @returns The user name in a string.
    */
    function getUserName() {
      return $this->userName;
    }

		/**
		*  Returns the method that was used to authenticate the user.
		*
		*  @return the method that was used to authenticate the user.
		*          In fact, this is currently the plugin's directory name.
		*          This may change in the future !
		*/
		function getAuthMethod() {
			return $this->authMethod;
		}

    /** 
    *  Shows the authentification box, use it where you need it in the page.
    *  This should be the only method you need to call to run authentification,
    *  apart from instanciating this class. The output is not echoed, but
    *  returned, in order to ease differed display.
    *
    *  @returns A string containing the form to display, for use with echo().
    */
    function showBox() {
      $output="<!-- Begin auth box -->\n";
      $output.="<div class=\"auth\">\n";
      if ($this->isConnected()) {
        $output.="You are connected as <span class=\"user\">";
        $output.=$this->getUserName();
				$output.="</span> with the <span class=\"authMethod\">";
				$output.=$this->getAuthMethod();
				$output.="</span> method.\n";
        $output.=$this->showLink()."\n";
      }
      else { // Not connected
        $output.=$this->showForm()."\n";
      }
      $output.="</div>\n";
      $output.="<!-- End auth box -->\n";

      return $output;
    }

    // Constructor

    /**
    *  The constructor handles automatically all the autentification and 
    *  session stuff so there is no need to call anything else than
    *  AU_Auth::showBox().
    *
    *  @param $pluginPath Path to the auth plugins directory. Each
    *                     plugin must have a subdirectory of its own.
    *
    *  @todo Modify this class to force the auth plugins to implement a
             plugin template.
    *  @todo Work on the return values !
    */
    
    function __construct($pluginPath) {
      // Setup the error handler
      $this->eh=ER_Handler::getInstance();
      
      // Load the auth plugins
      if (empty($pluginPath)) {
        $this->eh->logCrit("AU_Auth constructor", "Missing plugins path", "Instanciate this object with the good parameters !");
        return false;
      }
      $this->loadPlugins($pluginPath);
      // Nasty debugging
      // print_r($this->pluginsObject);
      // echo "<br />\n";

      // Set the auth variables from session
      if (empty($_SESSION["AU_auth"])) {
        // If not connected, set default values
			  $this->connected=false;
				$this->userName="Unknown user";
				$this->authMethod="Unknown auth method";
      }
      else {
        // Else, we are connected and retrieve values from session
        $this->connected=true;
        $this->userName=$_SESSION["AU_auth"]["userName"];
				$this->authMethod=$_SESSION["AU_auth"]["authMethod"];							
      }

      // Now, process actions
      if (!empty($_GET["AU_action"])) {
        switch ($_GET["AU_action"]) {
          case "close":
						$this->disconnect(); break;
          case "connect":
						// If we are already connected, forget it
						if ($this->isConnected()) {break;}
						// Else try to authenticate !
						if ($_POST["AU_login"] && $_POST["AU_password"]) {
              // Verify password
              if ($this->validate($_POST["AU_login"], $_POST["AU_password"])) {
              	$this->eh->logInfo("Authentification", "You are now connected !", "Click on the 'Close this session' link to disconnect.");
            	}
            	else {
              	// Wrong password
              	$this->eh->logError("Authentification", "Wrong login and/or password !", "Check your login parameters and try again.");
            	}
          	}
          	else {
            	// Missing password and/or login (Form hacking ?)
            	$this->eh->logError("Authentification", "Wrong login and/or password !", "Check your login parameters and try again.");
          	}
						break;
					case "autoConnect":
						// If some plugins provide automatic authentification (Cas, Ssl,...),
						// poll them now to try to get authenticated automagically
						if( ! $this->isConnected() && $this->autoAuth()) {
      				$this->eh->logInfo("Authentification", "You are now connected !", "Click on the 'Close this session' link to disconnect.");
						}						
						break;
        } // End of switch()
      } // End of actions
    } // End of constructor
  } // class AU_auth
?>
