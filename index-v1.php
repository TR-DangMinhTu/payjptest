<?php
require_once 'vendor/autoload.php';
require_once 'vendor/payjp/payjp-php/init.php';

use Payjp\Payjp;
use Payjp\Charge;
use Payjp\Customer;

// echo implode(', ', $_POST);
\Payjp\Payjp::setApiKey("sk_test_03599cbf212b2b23d85589b2");
$payjpToken = "";
if ($_POST){
  if (isset($_POST['payjp-token']) && isset($_POST['amount'])) {
    $payjpToken = $_POST['payjp-token'];
    $amount = $_POST['amount'];
    echo "payjp-token: " . htmlspecialchars($payjpToken);
    $charge = \Payjp\Charge::create(array(
        "card" => $payjpToken,
        "amount" => $amount,
        "currency" => "jpy",
        "capture" => true,
        "three_d_secure" => true 
    ));
    echo $charge->id;
    $ch = \Payjp\Charge::retrieve($charge->id);
    
    echo "<br/>";
    echo $ch;

    clearstatcache();
  }else{
    echo "payjp-token: NOT FOUND";
  } 
}

?>

<?php include_once("header.php") ?>

<script type="text/javascript">
function onCreated(response) {
    document.querySelector('#token').innerHTML = response.id;

    var inputElement = document.getElementById('payjp-token');
      // Set the value of the input
    inputElement.value = response.id;
}
</script>

<script
type="text/javascript"
src="https://checkout.pay.jp/"
class="payjp-button"
data-key="pk_test_55f09beb8d5f5460f85e7dd6"
data-on-created="onCreated"
data-submit-text="トークンを作成する"
data-partial="true">
</script>

作成されたトークン: <span id="token"></span>

<form id="form_token" method="POST" style="margin-top: 10vw;">
  <h2>Form Payment</h2>
  <input type="text" id="payjp-token" name="payjp-token" placeholder="token will be shown here" required/>
  <input type="text" id="amount" name="amount" placeholder="Please input price" required/>
  <button type="submit">Charge</button>
</form>

<?php include_once("footer.php") ?>