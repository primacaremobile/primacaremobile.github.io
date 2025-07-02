<!-- index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribe</title>
</head>
<body>
    <form id="paymentForm">
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" required>
        </div>
        <div>
            <label for="amount">Amount</label>
            <input type="tel" id="amount" required>
        </div>
        <div>
            <label for="subscription">Subscription Plan</label>
            <select id="subscription" required>
                <option value="basic">Basic</option>
                <option value="premium">Premium</option>
            </select>
        </div>
        <div>
            <button type="submit">Subscribe</button>
        </div>
    </form>

    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script src="paystack.js"></script>
</body>
</html>
