<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Pet Health Tracker</title>
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
                        'wiggle': 'wiggle 1s ease-in-out infinite',
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
        @keyframes wiggle {
            0%, 100% { transform: rotate(-3deg); }
            50% { transform: rotate(3deg); }
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .contact-card:hover .contact-icon {
            animation: wiggle 1s ease-in-out infinite;
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
                <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">Contact Us</h1>
                <p class="text-xl md:text-2xl text-blue-100 max-w-4xl mx-auto leading-relaxed">
                    We're here to help! Get in touch with our expert team for any questions or support you need.
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-2 gap-16">
                <!-- Contact Form -->
                <div class="animate-slide-up">
                    <div class="bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
                        <h2 class="text-3xl font-bold text-gray-800 mb-8">Send us a Message</h2>
                        <form class="space-y-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label for="firstName" class="block text-sm font-semibold text-gray-700 mb-3">First Name</label>
                                    <input type="text" id="firstName" name="firstName" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white">
                                </div>
                                <div>
                                    <label for="lastName" class="block text-sm font-semibold text-gray-700 mb-3">Last Name</label>
                                    <input type="text" id="lastName" name="lastName" required 
                                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white">
                                </div>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-3">Email Address</label>
                                <input type="email" id="email" name="email" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white">
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-3">Phone Number</label>
                                <input type="tel" id="phone" name="phone" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white">
                            </div>

                            <div>
                                <label for="subject" class="block text-sm font-semibold text-gray-700 mb-3">Subject</label>
                                <select id="subject" name="subject" required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white">
                                    <option value="">Select a subject</option>
                                    <option value="general">General Inquiry</option>
                                    <option value="support">Technical Support</option>
                                    <option value="billing">Billing Question</option>
                                    <option value="emergency">Emergency Support</option>
                                    <option value="feedback">Feedback</option>
                                </select>
                            </div>

                            <div>
                                <label for="message" class="block text-sm font-semibold text-gray-700 mb-3">Message</label>
                                <textarea id="message" name="message" rows="6" required 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white"
                                          placeholder="Tell us how we can help you..."></textarea>
                            </div>

                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-4 rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="space-y-8">
                    <!-- Contact Details -->
                    <div class="contact-card bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
                        <h2 class="text-3xl font-bold text-gray-800 mb-8">Get in Touch</h2>
                        <div class="space-y-6">
                            <div class="flex items-center p-4 bg-blue-50 rounded-2xl">
                                <div class="contact-icon w-14 h-14 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center mr-6">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg">Phone</h3>
                                    <p class="text-gray-600 font-semibold">+1 (555) 123-4567</p>
                                </div>
                            </div>

                            <div class="flex items-center p-4 bg-green-50 rounded-2xl">
                                <div class="contact-icon w-14 h-14 bg-gradient-to-r from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center mr-6">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg">Email</h3>
                                    <p class="text-gray-600 font-semibold">support@pethealthtracker.com</p>
                                </div>
                            </div>

                            <div class="flex items-center p-4 bg-purple-50 rounded-2xl">
                                <div class="contact-icon w-14 h-14 bg-gradient-to-r from-purple-500 to-violet-500 rounded-2xl flex items-center justify-center mr-6">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-800 text-lg">Address</h3>
                                    <p class="text-gray-600 font-semibold">123 Pet Care Lane<br>Animal City, AC 12345</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Business Hours -->
                    <div class="bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Business Hours</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                                <span class="text-gray-700 font-medium">Monday - Friday</span>
                                <span class="font-bold text-gray-800">8:00 AM - 8:00 PM</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                                <span class="text-gray-700 font-medium">Saturday</span>
                                <span class="font-bold text-gray-800">9:00 AM - 6:00 PM</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                                <span class="text-gray-700 font-medium">Sunday</span>
                                <span class="font-bold text-gray-800">10:00 AM - 4:00 PM</span>
                            </div>
                            <div class="border-t border-gray-200 pt-4 mt-6">
                                <div class="flex justify-between items-center p-3 bg-red-50 rounded-xl">
                                    <span class="text-red-700 font-medium">Emergency Support</span>
                                    <span class="font-bold text-red-600">24/7 Available</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    <div class="bg-gradient-to-r from-red-500 to-pink-500 rounded-3xl p-8 text-white shadow-2xl">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold">Emergency Support</h3>
                        </div>
                        <p class="text-red-100 mb-4 text-lg">For urgent pet health emergencies, contact us immediately:</p>
                        <p class="font-bold text-2xl">Emergency Hotline: +1 (555) 911-PETS</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section (Optional) -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold text-gray-800 mb-8">Visit Our Office</h2>
            <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-4xl mx-auto">
                <div class="bg-gray-200 h-64 rounded-2xl flex items-center justify-center">
                    <p class="text-gray-600 text-lg">Interactive Map Coming Soon</p>
                </div>
                <div class="mt-6 text-center">
                    <p class="text-gray-600 mb-4">
                        Located in the heart of Animal City, we're easily accessible by public transport and have ample parking available.
                    </p>
                    <a href="#" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold">
                        Get Directions
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include_once '../includes/footer.php'; ?>

</body>
</html>