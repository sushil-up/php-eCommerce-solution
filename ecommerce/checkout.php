<?php
require_once "header.php";
require_once "plugins/vendor/autoload.php";
?>
<script src="https://js.stripe.com/v3/"></script>
<?php
// Check if the cart is empty, then redirect to the shop page
$cartpage = $cart->getCart();
if (empty($cartpage)) {
    redirect(BASE_URL . "/shop");
}

// Current user ID
$userID = isUserLoggedIn();

// User address
$addressObj = getUserAddress();
$firstname = isset($addressObj["f_name"]) ? $addressObj["f_name"] : "";
$lastname = isset($addressObj["l_name"]) ? $addressObj["l_name"] : "";
$email = isset($addressObj["u_email"]) ? $addressObj["u_email"] : "";
$phone = isset($addressObj["u_phone"]) ? $addressObj["u_phone"] : "";
$address1 = isset($addressObj["address_1"]) ? $addressObj["address_1"] : "";
$address_2 = isset($addressObj["address_2"]) ? $addressObj["address_2"] : "";
$country = isset($addressObj["country"]) ? $addressObj["country"] : "";
$state = isset($addressObj["state"]) ? $addressObj["state"] : "";
$zip = isset($addressObj["zip"]) ? $addressObj["zip"] : "";

// Initialize coupon details
$coupon_code = isset($_POST['coupon_code']) ? $_POST['coupon_code'] : '';
$coupon_value = 0;
$coupon_discount_type = '';
$coupon_expiry_date = '';
$coupon_usage_limit = 0;
$coupon_used_count = 0;
$is_coupon_valid = false;
$coupon_discount_total = 0;

// Calculate total with shipping
$cartdetail = $cart->getCart();
$total = 0;
$shipping = 40; // Flat shipping rate
foreach ($cartdetail as $cartid) {
    $productid = $cartid["id"];
    $productQuantity = $cartid["quantity"];
    $productprice = $cartid["price"];
    $total += $productprice * $productQuantity;
}
$queryActiveCoupons = "SELECT * FROM coupons WHERE status = 'active'";
$activeCoupons = $db->select($queryActiveCoupons);
// Initialize coupon details
if (!empty($coupon_code)) {
    // Fetch the coupon details
    $query = "SELECT * FROM coupons WHERE code = '$coupon_code' AND status = 'active' LIMIT 1";
    $coupon = $db->select($query);

    if (!empty($coupon)) {
        $coupon = $coupon[0]; // Access the first record
        $coupon_value = $coupon["discount_value"];
        $coupon_discount_type = $coupon["discount_type"];
        $coupon_expiry_date = $coupon["expiry_date"];
        $coupon_usage_limit = $coupon["usage_limit"];
        $coupon_used_count = isset($coupon["used_count"]) ? $coupon["used_count"] : 0;

        // Validate coupon expiry and usage limit
        $current_date = new DateTime();
        $expiry_date = new DateTime($coupon_expiry_date);
        if ($current_date <= $expiry_date) {
            if ($coupon_used_count < $coupon_usage_limit) {
                // Calculate discount based on the type
                if ($coupon_discount_type == "fixed") {
                    $coupon_discount_total = $coupon_value;
                } elseif ($coupon_discount_type == "percentage") {
                    $discount = $total * ($coupon_value / 100);
                    $coupon_discount_total = $discount;
                }

                $is_coupon_valid = true;
            } else {
                echo "<p class='text-danger'>Coupon usage limit exceeded for code: $coupon_code</p>";
            }
        } else {
            echo "<p class='text-danger'>Coupon has expired for code: $coupon_code</p>";
        }
    } else {
        echo "<p class='text-danger'>Invalid coupon code: $coupon_code</p>";
    }
}

// Calculate total with shipping and coupon discount applied
$totalWithShipping = $total + $shipping;
$cartTotal = $totalWithShipping - $coupon_discount_total;

