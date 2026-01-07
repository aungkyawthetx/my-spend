<div class="hidden md:flex md:shrink-0">
    <div class="flex flex-col w-64 bg-indigo-800">
        <div class="flex items-center justify-center bg-indigo-900 px-3 py-3">
            <div class="flex items-center space-x-2">
                <div class="w-7 h-7 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-pie text-white"></i>
                </div>
                <a href="<?= url('public/index.php') ?>" class="text-xl font-bold text-white">
                    BudgetBoard
                </a>
            </div>
        </div>
        <div class="flex flex-col flex-1 px-4 py-4 overflow-y-auto">
            <div class="space-y-1">
                <a href="<?= url('public/index.php') ?>" class="flex items-center px-2 py-3 text-sm font-medium <?= isActive('public/index.php') ? 'text-white bg-indigo-900' : 'text-indigo-200 hover:text-white hover:bg-indigo-700' ?> rounded-lg">
                    <i class="fas fa-house mr-3"></i>
                    Dashboard
                </a>
                <a href="<?= url('public/expense.php') ?>" class="flex items-center px-2 py-3 text-sm font-medium <?= isActive('public/expense.php') ? 'text-white bg-indigo-900' : 'text-indigo-200 hover:text-white hover:bg-indigo-700' ?> rounded-lg">
                    <i class="fa-solid fa-dollar-sign mr-3"></i>
                    Expenses
                </a>
                <a href="<?= url('public/category.php') ?>" class="flex items-center px-2 py-3 text-sm font-medium <?= isActive('public/category.php') ? 'text-white bg-indigo-900' : 'text-indigo-200 hover:text-white hover:bg-indigo-700' ?> rounded-lg">
                    <i class="fas fa-tags mr-3"></i>
                    Categories
                </a>
                <a href="<?= url('public/report.php') ?>" class="flex items-center px-2 py-3 text-sm font-medium <?= isActive('public/report.php') ? 'text-white bg-indigo-900' : 'text-indigo-200 hover:text-white hover:bg-indigo-700' ?> rounded-lg">
                    <i class="fas fa-chart-pie mr-3"></i>
                    Reports
                </a>
                <a href="#" class="flex items-center px-2 py-3 text-sm font-medium text-indigo-200 hover:text-white hover:bg-indigo-700 rounded-lg">
                    <i class="fas fa-users mr-3"></i>
                    Users
                </a>
                <a href="#" class="flex items-center px-2 py-3 text-sm font-medium text-indigo-200 hover:text-white hover:bg-indigo-700 rounded-lg">
                    <i class="fas fa-cog mr-3"></i>
                    Settings
                </a>
            </div>
            
            <!-- <div class="mt-auto mb-4">
                <div class="bg-indigo-700 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="bg-indigo-600 p-2 rounded-full">
                            <i class="fas fa-gem text-white"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-white">Upgrade to Pro</p>
                            <p class="text-xs text-indigo-200">Get access to all features</p>
                        </div>
                    </div>
                    <button class="mt-2 w-full bg-white text-indigo-800 py-1 px-3 rounded text-sm font-medium hover:bg-gray-100">
                        Upgrade Now
                    </button>
                </div>
            </div> -->
        </div>
    </div>
</div>