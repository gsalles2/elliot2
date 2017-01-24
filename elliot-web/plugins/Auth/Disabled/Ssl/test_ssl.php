<html>
<body>
<?php
$cert = openssl_x509_parse($_SERVER['SSL_CLIENT_CERT']);
if ($cert) {
        $dn_cert=$cert["name"];
        print "certificat : ".$dn_cert."<br />\n";
        $cert_elements = explode("/",$dn_cert);
        foreach ($cert_elements as $elem) {
                if (substr($elem,0,3) == "CN=") {
                        $uid = substr($elem,3);
                        break;
                }
        }
}
// Debug
echo "<pre>\n";
var_dump($cert);
echo "</pre>\n";

if ($uid) {
        print "uid=".$uid."<br />\n";
} else {
        print "authentification par certificat impossible. <br />\n";
}
?>
</body>
</html>
