<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Clinic Management System - Your Health, Our Priority</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }
        
        /* Custom Cursor */
        body {
            cursor: default;
        }
        
        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            padding: 15px 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.3s;
        }
        
        .navbar-scrolled {
            padding: 10px 0;
            background: white;
            box-shadow: 0 5px 30px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .navbar-brand i {
            color: #00b4db;
            margin-right: 8px;
        }
        
        .nav-link {
            font-weight: 500;
            color: #333;
            margin: 0 10px;
            transition: all 0.3s;
            position: relative;
        }
        
        .nav-link:hover {
            color: #00b4db;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
            transition: width 0.3s;
        }
        
        .nav-link:hover::after {
            width: 80%;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
            color: white;
            padding: 8px 25px;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s;
            border: none;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0,180,219,0.4);
            color: white;
        }
        
        .btn-register {
            background: transparent;
            color: #00b4db;
            border: 2px solid #00b4db;
            padding: 8px 25px;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-register:hover {
            background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
            color: white;
            transform: translateY(-2px);
        }
        
        /* Hero Section */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            padding-top: 80px;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.1;
        }
        
        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 20px;
            animation: fadeInUp 0.8s ease;
        }
        
        .hero p {
            font-size: 1.2rem;
            color: rgba(255,255,255,0.95);
            margin-bottom: 30px;
            animation: fadeInUp 1s ease;
        }
        
        .hero-stats {
            display: flex;
            gap: 30px;
            margin-top: 40px;
            animation: fadeInUp 1.2s ease;
        }
        
        .hero-stat {
            text-align: center;
        }
        
        .hero-stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: white;
        }
        
        .hero-stat-label {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.8);
        }
        
        .hero-image {
            animation: float 3s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        
        /* Services Section */
        .services {
            padding: 80px 0;
            background: #f8f9fa;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
        }
        
        .section-title p {
            color: #666;
            font-size: 1.1rem;
        }
        
        .service-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            transition: all 0.4s;
            box-shadow: 0 5px 30px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }
        
        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
            transition: left 0.4s;
            z-index: 0;
        }
        
        .service-card:hover::before {
            left: 0;
        }
        
        .service-card > * {
            position: relative;
            z-index: 1;
            transition: color 0.4s;
        }
        
        .service-card:hover {
            transform: translateY(-10px);
        }
        
        .service-card:hover .service-icon,
        .service-card:hover h4,
        .service-card:hover p {
            color: white;
        }
        
        .service-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        
        .service-icon i {
            font-size: 2rem;
            color: white;
        }
        
        .service-card h4 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
        }
        
        .service-card p {
            color: #666;
            line-height: 1.6;
        }
        
        /* Doctors Section */
        .doctors {
            padding: 80px 0;
            background: white;
        }
        
        .doctor-card {
            text-align: center;
            padding: 30px;
            border-radius: 20px;
            transition: all 0.3s;
            box-shadow: 0 5px 30px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        
        .doctor-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        }
        
        .doctor-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        
        .doctor-image i {
            font-size: 4rem;
            color: white;
        }
        
        .doctor-card h4 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .doctor-card p {
            color: #00b4db;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        
        .doctor-social {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .doctor-social a {
            color: #666;
            transition: color 0.3s;
        }
        
        .doctor-social a:hover {
            color: #00b4db;
        }
        
        /* Appointment CTA */
        .appointment-cta {
            padding: 80px 0;
            background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
            color: white;
            text-align: center;
        }
        
        .appointment-cta h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .btn-appointment {
            background: white;
            color: #00b4db;
            padding: 15px 40px;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-block;
            margin-top: 20px;
        }
        
        .btn-appointment:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            color: #0083b0;
        }
        
        /* Testimonials */
        .testimonials {
            padding: 80px 0;
            background: #f8f9fa;
        }
        
        .testimonial-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.05);
        }
        
        .testimonial-text {
            font-style: italic;
            margin-bottom: 20px;
            color: #555;
            line-height: 1.6;
        }
        
        .testimonial-stars {
            color: #ffc107;
            margin-bottom: 10px;
        }
        
        .testimonial-author {
            font-weight: 600;
            color: #333;
        }
        
        .testimonial-role {
            font-size: 0.8rem;
            color: #00b4db;
        }
        
        /* Footer */
        .footer {
            background: #1a1a2e;
            color: #aaa;
            padding: 60px 0 20px;
        }
        
        .footer h5 {
            color: white;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .footer a {
            color: #aaa;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer a:hover {
            color: #00b4db;
        }
        
        .footer-social a {
            display: inline-block;
            width: 35px;
            height: 35px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            text-align: center;
            line-height: 35px;
            margin-right: 10px;
            transition: all 0.3s;
        }
        
        .footer-social a:hover {
            background: #00b4db;
            color: white;
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }
            .hero p {
                font-size: 1rem;
            }
            .section-title h2 {
                font-size: 1.8rem;
            }
            .appointment-cta h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg" id="navbar">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <i class="fas fa-heartbeat"></i> Clinic CMS
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="#doctors">Doctors</a></li>
                <li class="nav-item"><a class="nav-link" href="#testimonials">Testimonials</a></li>
                <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
            </ul>
            @if (Route::has('login'))
                @auth
                    @php
                        $user = Auth::user();
                    @endphp
                    <a href="@if($user->role == 'admin') {{ route('admin.dashboard') }}
                             @elseif($user->role == 'doctor') {{ route('doctor.dashboard') }}
                             @elseif($user->role == 'receptionist') {{ route('receptionist.dashboard') }}
                             @elseif($user->role == 'patient') {{ route('patient.dashboard') }}
                             @elseif($user->role == 'medical_store') {{ route('medical-store.dashboard') }}
                             @endif" class="btn btn-login">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-login me-2">Login</a>
                    <a href="{{ route('register.patient.form') }}" class="btn btn-register">Register</a>
                @endauth
            @endif
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section id="home" class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1>Your Health, <br>Our Priority</h1>
                <p>Experience quality healthcare with our team of expert doctors. Book appointments online, access medical records, and manage your health from anywhere.</p>
                @guest
                    <a href="{{ route('register.patient.form') }}" class="btn btn-register" style="background: white; color: #00b4db; border: none;">
                        <i class="fas fa-calendar-plus"></i> Book Appointment Now
                    </a>
                {{-- @else
                    <a href="{{ route('patient.appointments.create') }}" class="btn btn-register" style="background: white; color: #00b4db; border: none;">
                        <i class="fas fa-calendar-plus"></i> Book Appointment Now
                    </a> --}}
                @endguest
                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="hero-stat-number">5000+</div>
                        <div class="hero-stat-label">Happy Patients</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-number">50+</div>
                        <div class="hero-stat-label">Expert Doctors</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-number">98%</div>
                        <div class="hero-stat-label">Satisfaction Rate</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image text-center">
                    <i class="fas fa-stethoscope" style="font-size: 300px; color: rgba(255,255,255,0.2);"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="services">
    <div class="container">
        <div class="section-title">
            <h2>Our Services</h2>
            <p>Comprehensive healthcare services for you and your family</p>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-stethoscope"></i>
                    </div>
                    <h4>General Consultation</h4>
                    <p>Expert medical consultation for general health concerns and routine checkups.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h4>Cardiology</h4>
                    <p>Specialized heart care services including diagnosis and treatment.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-brain"></i>
                    </div>
                    <h4>Neurology</h4>
                    <p>Comprehensive neurological care for brain and nervous system disorders.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-baby-carriage"></i>
                    </div>
                    <h4>Pediatrics</h4>
                    <p>Specialized care for infants, children, and adolescents.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-female"></i>
                    </div>
                    <h4>Gynecology</h4>
                    <p>Women's health services including prenatal and postnatal care.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-tooth"></i>
                    </div>
                    <h4>Dentistry</h4>
                    <p>Complete dental care for all ages including orthodontics.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Doctors Section -->
<section id="doctors" class="doctors">
    <div class="container">
        <div class="section-title">
            <h2>Meet Our Expert Doctors</h2>
            <p>Highly qualified medical professionals dedicated to your health</p>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="doctor-card">
                    <div class="doctor-image">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h4>Dr. Maria Santos</h4>
                    <p>Cardiologist</p>
                    <div class="doctor-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="doctor-card">
                    <div class="doctor-image">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h4>Dr. Jose Reyes</h4>
                    <p>Pediatrician</p>
                    <div class="doctor-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="doctor-card">
                    <div class="doctor-image">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h4>Dr. Anna Cruz</h4>
                    <p>Neurologist</p>
                    <div class="doctor-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="doctor-card">
                    <div class="doctor-image">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <h4>Dr. Mark Garcia</h4>
                    <p>Dentist</p>
                    <div class="doctor-social">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Appointment CTA -->
<section class="appointment-cta">
    <div class="container">
        <h2>Book Your Appointment Today!</h2>
        <p>Take the first step towards better health. Schedule a consultation with our expert doctors.</p>
        @guest
            <a href="{{ route('register.patient.form') }}" class="btn-appointment">
                <i class="fas fa-calendar-check"></i> Register & Book Appointment
            </a>
        @else
            <a href="{{ route('patient.appointments.create') }}" class="btn-appointment">
                <i class="fas fa-calendar-check"></i> Book Appointment Now
            </a>
        @endguest
    </div>
</section>

<!-- Testimonials -->
<section id="testimonials" class="testimonials">
    <div class="container">
        <div class="section-title">
            <h2>What Our Patients Say</h2>
            <p>Real stories from real patients</p>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="testimonial-card">
                    <div class="testimonial-stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">"Excellent service! The doctors are very professional and caring. The online appointment system is so convenient."</p>
                    <p class="testimonial-author">- Maria Dela Cruz</p>
                    <p class="testimonial-role">Patient</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="testimonial-card">
                    <div class="testimonial-stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">"The clinic is clean, staff is friendly, and wait times are minimal. Highly recommended!"</p>
                    <p class="testimonial-author">- John Santos</p>
                    <p class="testimonial-role">Patient</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="testimonial-card">
                    <div class="testimonial-stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">"Affordable rates and quality healthcare. The online medical records feature is very helpful."</p>
                    <p class="testimonial-author">- Anna Reyes</p>
                    <p class="testimonial-role">Patient</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer id="contact" class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <h5><i class="fas fa-heartbeat"></i> Clinic CMS</h5>
                <p>Providing quality healthcare services with compassion and excellence.</p>
                <div class="footer-social">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#doctors">Doctors</a></li>
                    <li><a href="#testimonials">Testimonials</a></li>
                </ul>
            </div>
            <div class="col-lg-4 mb-4">
                <h5>Contact Info</h5>
                <ul class="list-unstyled">
                    <li><i class="fas fa-map-marker-alt me-2"></i> 123 Health Street, Manila, Philippines</li>
                    <li><i class="fas fa-phone me-2"></i> +63 912 345 6789</li>
                    <li><i class="fas fa-envelope me-2"></i> info@healthcareplus.com</li>
                </ul>
            </div>
        </div>
        <hr class="mt-4">
        <div class="text-center">
            <p>&copy; 2024 HealthCare Plus. All rights reserved.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        var navbar = document.getElementById('navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    });
</script>
</body>
</html>