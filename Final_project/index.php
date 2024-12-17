<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disease Management Portal</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="favicon.ico">
    <style>
        body {
            background-color: #f7f9fc; /* Light background */
            font-family: 'Arial', sans-serif;
        }
        header {
            background-color: #0056b3; /* Darker blue for header */
            color: white;
            padding: 20px 0;
            margin-bottom: 20px;
        }
        nav ul {
            list-style: none;
            padding: 0;
        }
        nav ul li {
            display: inline;
            margin: 0 15px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
            background-color: transparent; /* Ensure background is transparent */
            padding: 10px 15px; /* Add padding for button effect */
            border-radius: 5px; /* Rounded corners */
        }
        nav ul li a:hover {
            background-color: rgba(255, 255, 255, 0.2); /* Light hover effect */
        }
        #hero {
            position: relative;
            background-color: #e3f2fd; /* Light blue background */
            padding: 60px 20px;
            text-align: center;
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin-bottom: 20px;
        }
        #hero::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent overlay */
            border-radius: 8px;
            z-index: 1; /* Place overlay below text */
        }
        #hero h2, #hero p {
            position: relative; /* Position relative for text to be above overlay */
            z-index: 2;
        }
        .btn-primary {
            z-index: 2; /* Ensure button is above the overlay */
        }
        .section {
            padding: 40px 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .about {
            background-color: #e3f2fd; /* Light blue for about section */
        }
        .features {
            background-color: #fff; /* White for features section */
        }
        .contact-section {
            background-color: #e8f5e9; /* Light green for contact section */
        }
        .features-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .feature {
            background: white;
            border-radius: 5px;
            padding: 20px;
            margin: 10px;
            flex: 1 1 45%; /* Responsive two-column layout */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .feature:hover {
            transform: scale(1.05); /* Slightly enlarge on hover */
        }
        footer {
            background-color: #0056b3; /* Darker blue for footer */
            color: white;
            text-align: center;
            padding: 10px 0;
        }
        /* Add responsive typography */
        @media (max-width: 768px) {
            #hero h2 {
                font-size: 2rem;
            }
            #hero p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1 class="text-center">Disease Management Portal</h1>
            <nav>
                <ul class="text-center">
                    <li><a href="#about">About</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <li><a href="./view/Login.php">Login</a></li>
                    <li><a href="./view/SignUp.php">Register</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section id="hero">
            <div class="container">
                <h2>Your Health, Your Control</h2>
                <p>Manage your chronic illness effectively with personalized tools and seamless communication with your doctor.</p>
                <a href="./view/SignUp.php" class="btn btn-primary">Get Started</a>
            </div>
        </section>

        <section id="about" class="section about">
            <div class="container">
                <h2>About Us</h2>
                <div class="row">
                    <div class="col-md-6">
                        <p>We empower individuals with chronic illnesses like asthma, diabetes, and more to take charge of their health through technology.</p>
                    </div>
                    <div class="col-md-6">
                        <p>Our platform enables easy management of medication schedules, tracking vital health data, and staying connected with healthcare providers.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="features" class="section features">
            <div class="container">
                <h2>Features</h2>
                <div class="features-grid">
                    <div class="feature">
                        <h3>Medication Management</h3>
                        <p>Keep track of your medication schedules and receive reminders to take your drugs on time.</p>
                    </div>
                    <div class="feature">
                        <h3>Health Data Sharing</h3>
                        <p>Send vital statistics like blood pressure and glucose levels directly to your doctor.</p>
                    </div>
                    <div class="feature">
                        <h3>Booking Appointments</h3>
                        <p>Book, manage, and view your doctor appointments with ease.</p>
                    </div>
                    <div class="feature">
                        <h3>Lifestyle Tips</h3>
                        <p>Access personalized lifestyle and dietary tips to improve your health and well-being.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="contact" class="section contact-section">
            <div class="container">
                <h2>Contact Us</h2>
                <form action="contact.php" method="POST">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea id="message" name="message" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Disease Management Portal. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Smooth scroll for anchor links
        $('a[href^="#"]').on('click', function(event) {
            var target = $(this.getAttribute('href'));
            if(target.length) {
                event.preventDefault();
                $('html, body').stop().animate({
                    scrollTop: target.offset().top
                }, 1000);
            }
        });
    </script>
</body>
</html>