// Handle the payment processing
$paymentstatus = "";
$statuss = "";
$payment_id = "";
if (isset($_POST["paymentMethod"])) {
    $token = $_POST["stripeToken"];
    $createdDate = date("Y-m-d H:i:s");

    if ($_POST["paymentMethod"] == "card") {
        \Stripe\Stripe::setApiKey("{STRIPE KEY}");
        $charge = \Stripe\Charge::create([
            "amount" => $cartTotal * 100,
            "currency" => "usd",
            "description" => "Order Placed",
            "source" => $token,
        ]);

        $chargeID = $charge->id;
        $card = $charge->payment_method_details->card;
        $cardName = $card->brand;
        $cardNumber = $card->last4;
        $cvv = $card->cvc_check;
        $expDate = $card->exp_year;
        $paymentstatus = $charge->outcome->seller_message;
        $statuss = $charge->status;
        $payment_id = $charge->id;
    }

    // Insert order and meta data
    $cartAddress = [
        "f_name" => $_POST["firstName"],
        "l_name" => $_POST["lastName"],
        "u_email" => $_POST["email"],
        "address_1" => $_POST["address1"],
        "address_2" => $_POST["address2"],
        "country" => $_POST["country"],
        "state" => $_POST["state"],
        "zip" => $_POST["zip"]
    ];

    $items = json_encode($cartdetail);
    $address = json_encode($cartAddress);
    $total = floatval($cartTotal);
    $payment_method = $_POST["paymentMethod"];
    $payment_status = $paymentstatus;
    $status = $statuss;
    $created_date = date("Y-m-d H:i:s");

    $query = "INSERT INTO orders (user_id, address, items, total, payment_method, payment_id, payment_status, status, created_date)
              VALUES ('$userID', '$address', '$items', '$total', '$payment_method', '$payment_id', '$payment_status', '$status', '$created_date')";

    $orderID = $db->insert($query);

    if (!empty($orderID)) {
        // Update Stripe charge with the order ID
        if ($_POST["paymentMethod"] == "card") {
            $update = \Stripe\Charge::update($chargeID, ["metadata" => ["order_id" => $orderID]]);
            $sql = "INSERT INTO cards(user_id, order_id, card_name, card_number, card_cvv, card_date, created_date)
                    VALUES ('$userID', '$orderID', '$cardName', '$cardNumber', '$cvv', '$expDate', '$created_date')";
            $db->insert($sql);
        }

        // Update used_count only for the coupon that was used
        if ($is_coupon_valid) {
            $new_used_count = $coupon_used_count + 1;
            $updateQuery = "UPDATE coupons SET used_count = $new_used_count WHERE code = '$coupon_code'";
            if ($db->update($updateQuery)) {
                error_log("Coupon count updated successfully for code: $coupon_code");
            } else {
                error_log("Failed to update coupon count for code: $coupon_code");
            }
        }

        // Insert order meta data
        $metaData = [
            "promocode" => $coupon_code,
            "promocode_amount" => $coupon_discount_total,
            "promocode_type" => $coupon_discount_type,
            "payment_card_details" => json_encode($charge->payment_method_details)
        ];

        foreach ($metaData as $key => $value) {
            $queryOrderMeta = "INSERT INTO order_meta (order_id, meta_key, meta_value) VALUES ($orderID, '$key', '$value')";
            $db->insert($queryOrderMeta);
        }

        // Insert order notes
        $note = "Order placed successfully";
        $sql = "INSERT INTO order_notes (user_id, order_id, note, note_type) VALUES ('$userID', '$orderID', '$note', 'public')";
        $db->insert($sql);
    }

    // Clear the cart and redirect to the thank you page
    $cart->destroy();
    echo "<script>window.location.href = '/thank-you';</script>";
    exit();
}
?>




