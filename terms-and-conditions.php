<?php
    require_once __DIR__ . '/src/helpers/url.php';
    $title = "Terms & Conditions - TraceX";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="icon" type="image/png" href="/public/assets/logo.png">
</head>
<body class="bg-gray-50 text-gray-800 font-sans leading-relaxed">
    <div class="max-w-3xl mx-auto px-4 py-10">
        <h1 class="text-3xl font-bold mb-6 text-center">Terms & Conditions</h1>
        <a href="<?= url('index.php') ?>" class="font-semibold hover:underline float-end">Go back</a>

        <p class="mb-4"><strong>Last updated:</strong> <?= date('Y') ?></p>

        <p class="mb-4">
            Welcome to <strong>TraceX which stands for Trace eXpense - The expense tracking web app,</strong>
            developed and operated by <strong>Aung Kyaw Thet</strong>.
            By accessing or using this App, you agree to be bound by these Terms and Conditions.
            If you do not agree with any part of these terms, please discontinue use of the App.
        </p>

        <!-- 1 -->
        <h2 class="text-xl font-semibold mt-6 mb-2">1. Purpose of the Application</h2>
        <p class="mb-4">
            TraceX helps users record and manage:
        </p>
        <ul class="list-disc list-inside mb-4">
            <li>User account information</li>
            <li>Expense records, categories, and payment methods</li>
            <li>Monthly category-based budgets</li>
            <li>Savings goals and saving transactions (deposits and withdrawals)</li>
        </ul>
        <p class="mb-4">
            The App is provided for <strong>personal financial tracking and informational purposes only</strong>
            and does <strong>not</strong> constitute financial, investment, legal, or professional advice.
        </p>

        <!-- 2 -->
        <h2 class="text-xl font-semibold mt-6 mb-2">2. User Accounts</h2>
        <ul class="list-disc list-inside mb-4">
            <li>You must provide accurate and complete registration information.</li>
            <li>You are responsible for maintaining the confidentiality of your account credentials.</li>
            <li>All activities performed under your account are your responsibility.</li>
            <li>The App may store authentication tokens or session-related data for login functionality.</li>
        </ul>

        <!-- 3 -->
        <h2 class="text-xl font-semibold mt-6 mb-2">3. Financial Data Storage</h2>
        <p class="mb-4">
            The App stores financial tracking data entered by users, including:
            expenses, categories, budgets, savings goals, and saving transactions.
        </p>
        <ul class="list-disc list-inside mb-4">
            <li>All financial values are <strong>user-entered estimates</strong> and may not reflect real bank balances.</li>
            <li>The App does <strong>not connect to banks or financial institutions</strong>.</li>
            <li>Users are solely responsible for verifying the accuracy of their data.</li>
        </ul>

        <!-- 4 -->
        <h2 class="text-xl font-semibold mt-6 mb-2">4. Data Protection & Privacy</h2>
        <ul class="list-disc list-inside mb-4">
            <li>Reasonable technical measures are taken to protect stored data.</li>
            <li>No internet system is completely secure; data breaches or loss may occur.</li>
            <li>The App owner is not liable for data loss caused by hosting failures, attacks, or user negligence.</li>
        </ul>

        <!-- 5 -->
        <h2 class="text-xl font-semibold mt-6 mb-2">5. User Responsibilities</h2>
        <p class="mb-4">You agree that you will:</p>
        <ul class="list-disc list-inside mb-4">
            <li>Use the App only for lawful personal financial tracking.</li>
            <li>Not attempt to hack, exploit, reverse-engineer, or disrupt the system or database.</li>
            <li>Not submit malicious scripts, automated abuse, or harmful data.</li>
        </ul>

        <!-- 6 -->
        <h2 class="text-xl font-semibold mt-6 mb-2">6. Savings & Budget Calculations Disclaimer</h2>
        <p class="mb-4">
            Budget totals, savings progress, and transaction summaries are generated automatically
            from stored user data. These calculations:
        </p>
        <ul class="list-disc list-inside mb-4">
            <li>May contain rounding or logical differences.</li>
            <li>Should not be relied upon for real financial decisions.</li>
            <li>Are provided strictly for personal tracking convenience.</li>
        </ul>

        <!-- 7 -->
        <h2 class="text-xl font-semibold mt-6 mb-2">7. Intellectual Property</h2>
        <p class="mb-4">
            All source code, UI design, database structure, branding, and functionality of TraceX
            are the intellectual property of <strong>Aung Kyaw Thet</strong>, unless otherwise stated.
            Unauthorized copying, redistribution, or modification is prohibited.
        </p>

        <!-- 8 -->
        <h2 class="text-xl font-semibold mt-6 mb-2">8. Limitation of Liability</h2>
        <p class="mb-4">
            The App is provided on an <strong>"as is"</strong> and <strong>"as available"</strong> basis.
            The App owner is not liable for:
        </p>
        <ul class="list-disc list-inside mb-4">
            <li>Financial loss or incorrect budgeting decisions</li>
            <li>Data inaccuracies or missing transactions</li>
            <li>Service interruptions or downtime</li>
        </ul>

        <!-- 9 -->
        <h2 class="text-xl font-semibold mt-6 mb-2">9. Availability & Maintenance</h2>
        <p class="mb-4">
            The App may be modified, suspended, or discontinued at any time
            due to maintenance, upgrades, or hosting limitations without prior notice.
        </p>

        <!-- 10 -->
        <h2 class="text-xl font-semibold mt-6 mb-2">10. Account Termination</h2>
        <p class="mb-4">
            Accounts that violate these Terms may be suspended or permanently removed.
            Stored financial data may also be deleted upon termination.
        </p>

        <!-- 11 -->
        <h2 class="text-xl font-semibold mt-6 mb-2">11. Changes to These Terms</h2>
        <p class="mb-4">
            These Terms may be updated periodically. Continued use of the App
            after updates constitutes acceptance of the revised Terms.
        </p>

        <!-- 12 -->
        <h2 class="text-xl font-semibold mt-6 mb-2">12. Governing Law</h2>
        <p class="mb-4">
            These Terms shall be governed in accordance with applicable local laws and regulations.
        </p>

        <!-- 13 -->
        <h2 class="text-xl font-semibold mt-6 mb-2">13. Contact Information</h2>
        <p class="mb-4">
            For questions regarding these Terms:<br>
            <strong>Aung Kyaw Thet</strong><br>
            Email: <em>aungkyawthethimself@gmail.com</em>
        </p>

        <?php include __DIR__ . '/views/components/copyright.php'; ?>
    </div>
</body>
</html>
