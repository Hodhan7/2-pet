<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Pet Health Tracker</title>
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
            50% { transform: translateY(-10px); }
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .faq-item {
            transition: all 0.3s ease;
        }
        .faq-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        .accordion-button[aria-expanded="true"] .rotate-icon {
            transform: rotate(180deg);
        }
        .rotate-icon {
            transition: transform 0.3s ease;
        }
        .category-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.95) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
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
                <div class="mb-6">
                    <span class="inline-block animate-bounce-gentle text-6xl">❓</span>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">Frequently Asked Questions</h1>
                <p class="text-xl md:text-2xl text-blue-100 max-w-4xl mx-auto leading-relaxed">
                    Find answers to common questions about Pet Health Tracker and pet care management.
                </p>
            </div>
        </div>
    </section>

    <!-- Quick Links -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Browse by Category</h2>
                <p class="text-gray-600 text-lg">Jump to the section that interests you most</p>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="#getting-started" class="category-card rounded-2xl p-6 text-center hover:scale-105 transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg mb-2">Getting Started</h3>
                    <p class="text-gray-600 text-sm">Setup and basic usage</p>
                </a>

                <a href="#account-billing" class="category-card rounded-2xl p-6 text-center hover:scale-105 transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg mb-2">Account & Billing</h3>
                    <p class="text-gray-600 text-sm">Subscription and payments</p>
                </a>

                <a href="#features" class="category-card rounded-2xl p-6 text-center hover:scale-105 transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg mb-2">Features</h3>
                    <p class="text-gray-600 text-sm">Platform capabilities</p>
                </a>

                <a href="#support" class="category-card rounded-2xl p-6 text-center hover:scale-105 transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-r from-orange-500 to-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg mb-2">Support</h3>
                    <p class="text-gray-600 text-sm">Help and troubleshooting</p>
                </a>
            </div>
        </div>
    </section>

    <!-- FAQ Sections -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4 max-w-4xl">
            
            <!-- Getting Started -->
            <div id="getting-started" class="mb-16">
                <h2 class="text-3xl font-bold text-gray-800 mb-8 flex items-center">
                    <span class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </span>
                    Getting Started
                </h2>
                
                <div class="space-y-4">
                    <div class="faq-item bg-white rounded-2xl shadow-lg overflow-hidden">
                        <button class="accordion-button w-full px-8 py-6 text-left flex items-center justify-between focus:outline-none focus:bg-gray-50" 
                                onclick="toggleAccordion(this)">
                            <h3 class="text-lg font-semibold text-gray-800">How do I create an account?</h3>
                            <svg class="rotate-icon w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="accordion-content hidden px-8 pb-6">
                            <p class="text-gray-600 leading-relaxed">
                                Creating an account is simple! Click the "Register" button on the homepage, choose your account type (Pet Owner or Veterinarian), fill in your details, and verify your email address. You'll be ready to start tracking your pet's health in minutes.
                            </p>
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-2xl shadow-lg overflow-hidden">
                        <button class="accordion-button w-full px-8 py-6 text-left flex items-center justify-between focus:outline-none focus:bg-gray-50" 
                                onclick="toggleAccordion(this)">
                            <h3 class="text-lg font-semibold text-gray-800">What information do I need to add my pet?</h3>
                            <svg class="rotate-icon w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="accordion-content hidden px-8 pb-6">
                            <p class="text-gray-600 leading-relaxed">
                                To add your pet, you'll need basic information like their name, species, breed, age, weight, and any existing medical conditions. You can also upload a photo to make their profile more personal. Don't worry if you don't have all the details - you can always update the information later.
                            </p>
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-2xl shadow-lg overflow-hidden">
                        <button class="accordion-button w-full px-8 py-6 text-left flex items-center justify-between focus:outline-none focus:bg-gray-50" 
                                onclick="toggleAccordion(this)">
                            <h3 class="text-lg font-semibold text-gray-800">Is there a mobile app available?</h3>
                            <svg class="rotate-icon w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="accordion-content hidden px-8 pb-6">
                            <p class="text-gray-600 leading-relaxed">
                                Our web platform is fully responsive and works great on mobile devices through your browser. We're currently developing dedicated iOS and Android apps, which will be available soon with additional features like push notifications and offline access.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account & Billing -->
            <div id="account-billing" class="mb-16">
                <h2 class="text-3xl font-bold text-gray-800 mb-8 flex items-center">
                    <span class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </span>
                    Account & Billing
                </h2>
                
                <div class="space-y-4">
                    <div class="faq-item bg-white rounded-2xl shadow-lg overflow-hidden">
                        <button class="accordion-button w-full px-8 py-6 text-left flex items-center justify-between focus:outline-none focus:bg-gray-50" 
                                onclick="toggleAccordion(this)">
                            <h3 class="text-lg font-semibold text-gray-800">What subscription plans are available?</h3>
                            <svg class="rotate-icon w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="accordion-content hidden px-8 pb-6">
                            <p class="text-gray-600 leading-relaxed">
                                We offer three plans: Basic (free for 1 pet with essential features), Premium ($9.99/month for up to 5 pets with advanced health tracking), and Professional ($19.99/month for unlimited pets with veterinarian consultations and priority support).
                            </p>
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-2xl shadow-lg overflow-hidden">
                        <button class="accordion-button w-full px-8 py-6 text-left flex items-center justify-between focus:outline-none focus:bg-gray-50" 
                                onclick="toggleAccordion(this)">
                            <h3 class="text-lg font-semibold text-gray-800">Can I cancel my subscription anytime?</h3>
                            <svg class="rotate-icon w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="accordion-content hidden px-8 pb-6">
                            <p class="text-gray-600 leading-relaxed">
                                Yes! You can cancel your subscription at any time from your account settings. Your premium features will remain active until the end of your current billing period, and you can always reactivate your subscription later without losing your data.
                            </p>
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-2xl shadow-lg overflow-hidden">
                        <button class="accordion-button w-full px-8 py-6 text-left flex items-center justify-between focus:outline-none focus:bg-gray-50" 
                                onclick="toggleAccordion(this)">
                            <h3 class="text-lg font-semibold text-gray-800">What payment methods do you accept?</h3>
                            <svg class="rotate-icon w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="accordion-content hidden px-8 pb-6">
                            <p class="text-gray-600 leading-relaxed">
                                We accept all major credit cards (Visa, MasterCard, American Express), PayPal, and bank transfers. All payments are processed securely through our encrypted payment system with industry-standard security measures.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div id="features" class="mb-16">
                <h2 class="text-3xl font-bold text-gray-800 mb-8 flex items-center">
                    <span class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </span>
                    Features
                </h2>
                
                <div class="space-y-4">
                    <div class="faq-item bg-white rounded-2xl shadow-lg overflow-hidden">
                        <button class="accordion-button w-full px-8 py-6 text-left flex items-center justify-between focus:outline-none focus:bg-gray-50" 
                                onclick="toggleAccordion(this)">
                            <h3 class="text-lg font-semibold text-gray-800">How do health reminders work?</h3>
                            <svg class="rotate-icon w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="accordion-content hidden px-8 pb-6">
                            <p class="text-gray-600 leading-relaxed">
                                Our system automatically calculates when your pet is due for vaccinations, checkups, or medication based on their health records. You'll receive email notifications and dashboard alerts before important dates, helping you stay on top of your pet's health schedule.
                            </p>
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-2xl shadow-lg overflow-hidden">
                        <button class="accordion-button w-full px-8 py-6 text-left flex items-center justify-between focus:outline-none focus:bg-gray-50" 
                                onclick="toggleAccordion(this)">
                            <h3 class="text-lg font-semibold text-gray-800">Can I share records with my veterinarian?</h3>
                            <svg class="rotate-icon w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="accordion-content hidden px-8 pb-6">
                            <p class="text-gray-600 leading-relaxed">
                                Absolutely! You can generate and share comprehensive health reports with your veterinarian, or invite them to view your pet's records directly through our platform. This ensures they have complete information for better care decisions during appointments.
                            </p>
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-2xl shadow-lg overflow-hidden">
                        <button class="accordion-button w-full px-8 py-6 text-left flex items-center justify-between focus:outline-none focus:bg-gray-50" 
                                onclick="toggleAccordion(this)">
                            <h3 class="text-lg font-semibold text-gray-800">What types of health records can I track?</h3>
                            <svg class="rotate-icon w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="accordion-content hidden px-8 pb-6">
                            <p class="text-gray-600 leading-relaxed">
                                You can track vaccinations, medications, weight changes, vet visits, allergies, surgeries, behavioral notes, dietary information, exercise logs, and any other health-related observations. Our flexible system adapts to your pet's specific needs.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support -->
            <div id="support" class="mb-16">
                <h2 class="text-3xl font-bold text-gray-800 mb-8 flex items-center">
                    <span class="w-10 h-10 bg-gradient-to-r from-orange-500 to-red-500 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </span>
                    Support
                </h2>
                
                <div class="space-y-4">
                    <div class="faq-item bg-white rounded-2xl shadow-lg overflow-hidden">
                        <button class="accordion-button w-full px-8 py-6 text-left flex items-center justify-between focus:outline-none focus:bg-gray-50" 
                                onclick="toggleAccordion(this)">
                            <h3 class="text-lg font-semibold text-gray-800">How can I contact support?</h3>
                            <svg class="rotate-icon w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="accordion-content hidden px-8 pb-6">
                            <p class="text-gray-600 leading-relaxed">
                                You can reach our support team through multiple channels: email at support@pethealthtracker.com, phone at +1 (555) 123-4567 during business hours, or through the contact form on our website. Premium users get priority support with faster response times.
                            </p>
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-2xl shadow-lg overflow-hidden">
                        <button class="accordion-button w-full px-8 py-6 text-left flex items-center justify-between focus:outline-none focus:bg-gray-50" 
                                onclick="toggleAccordion(this)">
                            <h3 class="text-lg font-semibold text-gray-800">Is my pet's data secure?</h3>
                            <svg class="rotate-icon w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="accordion-content hidden px-8 pb-6">
                            <p class="text-gray-600 leading-relaxed">
                                Yes! We use bank-level encryption and security measures to protect your data. All information is stored on secure servers with regular backups, and we comply with industry privacy standards. You have full control over who can access your pet's information.
                            </p>
                        </div>
                    </div>

                    <div class="faq-item bg-white rounded-2xl shadow-lg overflow-hidden">
                        <button class="accordion-button w-full px-8 py-6 text-left flex items-center justify-between focus:outline-none focus:bg-gray-50" 
                                onclick="toggleAccordion(this)">
                            <h3 class="text-lg font-semibold text-gray-800">What if I encounter a technical issue?</h3>
                            <svg class="rotate-icon w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div class="accordion-content hidden px-8 pb-6">
                            <p class="text-gray-600 leading-relaxed">
                                First, try refreshing your browser or clearing your cache. If the issue persists, contact our technical support team with details about the problem, your browser, and device. We typically resolve technical issues within 24 hours for Premium users and 48 hours for Basic users.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Still Have Questions -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-2xl mx-auto">
                <h2 class="text-4xl font-bold text-gray-800 mb-6">Still Have Questions?</h2>
                <p class="text-xl text-gray-600 mb-8">
                    Can't find what you're looking for? Our friendly support team is here to help!
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="contact.php" 
                       class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-8 py-4 rounded-2xl hover:from-blue-600 hover:to-purple-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Contact Support
                    </a>
                    <a href="tel:+15551234567" 
                       class="bg-white border-2 border-gray-300 text-gray-700 px-8 py-4 rounded-2xl hover:bg-gray-50 hover:border-gray-400 transition-all duration-300 font-semibold flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        Call Us
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include_once '../includes/footer.php'; ?>

<script>
function toggleAccordion(button) {
    const content = button.nextElementSibling;
    const icon = button.querySelector('.rotate-icon');
    const isExpanded = button.getAttribute('aria-expanded') === 'true';
    
    // Close all other accordions
    document.querySelectorAll('.accordion-button').forEach(btn => {
        if (btn !== button) {
            btn.setAttribute('aria-expanded', 'false');
            btn.nextElementSibling.classList.add('hidden');
            btn.querySelector('.rotate-icon').style.transform = 'rotate(0deg)';
        }
    });
    
    // Toggle current accordion
    if (isExpanded) {
        button.setAttribute('aria-expanded', 'false');
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    } else {
        button.setAttribute('aria-expanded', 'true');
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    }
}

// Smooth scrolling for category links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>

</body>
</html>
