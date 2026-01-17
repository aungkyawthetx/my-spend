<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Categories</h1>
        <p class="text-gray-600">View your expense count with categories</p>
    </div>
    <!-- <button onclick="openAddCategoryModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 flex items-center cursor-pointer">
        <i class="fas fa-plus mr-1"></i> New Category
    </button> -->
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between p-4 border-b border-gray-200">
        <div class="mb-4 md:mb-0">
            <form action="category.php" method="GET" class="flex items-center space-x-2">
                <div class="relative max-w-xs">
                    <label for="search" class="sr-only">Search</label>
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" value="<?php if(isset($_GET['search'])) echo $_GET['search'] ?>" id="search" name="search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-full leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-sky-600 sm:text-sm placeholder:italic" placeholder="Search categories...">
                </div>
                <button type="submit" class="bg-sky-600 hover:bg-sky-700 border-2 border-sky-600 text-white px-4 py-2 rounded-full cursor-pointer">Search</button>
                <a href="category.php" class="border-2 border-gray-300 hover:bg-gray-300 text-gray-500 hover:text-white px-5 py-2 rounded-full cursor-pointer">Reset</a>
            </form>
        </div>
        <div class="flex">
            <button class="px-3 py-1 border border-gray-300 cursor-pointer rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <i class="fas fa-download mr-1"></i> Export
            </button>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Expenses</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php
                    $category_bg = [
                        'utensils' => 'bg-red-100',
                        'bus' => 'bg-blue-100',
                        'film' => 'bg-purple-100',
                        'lightbulb' => 'bg-yellow-100',
                        'shopping-cart' => 'bg-rose-100',
                        'heart' => 'bg-green-100',
                        'graduation-cap' => 'bg-indigo-100',
                        'plane' => 'bg-sky-100',
                        'file-invoice-dollar' => 'bg-orange-100',
                        'question' => 'bg-gray-100'
                    ];
                ?>

                <?php if(count($categories) > 0): ?>
                    <?php foreach($categories as $category): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="shrink-0 h-10 w-10 rounded-full <?= $category_bg[$category['icon']] ?? 'bg-gray-100' ?> flex items-center justify-center">
                                        <i class="fa-solid fa-<?= $category['icon'] ?>" style="color: <?= $category['color'] ?>"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900"> <?= $category['name'] ?? 'N/A' ?> </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"> <?= $category['description'] ?? 'N/A' ?> </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"> <?= $expenseCounts[$category['id']] ?? 0 ?> </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick="openEditCategoryModal(this)" 
                                    class="text-indigo-600 hover:text-indigo-900 mr-3 cursor-pointer" 
                                    title="Edit Category"
                                    data-id="<?= $category['id'] ?>"
                                    data-name="<?= htmlspecialchars($category['name']) ?>"
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center"> No categories found. </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="editCategoryModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-tag text-blue-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Set Monthly Budget</h3>
                        <div class="mt-2">
                            <form id="categoryForm" method="POST" action="<?= url('public/category.php') ?>">
                                <input type="hidden" name="edit_category_id" id="edit_category_id">
                                <div class="mb-4">
                                    <label for="categoryName" class="block text-sm font-medium text-gray-700">Category Name</label>
                                    <input type="text" id="categoryName" name="category_name" placeholder="Foods & Dining" class="mt-1 focus:outline-none focus:ring-2 p-2 focus:ring-blue-500 block w-full sm:text-sm border border-gray-300 rounded-md" disabled>
                                </div>
                                <div class="mb-4">
                                    <label for="monthlyBudget" class="block text-sm font-medium text-gray-700">Monthly Budget</label>
                                    <div class="mt-1 relative rounded-md">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" id="monthlyBudget" name="monthlyBudget" class="p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 block w-full pl-7 pr-12 sm:text-sm border border-gray-300 rounded-md" placeholder="150000.00">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button name="btnSaveCategory" form="categoryForm" type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm cursor-pointer">
                    Save Category
                </button>
                <button onclick="closeEditCategoryModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm cursor-pointer">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>