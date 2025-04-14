<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Olive Shoppers</title>
    <link rel="stylesheet" href="contact_us.css">
</head>
<body>
    <header>
        <!-- Assuming you will add navigation items later -->
        <nav></nav>
        <?php include('header.php'); ?>
    </header>

    <section class="contact-info">
        <h2>Contact Information</h2>
        <p><strong>Email:</strong> support@oliveshoppers.com</p>
        <p><strong>Phone:</strong> 0785585071</p>
        <p><strong>Address:</strong> P.O.BOX 72 Mbarara</p>
    </section>

    <section class="contact-form">
        <h2>Send Us a Message</h2>
        <form action="/submit-form" method="POST">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="email">Your Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="message">Your Message:</label>
            <textarea id="message" name="message" required></textarea>

            <button type="submit">Submit</button>
        </form>
    </section>

    <br><br>
</body>
</html>
