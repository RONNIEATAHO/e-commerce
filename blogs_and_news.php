<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs and News - Olive Shoppers</title>
    <link rel="stylesheet" href="blogs_and_news.css">
</head>
<body>

<div class="container">

    <!-- Header and Navigation -->
    <header>
        <?php include('header.php'); ?>
    </header>

    <h1>Blogs and News</h1>

    <!-- Blog and News Section -->
    <section class="blog-news">
        <h2>Latest Updates and Tips from Olive Shoppers</h2>

        <?php
        // Define blogs in an array for maintainability
        $blogs = [
            [
                "title" => "5 Tips for Healthy Grocery Shopping",
                "date" => "October 20, 2023",
                "description" => "Discover tips to make healthier choices while grocery shopping, from selecting fresh produce to understanding nutrition labels.",
                "link" => "healthy-grocery-tips.php"
            ],
            [
                "title" => "New Beverages Added to Our Collection!",
                "date" => "October 15, 2023",
                "description" => "We’re excited to introduce a variety of new beverages to our selection. Check out our latest additions and enjoy fresh, flavorful drinks with every order.",
                "link" => "new-beverages.php"
            ],
            [
                "title" => "Essential Personal Care Products You Need",
                "date" => "October 10, 2023",
                "description" => "Maintaining personal care is important for a healthy lifestyle. Explore our top recommendations to keep you feeling fresh and confident every day.",
                "link" => "essential-personal-care.php"
            ]
        ];

        // Function to display blog posts
        function displayBlogs($blogs) {
            foreach ($blogs as $blog) {
                echo '<article class="blog-post">';
                echo "<h3>{$blog['title']}</h3>";
                echo "<p>Posted on {$blog['date']}</p>";
                echo "<p>{$blog['description']} <a href=\"{$blog['link']}\">Read more</a></p>";
                echo '</article>';
            }
        }

        // Render the blogs
        displayBlogs($blogs);
        ?>
    </section>

    <!-- Footer -->
    <footer>
        <a href="privacy-policy.php">Privacy Policy</a>
        <a href="terms-and-conditions.php">Terms and Conditions</a>
        <a href="homepage.php">Home</a>
        <p>&copy; Olive Shoppers 2023</p>
    </footer>

</div>

</body>
</html>
