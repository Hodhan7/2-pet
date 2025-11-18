<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Features - Pet Health Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'bounce-gentle': 'bounceGentle 2s ease-in-out infinite',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes bounceGentle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }
    </style>
</head>
<body class="font-sans bg-gray-50">

<?php include_once '../includes/header.php'; ?>

<main class="min-h-screen">
    <!-- Hero Section -->
    <section class="gradient-bg text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <div class="animate-fade-in">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">Powerful Features</h1>
                <p class="text-xl md:text-2xl text-blue-100 max-w-4xl mx-auto leading-relaxed">
                    Discover all the innovative tools designed to keep your pet healthy, happy, and thriving.
                </p>
            </div>
        </div>
    </section>

    <!-- Features Grid -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">Everything You Need</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Comprehensive tools and features designed with your pet's wellbeing in mind
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Health Records -->
                <div class="feature-card bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="feature-icon w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center mb-6 transition-all duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Digital Health Records</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Keep comprehensive digital records of your pet's health history, vaccinations, medications, and treatments all in one secure, easily accessible place.
                    </p>
                </div>

                <!-- Appointment Scheduling -->
                <div class="feature-card bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="feature-icon w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center mb-6 transition-all duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Smart Scheduling</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Book appointments with qualified veterinarians at your convenience. Real-time availability, instant confirmations, and automated reminders.
                    </p>
                </div>

                <!-- Vaccination Tracking -->
                <div class="feature-card bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="feature-icon w-16 h-16 bg-gradient-to-r from-purple-500 to-violet-500 rounded-2xl flex items-center justify-center mb-6 transition-all duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Vaccination Reminders</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Never miss an important vaccination again. Get automatic reminders for upcoming shots, boosters, and preventive treatments.
                    </p>
                </div>

                <!-- Vet Network -->
                <div class="feature-card bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="feature-icon w-16 h-16 bg-gradient-to-r from-red-500 to-pink-500 rounded-2xl flex items-center justify-center mb-6 transition-all duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Trusted Vet Network</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Access to a carefully vetted network of licensed veterinarians who specialize in different areas of companion animal care.
                    </p>
                </div>

                <!-- Mobile Access -->
                <div class="feature-card bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="feature-icon w-16 h-16 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center mb-6 transition-all duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Mobile Responsive</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Access your pet's information anywhere, anytime. Our platform works seamlessly across all devices with offline capabilities.
                    </p>
                </div>

                <!-- Emergency Support -->
                <div class="feature-card bg-white rounded-2xl shadow-xl p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="feature-icon w-16 h-16 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center mb-6 transition-all duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Emergency Support</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Quick access to emergency veterinary services, urgent care information, and 24/7 support when you need it most.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8 text-center">
                <div class="animate-bounce-gentle">
                    <div class="text-4xl md:text-5xl font-bold text-blue-600 mb-2">10,000+</div>
                    <div class="text-gray-600 font-semibold">Happy Pet Owners</div>
                </div>
                <div class="animate-bounce-gentle" style="animation-delay: 0.2s;">
                    <div class="text-4xl md:text-5xl font-bold text-green-600 mb-2">500+</div>
                    <div class="text-gray-600 font-semibold">Qualified Veterinarians</div>
                </div>
                <div class="animate-bounce-gentle" style="animation-delay: 0.4s;">
                    <div class="text-4xl md:text-5xl font-bold text-purple-600 mb-2">50,000+</div>
                    <div class="text-gray-600 font-semibold">Health Records Managed</div>
                </div>
                <div class="animate-bounce-gentle" style="animation-delay: 0.6s;">
                    <div class="text-4xl md:text-5xl font-bold text-red-600 mb-2">24/7</div>
                    <div class="text-gray-600 font-semibold">Emergency Support</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 gradient-bg text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">Ready to Experience These Features?</h2>
            <p class="text-xl text-blue-100 mb-10 max-w-3xl mx-auto">
                Join thousands of pet owners who trust our platform for comprehensive pet health management.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="../register.php" class="bg-white text-purple-600 px-8 py-4 rounded-xl hover:bg-gray-100 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    Get Started Free
                </a>
                <a href="contact.php" class="border-2 border-white text-white px-8 py-4 rounded-xl hover:bg-white hover:text-purple-600 transition-all duration-300 font-semibold">
                    Learn More
                </a>
            </div>
        </div>
    </section>
</main>

<?php include_once '../includes/footer.php'; ?>

</body>
</html>