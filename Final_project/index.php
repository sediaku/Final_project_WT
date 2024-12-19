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
            background-color: #f9fafc; 
            font-family: 'Arial', sans-serif;
            color: #333; 
        }

       
        header {
            background-color: #343a40; 
            color: #fff;
            padding: 20px 0;
            margin-bottom: 20px;
        }

        header h1 {
            font-size: 2.5rem;
            text-align: center;
            margin: 0;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 10px 0;
            text-align: center;
        }

        nav ul li {
            display: inline-block;
            margin: 0 10px;
        }

        nav ul li a {
            color: #f8f9fa;
            text-decoration: none;
            font-size: 1rem;
            padding: 8px 16px;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }

        nav ul li a:hover {
            background-color: #495057; 
            color: #fff; 
        }

       
        #hero {
            background: linear-gradient(to right, #6c757d, #adb5bd); 
            color: #fff;
            text-align: center;
            padding: 60px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        #hero h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        #hero p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        #hero .btn-primary {
            font-size: 1.2rem;
            padding: 10px 20px;
            border-radius: 5px;
        }

        /* Section styling */
        .section {
            padding: 40px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .about {
            background-color: #e9ecef; 
        }

        .features {
            background-color: #fff;
        }

        .contact-section {
            background-color: #d4edda; 
        }

        
        .features-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .feature {
            background: #f8f9fa;
            border-radius: 5px;
            padding: 20px;
            margin: 10px;
            flex: 0 1 calc(50% - 20px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }

        .feature:hover {
            transform: translateY(-5px); 
        }

       
        footer {
            background-color: #343a40;
            color: #fff;
            text-align: center;
            padding: 15px 0;
            font-size: 0.9rem;
        }

        footer p {
            margin: 0;
        }

        
        @media (max-width: 768px) {
            #hero h2 {
                font-size: 2rem;
            }

            #hero p {
                font-size: 1rem;
            }

            .features-grid {
                flex-direction: column;
            }

            .feature {
                flex: 1 1 100%;
                margin: 10px 0;
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