<?php

require_once('vendor/autoload.php'); // Include the Stripe PHP library

\Stripe\Stripe::setApiKey('sk_test_51NV6JESDz5bQOwuQTU8EDJbb3iljE03ytoZ8uhXTXnRn9wcAXYd65UKgz49xrMhhcTp8UelTtd9q5hU5Y4RuOjHk00XiS15arH');
// Handle the payment form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['stripeToken'];
    $productID = 4;

    try {
        // Create a payment intent using the token
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => 1000,
            'currency' => 'usd',
            'description' => 'Payment description',
            'payment_method_types' => ['card'],
            'metadata' => [
                'product_id' => $productID
            ]
        ]);

        // Payment successful
        echo "Payment successful!";
        $paymentId = $paymentIntent->id;
        echo "<pre>";
        print_r($paymentIntent);
    } catch (\Stripe\Exception\CardException $e) {
        // Card was declined
        $error = $e->getError()->message;
        echo "Payment failed: " . $error;
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Stripe Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>
    <h1>Stripe Payment Demo</h1>

    <form method="POST" action="">
        <script src="https://checkout.stripe.com/checkout.js" class="stripe-button" data-key="pk_test_51NV6JESDz5bQOwuQCoNT2KeMW5vWTov9zX0WJuELdjL8PFkWFUS0rqfzOrtGZ1zfzTUU5Y2NidI9qtT4BmgngXzL00CTvILBu3" data-amount="1000" data-name="Stripe Payment" data-description="Example Charge" data-currency="usd" data-email="customer@example.com" data-locale="auto" data-zip-code="false" data-label="Pay with Card"></script>
    </form>
</body>

</html>

<?php

require_once '../vendor/autoload.php';
require_once '../secrets.php';

\Stripe\Stripe::setApiKey($stripeSecretKey);
header('Content-Type: application/json');

$YOUR_DOMAIN = 'http://localhost:4242';

$checkout_session = \Stripe\Checkout\Session::create([
    'line_items' => [[
        # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
        'price' => '{{PRICE_ID}}',
        'quantity' => 1,
    ]],
    'mode' => 'payment',
    'success_url' => $YOUR_DOMAIN . '/success.html',
    'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
]);

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);
