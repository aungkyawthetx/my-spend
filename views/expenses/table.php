<div class="flex flex-col md:flex-row md:items-center md:justify-between p-4 border-b border-gray-200">
    <div class="mb-4 md:mb-0">
        <div class="relative max-w-xs">
            <label for="expense-search" class="sr-only">Search</label>
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" id="expense-search" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Search expenses...">
        </div>
    </div>
    <div class="flex items-center space-x-2">
        <button class="px-3 py-1 border border-gray-300 cursor-pointer rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <i class="fas fa-download mr-1"></i> Export
        </button>
        <button class="px-3 py-1 border border-gray-300 cursor-pointer rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50" onclick="window.print()">
            <i class="fas fa-print mr-1"></i> Print
        </button>
    </div>
</div>
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Spent</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description & Note</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount (Ks)</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach($expenses as $expense): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"> <?= date("M j, Y", strtotime($expense['expense_date'] ?? '')) ?> </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900"> <?= $expense['description'] ?? '-' ?> </div>
                        <div class="text-sm text-gray-500"> <?= $expense['note'] ?? '-' ?> </div>
                    </td>

                    <?php
                        $categories = [
                            'Food & Dining'   => ['icon' => 'fa-utensils',     'bg' => 'bg-red-100',      'text' => 'text-red-800'],
                            'Utilities'       => ['icon' => 'fa-lightbulb',    'bg' => 'bg-yellow-100',   'text' => 'text-yellow-800'],
                            'Transportation'  => ['icon' => 'fa-car',          'bg' => 'bg-blue-100',     'text' => 'text-blue-800'],
                            'Entertainment'   => ['icon' => 'fa-film',         'bg' => 'bg-purple-100',   'text' => 'text-purple-800'],
                            'Shopping'        => ['icon' => 'fa-shopping-cart', 'bg' => 'bg-rose-100',    'text' => 'text-rose-800'],
                            'Healthcare'      => ['icon' => 'fa-heartbeat',     'bg' => 'bg-green-100',   'text' => 'text-green-800'],
                            'Travel'          => ['icon' => 'fa-plane',         'bg' => 'bg-sky-100',  'text' => 'text-cyan-800'],
                            'Education'       => ['icon' => 'fa-book',          'bg' => 'bg-indigo-100',  'text' => 'text-indigo-800'],
                            'Bills & Payments' => ['icon' => 'fa-file-invoice-dollar', 'bg' => 'bg-orange-100', 'text' => 'text-orange-800'],
                            'Others'          => ['icon' => 'fa-ellipsis-h',     'bg' => 'bg-gray-100', 'text' => 'text-gray-800']
                        ];
                        // Normalize category name - trim and remove any non-printable characters
                        $cat = preg_replace('/[\x00-\x1F\x7F]/u', '', trim($expense['category_name'] ?? '-'));
                        $catInfo = ['icon' => 'fa-question', 'bg' => 'bg-gray-100', 'text' => 'text-gray-800'];
                        
                        // Try direct lookup first
                        if (isset($categories[$cat])) {
                            $catInfo = $categories[$cat];
                        } else {
                            // Case-insensitive lookup with normalized comparison
                            foreach ($categories as $key => $value) {
                                $normalizedKey = preg_replace('/[\x00-\x1F\x7F]/u', '', trim($key));
                                if (strcasecmp($normalizedKey, $cat) === 0) {
                                    $catInfo = $value;
                                    break;
                                }
                            }
                        }
                    ?>
                    
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex items-center text-xs leading-5 font-semibold rounded-full <?= htmlspecialchars($catInfo['bg']) ?> <?= htmlspecialchars($catInfo['text']) ?>">
                            <i class="fas <?= htmlspecialchars($catInfo['icon']) ?> mr-1"></i> <?= htmlspecialchars($cat) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?= $expense['payment_method'] == 'cash' ? '<i class="fas fa-money-bill-wave mr-1"></i>' : '' ?>
                        <?= $expense['payment_method'] == 'bank_transfer' ? '<i class="fas fa-university mr-1"></i>' : '' ?>
                        <?= $expense['payment_method'] == 'card' ? '<i class="fas fa-credit-card mr-1"></i>' : '' ?>
                        <?= $expense['payment_method'] == 'digital_wallet' ? '<i class="fa-solid fa-wallet mr-1"></i>' : '' ?>
                        <?= str_replace('_', ' ', ucwords($expense['payment_method'] ?? ''  )) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"> <?= number_format($expense['amount'] ?? '0', 0) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $expense['status'] == true ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>"> <?= $expense['status'] == true ? "Paid" : "Pending" ?> </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button onclick="openEditExpenseModal(this)" 
                            data-id="<?= $expense['id'] ?>"
                            data-date="<?= $expense['expense_date'] ?>"
                            data-amount="<?= $expense['amount'] ?>"
                            data-description="<?= $expense['description'] ?>"
                            data-category="<?= $expense['category_name']?>"
                            data-payment-method="<?= $expense['payment_method'] ?>"
                            data-note="<?= $expense['note'] ?>"
                            data-status="<?= $expense['status'] ?>"
                            class="text-indigo-600 hover:text-indigo-900 cursor-pointer mr-3"> 
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="openDeleteExpenseModal()" class="text-red-600 hover:text-red-900 cursor-pointer"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>