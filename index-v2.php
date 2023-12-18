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

    echo $charge->id;

    // echo "
    // <script src='https://js.pay.jp/v2/pay.js'></script>
    // <script>
    // const payjp1 = Payjp('pk_test_f77e00f550ca19609765ece3');
    // payjp1.openThreeDSecureDialog('$charge->id').then(() => {
    //         // 3D Secure đã kết thúc, thông báo cho server và tiếp tục xử lý thanh toán

    
    //       });
    // </script>";

    $ch = \Payjp\Charge::retrieve("$charge->id");
    $ch->tdsFinish(); 

    clearstatcache();
  }else{
    echo "payjp-token: NOT FOUND";
  } 
}

// const payjp = Payjp('pk_test_0383a1b8f91e8a6e3ea0e2a9')

// payjp.openThreeDSecureDialog('サーバーサイドから渡された支払いID').then(() => {
//     // 3Dセキュア処理が終了したことをサーバーサイドに通知する
// })

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
  <p id="payjp-mes"></p>
  <input type="text" id="payjp-token" name="payjp-token" placeholder="token will be shown here" required/>
  <input type="text" id="amount" name="amount" placeholder="Please input price" required/>
  <button type="submit">Charge</button>
</form>

<script>
  // 公開鍵を登録し、起点となるオブジェクトを取得します
  var payjp = Payjp('pk_test_f77e00f550ca19609765ece3');

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

      const payjp1 = new Payjp('pk_test_f77e00f550ca19609765ece3');
      payjp1.charges.create({
        amount: 5000,
        currency: 'jpy',
        card: r.id
      }).then(function(r) {
        console.log(r);
        
      }).catch(console.error);
    })

    // payjp.openThreeDSecureDialog(r.error ? r.error.message : r.id).then(() => {
    //   // 3Dセキュア処理が終了したことをサーバーサイドに通知する
    // })

    // var charge = payjp.charges.create({
    //   card: r.id,
    //   amount: 3500,
    //   currency: 'jpy',
    //   capture: "true",
    //   three_d_secure: "true"
    // });
    // var payjpMes = document.getElementById('payjp-mes');
    // payjpMes.value = charge;
    

    // payjp.openThreeDSecureDialog(charge.id).then(() => {
    //   // 3Dセキュア処理が終了したことをサーバーサイドに通知する
    // })
  }
</script>

<?php include_once("footer.php") ?>

<br>
<br>
<br>
<div id="number-form" class="payjs-outer"><!-- ここにカード番号入力フォームが生成されます --></div>
<div id="expiry-form" class="payjs-outer"><!-- ここに有効期限入力フォームが生成されます --></div>
<div id="cvc-form" class="payjs-outer"><!-- ここにCVC入力フォームが生成されます --></div>
<button onclick="onSubmit2(event)">トークン作成</button>
<span id="token2"></span>
<script>
var elements4 = payjp.elements()

// 入力フォームを分解して管理・配置できます
var numberElement = elements4.create('cardNumber')
var expiryElement = elements4.create('cardExpiry')
var cvcElement = elements4.create('cardCvc')
numberElement.mount('#number-form')
expiryElement.mount('#expiry-form')
cvcElement.mount('#cvc-form')

// createTokenの引数には任意のElement1つを渡します
function onSubmit2(event) {
  payjp.createToken(numberElement).then(function(r) {
    document.querySelector('#token2').innerText = r.error ? r.error.message : r.id
  })
}
</script>