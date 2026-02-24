<?php
include __DIR__ . '/src/helpers/url.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: /public/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TraceX · Smart expense tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="icon" type="image/png" href="/public/assets/favicon.png">
    <!-- subtle modern font -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:opsz@14..32&display=swap');
        body { font-family: 'Inter', system-ui, sans-serif; }
        .feature-card { transition: all 0.2s ease; }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 25px 30px -12px rgba(0,0,0,0.15); }
        .step-circle { transition: background 0.2s; }
        .btn-primary { transition: background 0.2s, transform 0.1s; }
        .btn-primary:active { transform: scale(0.98); }
    </style>
</head>
<body class="bg-white text-gray-800 antialiased">

    <!-- hero section -->
    <section class="max-w-7xl mx-auto px-5 pt-16 pb-20 md:pt-24 md:pb-28">
        <div class="flex flex-col lg:flex-row items-center gap-14">
            <!-- left text -->
            <div class="flex-1 text-center lg:text-left">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight text-gray-900 leading-tight">
                    Take Control of <br class="hidden sm:block">Every Expense with <span class="text-indigo-600">TraceX</span>
                </h1>
                <p class="mt-6 text-xl text-gray-600 max-w-2xl mx-auto lg:mx-0">
                    TraceX helps you track your expenses, manage budgets, and save smarter all in one app.
                </p>
                <div class="mt-8 flex flex-wrap items-center justify-center lg:justify-start gap-4">
                    <a href="<?= url('register/index.php') ?>" class="inline-flex items-center justify-center px-7 py-4 text-base font-medium rounded-xl border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 shadow-sm">Get Started for Free</a>
                    <!-- <a href="#" class="inline-flex items-center justify-center px-7 py-4 text-base font-medium rounded-xl border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:ring-4 focus:ring-gray-200 shadow-sm">See Demo</a> -->
                </div>
            </div>
            <!-- right visual: mockup + graph hint (pure tailwind box mock) -->
            <div class="flex-1 w-full relative">
                <div class="bg-white rounded-3xl shadow-2xl border border-gray-200 p-4 md:p-6">
                    <!-- top bar with dots -->
                    <div class="flex items-center gap-2 mb-5">
                        <div class="w-3 h-3 rounded-full bg-rose-400"></div>
                        <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                        <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                        <span class="ml-2 text-xs text-gray-400 font-medium">TraceX dashboard · preview</span>
                    </div>
                    <!-- laptop + phone side by side (abstract) -->
                    <div class="flex flex-col md:flex-row gap-4 items-center">
                        <!-- laptop mock -->
                        <div class="bg-gray-100 rounded-xl p-3 w-full max-w-[280px] shadow-inner border border-gray-200">
                            <div class="bg-white rounded-lg h-40 w-full p-3 flex flex-col justify-between">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-semibold text-indigo-600">May 2025</span>
                                    <span class="text-[10px] bg-indigo-100 px-2 py-0.5 rounded-full">+4.2%</span>
                                </div>
                                <!-- mini spending bars (graph) -->
                                <div class="space-y-2 mt-3">
                                    <div class="flex items-center gap-2"><span class="text-xs w-12">Food</span> <div class="h-2 flex-1 rounded-full bg-indigo-200"><div class="h-2 w-3/4 rounded-full bg-indigo-500"></div></div></div>
                                    <div class="flex items-center gap-2"><span class="text-xs w-12">Transport</span> <div class="h-2 flex-1 rounded-full bg-indigo-200"><div class="h-2 w-2/5 rounded-full bg-indigo-500"></div></div></div>
                                    <div class="flex items-center gap-2"><span class="text-xs w-12">Shopping</span> <div class="h-2 flex-1 rounded-full bg-indigo-200"><div class="h-2 w-4/5 rounded-full bg-indigo-500"></div></div></div>
                                </div>
                                <!-- tiny pie hint -->
                                <div class="flex justify-end mt-2"><div class="w-6 h-6 rounded-full border-2 border-indigo-400 border-r-transparent"></div></div>
                            </div>
                            <div class="mt-2 text-center text-[10px] text-gray-400">laptop view</div>
                        </div>
                        <!-- phone mock -->
                        <div class="bg-gray-100 rounded-2xl p-2 w-[120px] shadow-inner border border-gray-200">
                            <div class="bg-white rounded-lg h-32 w-full p-2 flex flex-col">
                                <div class="flex justify-between"><span class="text-[8px] font-bold">$342</span><span class="text-[6px] bg-emerald-100 px-1 rounded">budget</span></div>
                                <!-- tiny line chart -->
                                <svg viewBox="0 0 50 30" class="mt-1 w-full h-8 stroke-indigo-500 stroke-1 fill-none">
                                    <polyline points="2,20 12,10 22,18 32,5 42,12 48,20" stroke="currentColor" stroke-width="1.5" />
                                </svg>
                                <span class="mt-auto text-[6px] text-gray-400">phone sync</span>
                            </div>
                        </div>
                    </div>
                    <!-- spending trend line -->
                    <div class="mt-6 border-t border-gray-100 pt-4 flex items-center justify-between text-xs text-gray-500">
                        <span><i class="fa-regular fa-clock mr-1"></i> spending trend</span>
                        <span class="text-emerald-600 font-medium">↓ 8% vs last month</span>
                    </div>
                </div>
                <!-- floating graph element (decor) -->
                <div class="absolute -bottom-4 -left-4 w-20 h-20 bg-indigo-100 rounded-full -z-10 blur-xl opacity-60"></div>
            </div>
        </div>
    </section>

    <!-- features section -->
    <section class="max-w-7xl mx-auto px-5 py-20 md:py-24">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Designed to give you clarity</h2>
            <p class="text-gray-600 mt-4 text-xl">Everything you need to master your money in one place.</p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- card 1 -->
            <div class="feature-card bg-white p-8 rounded-3xl border border-gray-100 shadow-md hover:shadow-xl">
                <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-700 text-2xl mb-5"><i class="fa-regular fa-compass"></i></div>
                <h3 class="text-xl font-bold text-gray-900">Track Spending Effortlessly</h3>
                <p class="text-gray-600 mt-2 text-base/relaxed">See where your money goes, in real time. Categorize transactions automatically.</p>
            </div>
            <!-- card 2 -->
            <div class="feature-card bg-white p-8 rounded-3xl border border-gray-100 shadow-md hover:shadow-xl">
                <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-700 text-2xl mb-5"><i class="fa-regular fa-bell"></i></div>
                <h3 class="text-xl font-bold text-gray-900">Smart Budgets</h3>
                <p class="text-gray-600 mt-2 text-base/relaxed">Set limits and get alerts before overspending. Stay on track without stress.</p>
            </div>
            <!-- card 3 -->
            <div class="feature-card bg-white p-8 rounded-3xl border border-gray-100 shadow-md hover:shadow-xl">
                <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-700 text-2xl mb-5"><i class="fa-regular fa-chart-bar"></i></div>
                <h3 class="text-xl font-bold text-gray-900">Visual Insights</h3>
                <p class="text-gray-600 mt-2 text-base/relaxed">Charts and reports to help you plan better. Spot trends at a glance.</p>
            </div>
            <!-- card 4 -->
            <div class="feature-card bg-white p-8 rounded-3xl border border-gray-100 shadow-md hover:shadow-xl">
                <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-indigo-700 text-2xl mb-5"><i class="fa-solid fa-lock"></i></div>
                <h3 class="text-xl font-bold text-gray-900">Secure & Private</h3>
                <p class="text-gray-600 mt-2 text-base/relaxed">Your data stays safe. We never share your info.</p>
            </div>
        </div>
    </section>

    <!-- how it works (3 steps) -->
    <section class="max-w-6xl mx-auto px-5 py-16 md:py-20 bg-indigo-50/40 rounded-3xl md:rounded-4xl">
        <div class="text-center mb-14">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Start in minutes</h2>
            <p class="text-gray-600 mt-3 text-lg">No complicated setup, just you and your goals.</p>
        </div>
        <div class="flex flex-col md:flex-row justify-center items-start md:items-center gap-10 md:gap-6 lg:gap-12 max-w-4xl mx-auto">
            <!-- step 1 -->
            <div class="flex-1 text-center">
                <div class="step-circle w-16 h-16 bg-indigo-600 text-white rounded-3xl text-2xl font-bold flex items-center justify-center mx-auto shadow-lg">1</div>
                <h3 class="text-xl font-semibold mt-5 text-gray-900">Sign Up</h3>
                <p class="text-gray-600 mt-2">Create your account in seconds.</p>
            </div>
            <div class="hidden md:block w-12 h-0.5 bg-indigo-200 rounded-full"></div>
            <!-- step 2 -->
            <div class="flex-1 text-center">
                <div class="step-circle w-16 h-16 bg-indigo-600 text-white rounded-3xl text-2xl font-bold flex items-center justify-center mx-auto shadow-lg">2</div>
                <h3 class="text-xl font-semibold mt-5 text-gray-900">Save Expenses</h3>
                <p class="text-gray-600 mt-2">Start saving your expenses, you're in control.</p>
            </div>
            <div class="hidden md:block w-12 h-0.5 bg-indigo-200 rounded-full"></div>
            <!-- step 3 -->
            <div class="flex-1 text-center">
                <div class="step-circle w-16 h-16 bg-indigo-600 text-white rounded-3xl text-2xl font-bold flex items-center justify-center mx-auto shadow-lg">3</div>
                <h3 class="text-xl font-semibold mt-5 text-gray-900">Track & Optimize</h3>
                <p class="text-gray-600 mt-2">Analyze, plan, and save smarter with real insights.</p>
            </div>
        </div>
    </section>

    <!-- testimonials -->
    <!-- <section class="max-w-7xl mx-auto px-5 py-20 md:py-24">
        <div class="text-center mb-14">
            <span class="text-sm font-semibold text-indigo-600 uppercase tracking-wider">Trusted by early adopters</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-3">Loved by finance enthusiasts</h2>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                <div class="flex text-amber-400 text-lg gap-0.5 mb-4">★★★★★</div>
                <p class="text-gray-700 text-lg italic">“TraceX turned my chaotic expenses into crystal clear categories. The alerts saved me from overdrafts twice!”</p>
                <div class="mt-6 flex items-center gap-3">
                    <div class="w-11 h-11 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-bold">MP</div>
                    <div><p class="font-semibold text-gray-900">Megan Park</p><p class="text-sm text-gray-500">early adopter</p></div>
                </div>
            </div>
        </div>
        <p class="text-center text-gray-500 text-sm mt-12"><i class="fa-regular fa-star mr-1"></i> Join 2,000+ users who started their journey</p>
    </section> -->

    <!-- footer -->
    <footer class="bg-gray-50 border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-5 py-12 md:py-16">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <!-- logo -->
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-bold text-indigo-600">TraceX</span>
                    <span class="text-sm text-gray-500 font-light"> &copy; <?= date('Y') ?></span>
                </div>
                <!-- footer links -->
                <div class="flex flex-wrap justify-center gap-x-8 gap-y-3 text-sm font-medium text-gray-600">
                    <a href="<?= url('terms-and-conditions.php') ?>" class="hover:text-indigo-600 transition">Terms & Conditions</a>
                    <a href="https://aungkyawth3ts-portfolio.vercel.app/#contact" target="_blank" class="hover:text-indigo-600 transition">Contact</a>
                </div>
                <!-- social icons -->
                <div class="flex gap-5 text-gray-500 text-xl">
                    <a href="https://github.com/aungkyawthetx" target="_blank" class="hover:text-indigo-600 transition"><i class="fab fa-github"></i></a>
                </div>
            </div>
            <div class="text-center text-xs text-gray-400 mt-10"> &copy; TraceX. Made with <i class="fa-regular fa-heart text-rose-400"></i> for smart spenders</div>
        </div>
    </footer>
</body>
</html>
