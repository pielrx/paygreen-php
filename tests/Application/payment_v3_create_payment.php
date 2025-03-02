<?php
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Dotenv\Exception\PathException;

require dirname(dirname(__DIR__)) . '/vendor/autoload.php';

try {
    (new Dotenv())->load(dirname(dirname(__DIR__)) . '/.env.behat');
} catch (PathException $exception) {
    print "The .env.behat file does not exist. This is probably unintentional.\n";
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <?php if (getenv('ENVIRONMENT') === 'RECETTE'): ?>
        <script defer type="text/javascript" src="https://rc-pgjs.paygreen.dev/latest/paygreen.min.js"></script>
    <link href="https://rc-pgjs.paygreen.dev/latest/paygreen.min.css" type="text/css" rel="stylesheet" />
    <?php else: ?>
        <script defer type="text/javascript" src="https://sb-pgjs.paygreen.fr/latest/paygreen.min.js"></script>
    <link href="https://sb-pgjs.paygreen.fr/latest/paygreen.min.css" type="text/css" rel="stylesheet" />
    <?php endif; ?>
</head>
<body>

<div id="paygreen-container"></div>
<div id="paygreen-methods-container"></div>
<div id="yourCustomDiv">
    <div id="paygreen-pan-frame"></div>
    <div id="paygreen-cvv-frame"></div>
    <div id="paygreen-exp-frame"></div>
    <div id="paygreen-reuse-checkbox-container"></div>
</div>

<script type="application/javascript">
    window.addEventListener("load", function () {
        const urlParams = new URLSearchParams(window.location.search);
        const publicKey = urlParams.get('publicKey');
        const paymentOrderId = urlParams.get('paymentOrderId');
        const objectSecret = urlParams.get('objectSecret');
        const instrumentId = urlParams.get('instrumentId');

        window.paymentDone = false;

        paygreenjs.attachEventListener(paygreenjs.Events.FULL_PAYMENT_DONE, () => {
            window.paymentDone = true;
        });

        paygreenjs.attachEventListener(
            paygreenjs.Events.ERROR,
            (event) => console.log(event.detail),
        );

        console.log({
            publicKey: publicKey,
            objectSecret: objectSecret,
            paymentOrderID: paymentOrderId,
            instrument: instrumentId
        });

        paygreenjs.init({
            publicKey: publicKey,
            objectSecret: objectSecret,
            paymentOrderID: paymentOrderId,
            instrument: instrumentId,
            mode: 'payment',
            paymentMethod: 'bank_card',
        });
    });
</script>
</body>
</html>