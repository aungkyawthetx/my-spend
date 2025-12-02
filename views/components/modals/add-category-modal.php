<div id="addCategoryModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-10">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class="fas fa-tag text-indigo-600"></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Add New Category</h3>
                        <div class="mt-2">
                            <form id="categoryForm">
                                <div class="mb-4">
                                    <label for="categoryName" class="block text-sm font-medium text-gray-700">Category Name</label>
                                    <input type="text" id="categoryName" name="category_name" placeholder="Foods & Dining" class="mt-1 focus:outline-none focus:ring-2 p-2 focus:ring-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md">
                                </div>
                                <div class="mb-4">
                                    <label for="categoryDescription" class="block text-sm font-medium text-gray-700">Description</label>
                                    <textarea id="categoryDescription" rows="3" name="description" placeholder="Restaurants, groceries, and food delivery" class="mt-1 focus:outline-none focus:ring-2 p-2 focus:ring-indigo-500 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                                </div>
                                <div class="mb-4">
                                    <label for="categoryBudget" class="block text-sm font-medium text-gray-700">Monthly Budget</label>
                                    <div class="mt-1 relative rounded-md">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">$</span>
                                        </div>
                                        <input type="number" id="categoryBudget" name="monthly_budget" class="p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 block w-full pl-7 pr-12 sm:text-sm border border-gray-300 rounded-md" placeholder="150000.00 Ks">
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="categoryIcon" class="block text-sm font-medium text-gray-700">Icon</label>
                                    <select id="categoryIcon" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm rounded-md">
                                        <option value="">Choose Icon</option>
                                        <option value="utensils">Food (utensils)</option>
                                        <option value="car">Transportation (car)</option>
                                        <option value="lightbulb">Utilities (lightbulb)</option>
                                        <option value="film">Entertainment (film)</option>
                                        <option value="shopping-bag">Shopping (bag)</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="categoryColor" class="block text-sm font-medium text-gray-700">Color</label>
                                    <select id="categoryColor" class="mt-1 p-2 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 sm:text-sm rounded-md">
                                        <option value="red">Red</option>
                                        <option value="blue">Blue</option>
                                        <option value="yellow">Yellow</option>
                                        <option value="purple">Purple</option>
                                        <option value="green">Green</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                                        <span class="ml-2 text-sm text-gray-700">Active category</span>
                                    </label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm cursor-pointer">
                    Save Category
                </button>
                <button onclick="closeAddCategoryModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm cursor-pointer">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>