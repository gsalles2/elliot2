<?php
  /** @file plugins/Auth/Disabled/Ldap/config.php
  *  @version 0-23.05.2006
  *
  *  @brief Parameters for the ldap auth plugin
  *
	*  Remember we include this file inside a class, hence the
	*  "$this->" !
  *
  *  @todo DO NOT USE $this IN THIS FILE ! Change the following
  *        for regular variables (Like in the CAS plugin)
  */

  // Ldap server adress : Adjust this to your ldap
  // authentification server's adress
  $this->ldapServer="";

  // REMEMBER THAT LDAP ATTRIBUTES ARE USUALLY lowercase !!!
	
  // Ldap search base DN
  $this->ldapBase="";

  // Which ldap attribute do we use for authetification ?
  $this->ldapSearchAttribute="uid";

  // Which attribute should we use to display as a username ?
  // If unsure, use the same as $this->ldapSearchAttribute. Maybe
  // "CN" is a good choice...
  $this->userNameAttribute="cn";
?>
