<?php
  /** @file plugins/Auth/Disabled/Ssl/config.php
  *  @version 0-23.05.2006
  *
  *  @brief Config file for the SSL auth plugin
	*/

  // See comment below
	$certAttributeToDisplay="CN"; ///< In which attribute should we look for the user
                                ///< name to display ?
                                ///< This attribute must be present in the ["name"]
                                ///< line of the certificate, which usually look like this :
                                ///<    /O=Grid/O=Globus/O=CCR Grid Portal/OU=Portal User/CN=Test \
                                ///<    User/emailAddress=test\@nospam.buffalo.edu
                                ///< ...So this variable should be one of "O", "OU", "CN",
                                ///< "emailAddress"... Usually CN is used.
?>
