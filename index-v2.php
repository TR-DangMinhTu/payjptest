<?php
require_once 'vendor/autoload.php';
require_once 'vendor/payjp/payjp-php/init.php';

use Payjp\Payjp;
use Payjp\Charge;
use Payjp\Customer;

// echo implode(', ', $_POST);
$payjp = \Payjp\Payjp::setApiKey("sk_test_03599cbf212b2b23d85589b2");

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
        "capture" => "true",
        "three_d_secure" => "true"
    ));

    echo $charge;

    clearstatcache();
  }else{
    echo "payjp-token: NOT FOUND";
  } 
}


?>

<?php include_once("header.php") ?>

<script src="https://js.pay.jp/v2/pay.js"></script>
<style>
  /* 必要に応じてフォームの外側のデザインを用意します */
  div.payjs-outer {
    border: thin solid #198fcc;
    width: 30vw;
  }
</style>
<div id="v2-demo" class="payjs-outer"><!-- ここにフォームが生成されます --></div>
<button onclick="onSubmit(event)">トークン作成</button>
<span id="token"></span>
<form id="form_token" method="POST" style="margin-top: 10vw;">
  <h2>Form Payment</h2>
  <input type="text" id="payjp-token" name="payjp-token" placeholder="token will be shown here" required/>
  <input type="text" id="amount" name="amount" placeholder="Please input price" required/>
  <button type="submit">Charge</button>
</form>

<script>
  // 公開鍵を登録し、起点となるオブジェクトを取得します
  var payjp = Payjp('pk_test_55f09beb8d5f5460f85e7dd6');

  // elementsを取得します。ページ内に複数フォーム用意する場合は複数取得ください
  var elements = payjp.elements();

  // element(入力フォームの単位)を生成します
  var cardElement = elements.create('card');

  // elementをDOM上に配置します
  cardElement.mount('#v2-demo');

  // ボタンが押されたらtokenを生成する関数を用意します
  function onSubmit(event) {
    payjp.createToken(cardElement).then(function(r) {
      document.querySelector('#token').innerText = r.error ? r.error.message : r.id;

      var inputElement = document.getElementById('payjp-token');
      // Set the value of the input
      inputElement.value = r.error ? r.error.message : r.id;
    })
  }
</script>

<?php include_once("footer.php") ?>