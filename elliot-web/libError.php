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

  /**
  *  @file libError.php
  *  @version 0-12.02.2006
  *
  *  This library provides objects and functions to handle errors occuring
  *  in the scripts, and display them properly.
  *
	*  @todo Improve the methods so they do better checks for parameters.
  */

  /**
  *  Handles, logs, sorts and display error messages.
  *
  *  To use it, just include this file, and use the static function
  *  ER_Handler::getInstance() to get an handler to the instance of the
  *  class (Do not use new() !). Yes, you can only have one instance of
  *  the class in your whole project, but it eases its use in other
  *  objects.
  */
  class ER_Handler {
    // Private vars
    protected $errorsToDisplay;	  ///< Stores all the error messages.
    protected static $eventCount; /**< Stores the number of events that
    				       where logged */
    protected static $instance;	  /**< Contains the current and unique
   				       instance of this class */

    // Private functions

    /**
    *  This method actually logs an event, and is called by the public
    *  wrappers.
    *
    *  @param $severity The severity of the event : One of "debug",
    *                    "info", "error" or "critical"
    *  @param $source Arbitrary string telling the source of the event,
    *                 usually the function name, or when it happened
    *                 ("Opening file", "user login"...)
    *  @param $message Description of the error, actual error message or
    *                  result of an error reporting function (like
    *                  mysql_error(),...)
    *  @param $hint Hint, advice to give to the user ("Check your password",
    *                  "come again later", "You shouldn't touch a computer")
    *  @returns False if arguments are missing, true otherwise.
    */
    protected function log($severity, $source, $message, $hint="") {
      // Check presence of mandatory args
      if (empty($source) || empty($message) || empty($severity)){
        $tmpmsg="Cannot display error, wrong format.";
        $tmphint="Check how you called this log function !";
        $this->log("critical", "log", $tmpmsg, $tmphint);
        return false;
      }

      // Clean the messages
      $source=htmlentities($source);
      $message=htmlentities($message);
      $hint=htmlentities($hint);

      // Finally, add the error message to the array for later
      // displaying by displayEvents() !
      $this->errorsToDisplay[$severity][]=array($source, $message, $hint);
      $this->eventCount+=1;
      return true;
    }

    /**
    *  Displays an event block
    *
    *  Displays an HTML block for a single category of events. It is
    *  called in the displayEvents() method.
    *
    *  @param $crit Category of events to display ("debug", "info"...)
    *  @param $critTitle Caption in human language describing the
    *                    event type ("Debug messages", "critical errors"...)
    *  @returns Nothing !
    */
    protected function displayEventBlock($crit, $critTitle) {
      // If this category's array is not empty, display its content.
      if (!empty($this->errorsToDisplay[$crit])) {
        echo "  <!-- $critTitle -->\n";
        echo "  <h3>$critTitle</h3>\n";
        echo "  <dl>\n";
        foreach($this->errorsToDisplay[$crit] as $anEvent){
          echo "    <dt>\n";
          echo "      <strong>".$anEvent[0]."</strong>\n";
          echo "      ".$anEvent[1]."\n";
          echo "      <dd>".$anEvent[2]."</dd>\n";
          echo "    </dt>\n";
        }
        echo "  </dl>\n";
      }
    }

    /**
    *  Contructor, do not use it directly !.
    *
    *  This constructor is made protected, so developpers won't call
    *  it directly, and use ER_Handler::getInstance() instead.
    *  It only clears the errorsToDisplay[] events array, by calling
    *  the clearEvents() method.
    */
    protected function __construct() {
      $this->clearEvents();
    }    

    // Public functions
    
    /**
    *  Returns the current and unique instance of this class.
    *
    *  DO use this method instead of the constructor (It will fail,
    *  anyway) to access this class : This way, you do not need to
    *  know the instance's name. Useful for calling it from objects
    *  and to keep the same instance through all the project. If it
    *  the first call, the class is instanciated.
    *
    *  Example :  <code>ER_Handler::getInstance()->logCrit("test", "error !");</code>
    *
    *  @returns The current instance of the ER_Handler class for
    *           direct use.
    */
    public function getInstance() {
      if (ER_Handler::$instance==null) {
        ER_Handler::$instance=new ER_Handler();
      }
      return ER_Handler::$instance;
    }
    
    /**
    *  Logs a critical error message.
    *
    *  Use this when an the error will prevent the task to end normally.
    *
    *  @param $source Arbitrary string telling the source of the event,
    *                 usually the function name, or when it happened
    *                 ("Opening file", "user login"...)
    *  @param $message Description of the error, actual error message or
    *                  result of an error reporting function (like
    *                  mysql_error(),...)
    *  @param $hint Arbitrary string telling the source of the event,
    *               usually the function name, or when it happened
    *               ("Opening file", "user login"...)
    *  @returns False if arguments are missing, true otherwise.
    */
    function logCrit($source, $message, $hint="") {
      return $this->log("critical", $source, $message, $hint);
    }

    /**
    *  Logs a normal error message.
    *
    *  Use this when the error is user-related (Bad password,...).
    *
    *  @param $source Arbitrary string telling the source of the event,
    *                 usually the function name, or when it happened
    *                 ("Opening file", "user login"...)
    *  @param $message Description of the error, actual error message or
    *                  result of an error reporting function (like
    *                  mysql_error(),...)
    *  @param $hint Arbitrary string telling the source of the event,
    *               usually the function name, or when it happened
    *               ("Opening file", "user login"...)
    *  @returns False if arguments are missing, true otherwise.
    */
    function logError($source, $message, $hint="") {
      return $this->log("error", $source, $message, $hint);
    }

    /**
    *  Logs a informative message.
    *
    *  Use this to display info for the user (Success, hint...).
    *
    *  @param $source Arbitrary string telling the source of the event,
    *                 usually the function name, or when it happened
    *                 ("Opening file", "user login"...)
    *  @param $message Description of the error, actual error message or
    *                  result of an error reporting function (like
    *                  mysql_error(),...)
    *  @param $hint Arbitrary string telling the source of the event,
    *               usually the function name, or when it happened
    *               ("Opening file", "user login"...)
    *  @returns False if arguments are missing, true otherwise.
    */
    function logInfo($source, $message, $hint="") {
      return $this->log("info", $source, $message, $hint);
    }

    /**
    *  Logs a debug message.
    *
    *  Use this to display debug info for the developper or administrator
    *  (Variable dump,...).
    *
    *  @param $source Arbitrary string telling the source of the event,
    *                 usually the function name, or when it happened
    *                 ("Opening file", "user login"...)
    *  @param $message Description of the error, actual error message or
    *                  result of an error reporting function (like
    *                  mysql_error(),...)
    *  @param $hint Arbitrary string telling the source of the event,
    *               usually the function name, or when it happened
    *               ("Opening file", "user login"...)
    *  @returns False if arguments are missing, true otherwise.
    */
    function logDebug($source, $message, $hint="") {
      return $this->log("debug", $source, $message, $hint);
    }

    /**
    *  Clears the event log by emptying the errorsToDisplay[] array.
    *
    *  @returns Nothing.
    */
    function clearEvents() {
      // This function inits and clears the events array and count.
      $this->errorsToDisplay=array(
                               "debug" => array(),
                               "info" => array(),
                               "error" => array(),
                               "critical" => array()
                             );
      $this->eventCount=0;
    }

    /**
    *  Finally displays the error log, sorted by criticity.
    *
    *  Use this near the end of your script. You may use it after all
    *  processing, before any differed output.
		*
		*  @returns False if no event has been logged.
    */
    function displayEvents() {
      // If no event was registered, do nothing and return false
      if ($this->eventCount<1)
        return false;

      // Begin the events block
      echo "<!-- Begin events block -->\n";
      echo "<div class=\"events_block\">\n";
      echo "  <h2>Informations</h2>\n";

      $this->displayEventBlock("debug","Debug messages");
      $this->displayEventBlock("info","Informative messages");
      $this->displayEventBlock("error","Errors");
      $this->displayEventBlock("critical","Critical errors");

      // End the events block
      echo "</div>\n";
      echo "<!-- End events block -->\n";
    }
  } // class ER_Handler