<div class="container my-5">
    <form method="POST"class="pt-4" action="/checkout" id="payment-form">
        <input type="hidden" name="stripeToken" id="stripe_token" value="">
        <div class="row" id="checkouti">
            <div class="col-md-8 mb-4">
                <div class="card">
                    <div class="card-header py-3">
                        <h5 class="mb-0">Billing Address</h5>
                    </div>
                    <div class="card-body">
                        <div class="needs-validation" novalidate="">
                            <div class="row">
                                <div class="col-md-6 mb-3 pl-5">
                                    <label for="firstName">First name</label>
                                    <input type="text" class="form-control" name="firstName"
                                        id="firstName" placeholder=""
                                        value="<?php echo $firstname; ?>" required="">
                                    <div class="invalid-feedback">
                                        Valid first name is required.
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lastName">Last name</label>
                                    <input type="text" class="form-control" name="lastName"
                                        id="lastName" placeholder=""
                                        value="<?php echo $lastname; ?>" required="">
                                    <div class="invalid-feedback">
                                        Valid last name is required.
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email">Email <span
                                    class="text-muted">(Required)</span></label>
                                <input type="email" class="form-control" id="email" name="email"
                                    placeholder="you@example.com" value="<?php echo $email; ?>"
                                    required>
                                <div class="invalid-feedback">
                                    Please enter a valid email address for shipping updates.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email">Phone <span
                                    class="text-muted">(Required)</span></label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    placeholder="" value="<?php echo $phone; ?>" required>
                                <div class="invalid-feedback">
                                    Please enter a valid phone for shipping updates.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address1"
                                    placeholder="" value="<?php echo $address1; ?>" required="">
                                <div class="invalid-feedback">
                                    Please enter your shipping address.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="address2">Address 2 <span
                                    class="text-muted">(Optional)</span></label>
                                <input type="text" class="form-control" id="address2" name="address2"
                                    placeholder="" value="<?php echo $address_2; ?>">
                            </div>
                            <div class="row">
                                <div class="col-md-5 mb-3">
                                    <label for="country">Country</label>
                                    <input type="text" class="form-control" name="country" id="country"
                                        placeholder="" required="" value="<?php echo $country; ?>">
                                    <div class="invalid-feedback">
                                        Please select a valid country.
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="state">State</label>
                                    <!-- <select class="form-control w-100" id="state" name="state"
                                        required=""> -->
                                    <input type="text" class="form-control" name="state" id="state"
                                        placeholder="" required="" value="<?php echo $state; ?>">
                                    <div class="invalid-feedback">
                                        Please provide a valid state.
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="zip">Zip</label>
                                    <input type="text" class="form-control" name="zip" id="zip"
                                        placeholder="" required="" value="<?php echo $zip; ?>">
                                    <div class="invalid-feedback">
                                        Zip code required.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header py-3">
                        <h5 class="mb-0">Your cart</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                                Products
                                <span>
                                <?php echo displayPrice($total); ?>
                                </span>
                            </li>
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center px-0">
                                Shipping
                                <span>
                                <?php echo displayPrice(
                                    $shipping
                                    ); ?>
                                </span>
                            </li>
                            <!-- Coupon Display Section -->
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0" id="promoInfo" style="display: none;">
                                <div class="text-success">
                                    <h6 class="my-0">Promo code</h6>
                                    <small id="promoCode"></small> <!-- Dynamic promo code display -->
                                </div>
                                <span id="promoValue" class="text-success"></span> <!-- Dynamic promo value display -->
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <strong>Total amount</strong>
                                    <strong>
                                        <p class="mb-0">(including VAT)</p>
                                    </strong>
                                </div>
                                <span id="totalAmount"><strong>
                                <?php echo displayPrice($totalWithShipping); ?>
                                </strong></span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="redeem">
                    <div class="input-group mt-3">
                        <input type="text" class="form-control" id="promocode" placeholder="Enter Promo code">
                        <div class="input-group-append">
                            <button id="redeemBtn" class="btn btn-secondary" type="button">Redeem</button>
                        </div>
                    </div>
                </div>
                <!-- <div class="container mt-5"> -->
                <div class="active-coupons mt-3">
                    <h6 class="mb-3">Active Coupons:</h6>
                    <ul class="list-unstyled">
                        <?php foreach ($activeCoupons as $coupon) { ?>
                        <li class="d-flex justify-content-between align-items-center mb-2 p-2 border  rounded ">
                            <span>
                            <?php echo htmlspecialchars($coupon["code"]); ?> - 
                            <?php echo $coupon["discount_type"] === "percentage"
                                ? $coupon["discount_value"] . "% Off"
                                : '$' . $coupon["discount_value"]. ' Off'; ?>
                            </span>
                            <a href="#"><button class="btn btn-sm btn-secondary" onclick="copyToClipboard(this, '<?php echo htmlspecialchars($coupon["code"]); ?>')">Copy</button></a>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
                <!-- </div> -->
                <div class="payment mt-3">
                    <div class="card">
                        <div class="card-header py-2">
                            <h5 class="mb-0">Payment</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="custom-control custom-radio">
                                    <!-- <input id="card" name="paymentMethod" type="radio" class="custom-control-input" value="card" required="" checked> -->
                                    <input id="cod" name="paymentMethod" type="radio"
                                        class="custom-control-input" value="cod" required=""
                                        checked>
                                    <label class="custom-control-label" for="cod" checked>Cash on
                                    Delivery</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input id="card" name="paymentMethod" type="radio"
                                        class="custom-control-input" value="card" required=""
                                        checked>
                                    <label class="custom-control-label" for="card">Card</label>
                                </div>
                            </div>
                            <div class="row mb-3 card-details">
                                <div class="col-md-12">
                                    <label for="card-element">
                                    Credit or debit card
                                    </label>
                                    <div id="card-element">
                                        <!-- A Stripe Element will be inserted here. -->
                                    </div>
                                    <!-- <input type="hidden" name="stripe_token" id="stripe_token" value=""> -->
                                    <!-- Used to display Element errors. -->
                                    <div id="card-errors" role="alert"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3 ">
                    <div class="col-md-12">
                        <button class="btn btn-primary d-block my-3" id="cc-name" name="place_order">Place
                        Order</button>
                    </div>
                </div>
            </div>
        </div>
</div>
</form>
</div>
<?php require_once "footer.php"; ?>

<script>
      jQuery(document).ready(function () {
            const stripe = Stripe('{STRIPE KEY}');
            const elements = stripe.elements();

            // Custom styling can be passed to options when creating an Element.
            const style = {
                  base: {
                        // Add your base input styles here. For example:
                        fontSize: '12px',
                        color: '#32325d',
                  },
            };

            // Create an instance of the card Element.
            const card = elements.create('card', { style });

            // Add an instance of the card Element into the `card-element` <div>.
            card.mount('#card-element');

            const form = document.getElementById('payment-form');
            form.addEventListener('submit', async (event) => {

                  let paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value

                  if (paymentMethod == 'card') {

                        event.preventDefault();


                        const { token, error } = await stripe.createToken(card);

                        if (error) {
                              // Inform the customer that there was an error.
                              const errorElement = document.getElementById('card-errors');
                              errorElement.textContent = error.message;
                        } else {
                              // Send the token to your server.
                              stripeTokenHandler(token);
                        }
                  }
            });

            const stripeTokenHandler = (token) => {
                  // Insert the token ID into the form so it gets submitted to the server
                  const form = document.getElementById('payment-form');
                  const hiddenInput = document.createElement('input');
                  hiddenInput.setAttribute('type', 'hidden');
                  hiddenInput.setAttribute('name', 'stripeToken');
                  hiddenInput.setAttribute('value', token.id);
                  form.appendChild(hiddenInput);

                  // Submit the form
                  form.submit();
            }
      })

</script>

<script>
jQuery(document).ready(function() {
    // Hide card details section initially
    $(".card-details").hide();

    // Show card details section and "Place Order" button when "Card" payment is selected
    $("#card").change(function() {
        if ($("#card").is(":checked")) {
            $(".card-details").show();
            $("#cc-name").show(); // Show "Place Order" button for Card payment
        }
    });

    // Hide card details section and "Place Order" button when "Cash on Delivery" payment is selected
    $("#cod").change(function() {
        if ($("#cod").is(":checked")) {
            $(".card-details").hide();
            $("#cc-name").hide(); // Hide "Place Order" button for Cash on Delivery
        }
    });

    // Trigger the "Card" payment change event by default
    $("#card").trigger("change");
});
</script>
<script>
    document.getElementById('redeemForm').addEventListener('submit', function(event) {
        // Check if the promo code input is not empty before submitting
        var promoCodeInput = document.getElementById('promocode').value.trim();
        if (!promoCodeInput) {
            event.preventDefault();
            alert("Please enter a promo code.");
        }
    });
</script>
<script>
    // Embedding PHP activeCoupons array into JavaScript
    var activeCoupons = <?php echo json_encode($activeCoupons); ?>;
    var totalWithShipping = <?php echo $totalWithShipping; ?>;

    $(document).ready(function() {
        // Check if a coupon is already applied in localStorage
        if (localStorage.getItem('appliedCoupon')) {
            applySavedCoupon();
        }

        $('#redeemBtn').on('click', function() {
            var promoCodeInput = $('#promocode').val().trim();
            var promoCodeDisplay = $('#promoCode');
            var promoValueDisplay = $('#promoValue');
            var promoInfoSection = $('#promoInfo');
            var totalAmountDisplay = $('#totalAmount'); 

            if (promoCodeInput === "") {
                alert("Please enter a promo code.");
                return;
            }

            var foundCoupon = null;
            $.each(activeCoupons, function(index, coupon) {
                if (coupon.code === promoCodeInput) {
                    var currentDate = new Date();
                    var expiryDate = new Date(coupon.expiry_date);

                    if (currentDate <= expiryDate && coupon.used_count < coupon.usage_limit) {
                        foundCoupon = coupon;
                        return false; 
                    }
                }
            });

            if (foundCoupon) {
                promoCodeDisplay.text(foundCoupon.code);
                promoValueDisplay.text(foundCoupon.discount_type === "percentage" 
                    ? foundCoupon.discount_value + "%" 
                    : "$" + foundCoupon.discount_value);
                promoInfoSection.show(); 

                // Calculate the new total based on the coupon
                var newTotal = calculateNewTotal(foundCoupon);
                totalAmountDisplay.html("<strong>" + displayPrice(newTotal) + "</strong>");

                // Save the applied coupon data to localStorage
                localStorage.setItem('appliedCoupon', JSON.stringify({
                    code: foundCoupon.code,
                    discount_type: foundCoupon.discount_type,
                    discount_value: foundCoupon.discount_value,
                    newTotal: newTotal
                }));
            } else {
                alert("Invalid or expired promo code.");
            }
        });

        // Function to display price in the correct format
        function displayPrice(amount) {
            return "$" + amount.toFixed(2); 
        }

        // Function to calculate new total based on coupon
        function calculateNewTotal(coupon) {
            var newTotal = 0;
            if (coupon.discount_type === "fixed") {
                newTotal = totalWithShipping - coupon.discount_value;
            } else if (coupon.discount_type === "percentage") {
                var discount = totalWithShipping * (coupon.discount_value / 100);
                newTotal = totalWithShipping - discount;
            }

            return newTotal < 0 ? 0 : newTotal;  // Ensure new total is not negative
        }

        // Function to apply coupon saved in localStorage
        function applySavedCoupon() {
            var savedCoupon = JSON.parse(localStorage.getItem('appliedCoupon'));
            if (savedCoupon) {
                var promoCodeDisplay = $('#promoCode');
                var promoValueDisplay = $('#promoValue');
                var promoInfoSection = $('#promoInfo');
                var totalAmountDisplay = $('#totalAmount');

                // Display saved coupon details
                promoCodeDisplay.text(savedCoupon.code);
                promoValueDisplay.text(savedCoupon.discount_type === "percentage" 
                    ? savedCoupon.discount_value + "%" 
                    : "$" + savedCoupon.discount_value);
                promoInfoSection.show(); 

                // Display the new total saved from localStorage
                totalAmountDisplay.html("<strong>" + displayPrice(savedCoupon.newTotal) + "</strong>");
            }
        }
    });
</script>

<script>
function copyToClipboard(button, text) {
    event.preventDefault();
    
    navigator.clipboard.writeText(text).then(function() {
        button.textContent = 'Copied';
        button.classList.add('copied');

        setTimeout(function() {
            button.textContent = 'Copy';
            button.classList.remove('copied');
        }, 2000);
    }).catch(function(err) {
        console.error('Failed to copy text: ', err);
    });
}
</script>
