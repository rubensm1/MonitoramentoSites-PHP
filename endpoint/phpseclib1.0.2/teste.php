<?php 

include "Net/SSH2.php"; 


$ssh = new Net_SSH2('homologacao2');
if (!$ssh->login('coin', 'coinflyboys')) {
	exit('Login Failed');
}

echo $ssh->exec('pwd');
echo $ssh->exec('ls -la');


$ssh->disconnect();




?>