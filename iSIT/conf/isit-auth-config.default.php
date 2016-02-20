<?php

// rename this file to isit-auth-conifg.php, it will be replaced with upgrade


// isit authentication
$auth_type = "isit";
$auth[] = array("user"=>"admin","passw"=>"isit", "permission"=>Util::iSIT_AUTH_RW);
$auth[] = array("user"=>"guest","passw"=>"guest", "permission"=>Util::iSIT_AUTH_R);

// ldap authentication, filter form "member"

//uncomment line below to allow ldap authentication 
//$auth_type = "ldap"; 
$ldap_srv  = 'my.ldap.server';
$ldap_port = '389';
$ldap_user_dn = "ou=Users,dc=domain,dc=com";
$ldap_group_dn = "cn=name-of-group-for-isit,ou=Groups,dc=domain,dc=com";

?>
