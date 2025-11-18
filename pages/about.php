<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Pet Health Tracker</title>
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
                        'float': 'float 6s ease-in-out infinite',
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
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="font-sans bg-gray-50">

<?php include_once '../includes/header.php'; ?>

<main class="min-h-screen">
    <!-- Hero Section with Gradient Background -->
    <section class="relative gradient-bg text-white py-20 overflow-hidden">
        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center animate-fade-in">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">About Pet Health Tracker</h1>
                <p class="text-xl md:text-2xl text-blue-100 max-w-4xl mx-auto leading-relaxed">
                    Dedicated to keeping your beloved pets healthy and happy through comprehensive health management and expert veterinary care.
                </p>
            </div>
        </div>
        <!-- Floating decorative elements -->
        <div class="absolute top-20 left-10 w-20 h-20 bg-white bg-opacity-10 rounded-full animate-float"></div>
        <div class="absolute bottom-20 right-10 w-32 h-32 bg-white bg-opacity-5 rounded-full animate-float" style="animation-delay: -2s;"></div>
    </section>

    <!-- Our Mission Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="animate-slide-up">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-8 leading-tight">Our Mission</h2>
                    <div class="space-y-6 text-lg text-gray-600 leading-relaxed">
                        <p>
                            At Pet Health Tracker, we believe every pet deserves the best possible care. Our platform connects pet owners with qualified veterinarians, making it easier than ever to monitor your pet's health, schedule appointments, and maintain comprehensive health records.
                        </p>
                        <p>
                            We're committed to using technology to improve the lives of pets and their families, ensuring that no health concern goes unnoticed and every pet receives timely, professional care.
                        </p>
                    </div>
                    <div class="mt-8">
                        <a href="../register.php" class="inline-flex items-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                            Join Us Today
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="glass-effect rounded-3xl p-8 shadow-2xl">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Why Choose Us?</h3>
                    <div class="space-y-4">
                        <div class="flex items-center p-4 bg-white bg-opacity-70 rounded-xl shadow-sm">
                            <div class="w-10 h-10 bg-gradient-to-r from-green-400 to-blue-500 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-800">Qualified Veterinarians</span>
                        </div>
                        <div class="flex items-center p-4 bg-white bg-opacity-70 rounded-xl shadow-sm">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-400 to-pink-500 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-800">24/7 Health Monitoring</span>
                        </div>
                        <div class="flex items-center p-4 bg-white bg-opacity-70 rounded-xl shadow-sm">
                            <div class="w-10 h-10 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-800">Comprehensive Records</span>
                        </div>
                        <div class="flex items-center p-4 bg-white bg-opacity-70 rounded-xl shadow-sm">
                            <div class="w-10 h-10 bg-gradient-to-r from-red-400 to-pink-500 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="font-semibold text-gray-800">Easy Appointment Scheduling</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Team Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">Meet Our Team</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Passionate professionals dedicated to your pet's wellbeing
                </p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl shadow-xl p-8 text-center transform hover:scale-105 transition-all duration-300 hover:shadow-2xl">
                    <div class="w-24 h-24 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full mx-auto mb-6 flex items-center justify-center shadow-lg">
                        <span class="text-white text-2xl font-bold">DV</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Dr. Sarah Johnson</h3>
                    <p class="text-blue-600 font-semibold mb-4">Chief Veterinarian</p>
                    <p class="text-gray-600">15+ years experience in companion animal care and emergency veterinary services.</p>
                </div>
                <div class="bg-white rounded-2xl shadow-xl p-8 text-center transform hover:scale-105 transition-all duration-300 hover:shadow-2xl">
                    <div class="w-24 h-24 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full mx-auto mb-6 flex items-center justify-center shadow-lg">
                        <span class="text-white text-2xl font-bold">MS</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Michael Smith</h3>
                    <p class="text-purple-600 font-semibold mb-4">Platform Director</p>
                    <p class="text-gray-600">Technology expert focused on creating innovative pet health solutions.</p>
                </div>
                <div class="bg-white rounded-2xl shadow-xl p-8 text-center transform hover:scale-105 transition-all duration-300 hover:shadow-2xl">
                    <div class="w-24 h-24 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full mx-auto mb-6 flex items-center justify-center shadow-lg">
                        <span class="text-white text-2xl font-bold">EW</span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Emily Wilson</h3>
                    <p class="text-orange-600 font-semibold mb-4">Customer Care Manager</p>
                    <p class="text-gray-600">Dedicated to providing exceptional support and guidance to pet owners.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="py-20 gradient-bg text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">Ready to Get Started?</h2>
            <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto">
                Join thousands of pet owners who trust us with their pet's health and wellbeing.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="../register.php" class="bg-white text-purple-600 px-8 py-4 rounded-xl hover:bg-gray-100 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    Sign Up Today
                </a>
                <a href="contact.php" class="border-2 border-white text-white px-8 py-4 rounded-xl hover:bg-white hover:text-purple-600 transition-all duration-300 font-semibold">
                    Contact Us
                </a>
            </div>
        </div>
    </section>
</main>

<?php include_once '../includes/footer.php'; ?>

</body>
</html>