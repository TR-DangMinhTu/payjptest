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
    $ch_id =  $ch->id;
    
    echo "<br/>";
    echo "<br/>";
    echo $ch->id;

  echo'<script src="https://js.pay.jp/v2/pay.js"></script>
   <script>
    var payjp = Payjp("pk_test_f77e00f550ca19609765ece3");
    var charge_id = ' . json_encode($ch_id) . ';
    //payjp.openThreeDSecureDialog(charge_id).then(() => {
      // 3Dセキュア処理が終了したことをサーバーサイドに通知する
    //})
    async function open3DSecureDialog() {
      try {
        await payjp.openThreeDSecureDialog(charge_id);
        // 3Dセキュア処理が終了したことをサーバーサイドに通知する

        fetch("http://localhost/testpayjp/confirm3DSecureResult.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            result: "success",
            error_message: null,
            charge_id: charge_id,
          }),
        });
      } catch (error) {
        console.error("Error in 3D Secure:", error);
        
        fetch("http://localhost/testpayjp/confirm3DSecureResult.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            result: "error",
            error_message: error.message,
            charge_id: charge_id,
          }),
        });
      }
    }

    open3DSecureDialog();

   </script>';

    clearstatcache();
  }else{
    echo "payjp-token: NOT FOUND";
  } 
}

?>

<?php include_once("header.php") ?>

<!-- <script src="https://js.pay.jp/v2/pay.js"></script> -->

<script type="text/javascript">
function onCreated(response) {
    document.querySelector('#token').innerHTML = response.id;

    var inputElement = document.getElementById('payjp-token');
      // Set the value of the input
    inputElement.value = response.id;

    return response.id
}

function onProcessing(response) {
    var payJPToken = onCreated(response);
        // var payjp = Payjp('pk_test_f77e00f550ca19609765ece3');
    // payjp.openThreeDSecureDialog(payJPToken).then(() => {
    //   // 3Dセキュア処理が終了したことをサーバーサイドに通知する
    // });

//     payjp.openThreeDSecureDialog('ch_1122334455').then(() => {
//   // 3Dセキュアフロー終了をサーバーに通知
// }).catch(() => {
//   console.log('timeout')
// })
    
}


</script>

<script
type="text/javascript"
src="https://checkout.pay.jp/"
class="payjp-button"
data-key="pk_test_f77e00f550ca19609765ece3"
data-on-created="onProcessing"
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