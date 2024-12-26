<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Razorpay Checkout</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <h2>Order Details</h2>
    <p>Order ID: {{ $order_id }}</p>
    <p>Total: ₹{{ $amount / 100 }}</p>

    <button id="razorpay-button">Pay with Razorpay</button>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        var options = {
            "key": "{{ $key }}",
            "amount": "{{ $amount }}", // Amount in paise
            "currency": "INR",
            "order_id": "{{ $order_id }}",
            "name": "Your Company Name",
            "description": "Payment for order {{ $order_id }}",
            "image": "https://yourdomain.com/logo.png",
            "handler": function (response) {
                console.log(response);
                // Handle the success response here
                var payment_id = response.razorpay_payment_id;
                var order_id = response.razorpay_order_id;
                var signature = response.razorpay_signature;

                // Send the payment details to your backend for verification
                axios.post('/verify-payment', {
                    payment_id: payment_id,
                    order_id: order_id,
                    transaction_type: "{{ $transaction_type }}",
                    amount: "{{ $amount }}", 
                    user: "{{ $user }}", 
                    signature: signature
                }).then(response => {
                    alert('Payment successful!');
                    // window.location.href = '/'; // Or another success page URL
                }).catch(error => {
                    alert('Payment failed!');
                });
            },
            "prefill": {
                "name": "{{ Auth::user()->name }}",
                "email": "{{ Auth::user()->email }}",
                "contact": "9522119545"
            },
            "theme": {
                "color": "#F37254"
            }
        };

        var rzp1 = new Razorpay(options);

        document.getElementById('razorpay-button').onclick = function (e) {
            rzp1.open();
            e.preventDefault();
        }
    </script>
</body>
</html>
