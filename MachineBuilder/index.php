
<!DOCTYPE HTML>  
<html>
<head>
</head>
<body>  

<?php
echo (defined('__DIR__') ? '__DIR__ is defined' : '__DIR__ is NOT defined' . PHP_EOL);
echo (defined('__FILE__') ? '__FILE__ is defined' : '__FILE__ is NOT defined' . PHP_EOL);
echo (defined('PHP_VERSION') ? 'PHP_VERSION is defined' : 'PHP_VERSION is NOT defined') . PHP_EOL;
echo 'PHP Version: ' . PHP_VERSION . PHP_EOL;

// define variables and set to empty values
$username = $password = $action = $endpoint = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = test_input($_POST["username"]);
  $password = test_input($_POST["password"]);
  $endpoint = test_input($_POST["endpoint"]);
  $action = test_input($_POST["action"]);
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<h2>Endopoint Rebuild</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  Username: <input type="text" name="username">
  <br><br>
  Pasword: <input type="text" name="password">
  <br><br>
  Endpoint: <input type="text" name="endpoint">
  <br><br>
  Operation to Perform:
  <input type="radio" name="action" value="revert">Revert Snapshot
  <input type="radio" name="action" value="poweron">Power On
  <input type="radio" name="action" value="poweroff">Power Off
  <br><br>
  <input type="submit" name="submit" value="Submit">  
</form>

<?php
if ($username != "") {

echo "<h2>Your Input:</h2>";
echo "Username: " . $username;
echo "<br>";
echo "Endpoint: " . $endpoint;
echo "<br>";
echo "We will: " . $action;

if ($action = 'revert' ) {
$pyscript = dirname(__FILE__) . "/support/snapshot_operations.py";
$arguments = '-s esdesx02.ustx.ibm.com -u ' . $username . ' -p ' . $password . ' -nossl  -v ' . $endpoint . ' -op ' . $action;
}

if ($action = 'poweron' ) {
$pyscript = dirname(__FILE__) . "/support/vm_power_on.py";
$arguments = '-s esdesx02.ustx.ibm.com -u ' . $username . ' -p ' . $password . ' -nossl  -v ' . $endpoint;
}


if ($action = 'poweroff' ) {
  $pyscript = dirname(__FILE__) . "/support/vm_power_off.py";
  $arguments = '-s esdesx02.ustx.ibm.com -u ' . $username . ' -p ' . $password . ' -nossl  -v ' . $endpoint;
  }


echo $pyscript;
echo "<br>";
echo $pyscript . " " . $arguments;

$comando = shell_exec('/usr/bin/python3 ' . $pyscript . ' ' . $arguments . " 2>&1");
echo "<pre>$comando</pre>";

print_r($output);



}?>

</body>
</html>
