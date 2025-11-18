<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing - Pet Health Tracker</title>
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
                        'pulse-gentle': 'pulseGentle 2s ease-in-out infinite',
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
        @keyframes pulseGentle {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .pricing-card:hover {
            transform: translateY(-8px);
        }
        .popular-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
                <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">Simple, Transparent Pricing</h1>
                <p class="text-xl md:text-2xl text-blue-100 max-w-4xl mx-auto leading-relaxed">
                    Choose the perfect plan for you and your pets. No hidden fees, cancel anytime.
                </p>
            </div>
        </div>
    </section>

    <!-- Pricing Cards -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Basic Plan -->
                <div class="pricing-card bg-white rounded-3xl shadow-xl p-8 transition-all duration-300 hover:shadow-2xl border border-gray-100">
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Basic</h3>
                        <div class="mb-8">
                            <span class="text-5xl font-bold text-gray-800">Free</span>
                            <span class="text-gray-600 text-lg">/month</span>
                        </div>
                        <div class="mb-8">
                            <p class="text-gray-600">Perfect for pet owners just getting started</p>
                        </div>
                        <ul class="text-left space-y-4 mb-8">
                            <li class="flex items-center">
                                <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Up to 2 pets</span>
                            </li>
                            <li class="flex items-center">
                                <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Basic health records</span>
                            </li>
                            <li class="flex items-center">
                                <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Appointment scheduling</span>
                            </li>
                            <li class="flex items-center">
                                <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Email support</span>
                            </li>
                        </ul>
                        <a href="../register.php" class="w-full bg-gray-100 text-gray-700 py-4 rounded-xl hover:bg-gray-200 transition-colors duration-200 font-semibold block text-center">
                            Get Started
                        </a>
                    </div>
                </div>

                <!-- Premium Plan (Most Popular) -->
                <div class="pricing-card bg-white rounded-3xl shadow-2xl p-8 transition-all duration-300 hover:shadow-3xl border-2 border-blue-500 relative transform scale-105">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                        <div class="popular-badge text-white px-6 py-2 rounded-full text-sm font-semibold">
                            Most Popular
                        </div>
                    </div>
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Premium</h3>
                        <div class="mb-8">
                            <span class="text-5xl font-bold text-blue-600">$9.99</span>
                            <span class="text-gray-600 text-lg">/month</span>
                        </div>
                        <div class="mb-8">
                            <p class="text-gray-600">Ideal for active pet families</p>
                        </div>
                        <ul class="text-left space-y-4 mb-8">
                            <li class="flex items-center">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Up to 5 pets</span>
                            </li>
                            <li class="flex items-center">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Advanced health records</span>
                            </li>
                            <li class="flex items-center">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Priority appointment booking</span>
                            </li>
                            <li class="flex items-center">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Vaccination reminders</span>
                            </li>
                            <li class="flex items-center">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Phone & email support</span>
                            </li>
                        </ul>
                        <a href="../register.php" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-4 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200 font-semibold block text-center shadow-lg hover:shadow-xl animate-pulse-gentle">
                            Start Free Trial
                        </a>
                    </div>
                </div>

                <!-- Professional Plan -->
                <div class="pricing-card bg-white rounded-3xl shadow-xl p-8 transition-all duration-300 hover:shadow-2xl border border-gray-100">
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-gray-800 mb-4">Professional</h3>
                        <div class="mb-8">
                            <span class="text-5xl font-bold text-purple-600">$19.99</span>
                            <span class="text-gray-600 text-lg">/month</span>
                        </div>
                        <div class="mb-8">
                            <p class="text-gray-600">For serious pet enthusiasts</p>
                        </div>
                        <ul class="text-left space-y-4 mb-8">
                            <li class="flex items-center">
                                <div class="w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Unlimited pets</span>
                            </li>
                            <li class="flex items-center">
                                <div class="w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Complete health analytics</span>
                            </li>
                            <li class="flex items-center">
                                <div class="w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">24/7 emergency support</span>
                            </li>
                            <li class="flex items-center">
                                <div class="w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Telemedicine consultations</span>
                            </li>
                            <li class="flex items-center">
                                <div class="w-6 h-6 bg-purple-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Dedicated account manager</span>
                            </li>
                        </ul>
                        <a href="../register.php" class="w-full bg-gradient-to-r from-purple-500 to-purple-600 text-white py-4 rounded-xl hover:from-purple-600 hover:to-purple-700 transition-all duration-200 font-semibold block text-center shadow-lg hover:shadow-xl">
                            Contact Sales
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-4xl font-bold text-gray-800 mb-12 text-center">Frequently Asked Questions</h2>
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <h3 class="font-bold text-gray-800 mb-3 text-lg">Can I change my plan anytime?</h3>
                        <p class="text-gray-600">Yes, you can upgrade or downgrade your plan at any time. Changes take effect immediately and billing is prorated accordingly.</p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <h3 class="font-bold text-gray-800 mb-3 text-lg">Is there a free trial?</h3>
                        <p class="text-gray-600">Yes, we offer a 14-day free trial for our Premium and Professional plans with no credit card required.</p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <h3 class="font-bold text-gray-800 mb-3 text-lg">What payment methods do you accept?</h3>
                        <p class="text-gray-600">We accept all major credit cards, PayPal, and bank transfers for annual plans with additional discounts.</p>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <h3 class="font-bold text-gray-800 mb-3 text-lg">Can I cancel anytime?</h3>
                        <p class="text-gray-600">Absolutely! You can cancel your subscription at any time with no cancellation fees or penalties.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 gradient-bg text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">Ready to Get Started?</h2>
            <p class="text-xl text-blue-100 mb-10 max-w-3xl mx-auto">
                Join thousands of satisfied pet owners who trust us with their pets' health and wellbeing.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="../register.php" class="bg-white text-purple-600 px-8 py-4 rounded-xl hover:bg-gray-100 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    Start Your Free Trial
                </a>
                <a href="contact.php" class="border-2 border-white text-white px-8 py-4 rounded-xl hover:bg-white hover:text-purple-600 transition-all duration-300 font-semibold">
                    Contact Sales
                </a>
            </div>
        </div>
    </section>
</main>

<?php include_once '../includes/footer.php'; ?>

</body>
</html>