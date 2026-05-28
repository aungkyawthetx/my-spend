<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Spent</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Note</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if(count($expenses) > 0 ): ?>
                    <?php foreach($expenses as $expense): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"> <?= date("M j, Y", strtotime($expense['expense_date'] ?? '')) ?> </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"> <?= htmlspecialchars($expense['note'] ?? '-') ?> </div>
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
                                <?= htmlspecialchars($expense['payment_method'] ?? '') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"> <?= number_format($expense['amount'] ?? '0', 0) ?> Ks</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                    $expenseStatus = strtolower(trim((string) ($expense['status'] ?? '')));
                                    $isPaid = in_array($expenseStatus, ['1', 'true', 'paid', 'yes', 'on'], true);
                                ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $isPaid ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>"> <?= $isPaid ? "Paid" : "Pending" ?> </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick="openEditExpenseModal(this)" 
                                    data-id="<?= (int) ($expense['id'] ?? 0) ?>"
                                    data-date="<?= htmlspecialchars($expense['expense_date'] ?? '') ?>"
                                    data-amount="<?= htmlspecialchars((string) ($expense['amount'] ?? '')) ?>"
                                    data-category="<?= (int) ($expense['category_id'] ?? 0) ?>"
                                    data-payment-method-id="<?= (int) ($expense['payment_method_id'] ?? 0) ?>"
                                    data-note="<?= htmlspecialchars($expense['note'] ?? '') ?>"
                                    data-status="<?= ($expense['status'] ?? '') ?>"
                                    class="text-indigo-600 hover:text-indigo-900 cursor-pointer mr-3"> 
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="openDeleteExpenseModal(<?= (int) ($expense['id'] ?? 0) ?>)"
                                    class="text-red-600 hover:text-red-900 cursor-pointer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center"> No expenses found. </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if ($totalExpenses > 0): ?>
        <?php
            $pageWindowStart = max(1, $currentPage - 2);
            $pageWindowEnd = min($totalPages, $currentPage + 2);

            if ($pageWindowEnd - $pageWindowStart < 4) {
                $pageWindowStart = max(1, $pageWindowEnd - 4);
                $pageWindowEnd = min($totalPages, $pageWindowStart + 4);
            }
        ?>
        <div class="flex flex-col gap-3 border-t border-gray-200 px-4 py-3 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <p class="text-sm text-gray-600">
                Showing
                <span class="font-medium text-gray-900"><?= number_format($paginationStart) ?></span>
                to
                <span class="font-medium text-gray-900"><?= number_format($paginationEnd) ?></span>
                of
                <span class="font-medium text-gray-900"><?= number_format($totalExpenses) ?></span>
                expenses
            </p>
            <nav class="inline-flex items-center justify-end gap-1" aria-label="Expense pagination">
                <?php if ($currentPage > 1): ?>
                    <a href="<?= htmlspecialchars($paginationUrl($currentPage - 1)) ?>" class="inline-flex h-9 min-w-9 items-center justify-center rounded-md border border-gray-300 px-3 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <i class="fa-solid fa-chevron-left mr-1 text-xs"></i>
                        Previous
                    </a>
                <?php else: ?>
                    <span class="inline-flex h-9 min-w-9 cursor-not-allowed items-center justify-center rounded-md border border-gray-200 px-3 text-sm font-medium text-gray-400">
                        <i class="fa-solid fa-chevron-left mr-1 text-xs"></i>
                        Previous
                    </span>
                <?php endif; ?>

                <?php if ($pageWindowStart > 1): ?>
                    <a href="<?= htmlspecialchars($paginationUrl(1)) ?>" class="hidden h-9 min-w-9 items-center justify-center rounded-md border border-gray-300 px-3 text-sm font-medium text-gray-700 hover:bg-gray-50 sm:inline-flex">1</a>
                    <?php if ($pageWindowStart > 2): ?>
                        <span class="hidden h-9 min-w-9 items-center justify-center px-2 text-sm text-gray-500 sm:inline-flex">...</span>
                    <?php endif; ?>
                <?php endif; ?>

                <?php for ($page = $pageWindowStart; $page <= $pageWindowEnd; $page++): ?>
                    <?php if ($page === $currentPage): ?>
                        <span class="hidden h-9 min-w-9 items-center justify-center rounded-md border border-blue-600 bg-blue-600 px-3 text-sm font-semibold text-white sm:inline-flex" aria-current="page"><?= $page ?></span>
                    <?php else: ?>
                        <a href="<?= htmlspecialchars($paginationUrl($page)) ?>" class="hidden h-9 min-w-9 items-center justify-center rounded-md border border-gray-300 px-3 text-sm font-medium text-gray-700 hover:bg-gray-50 sm:inline-flex"><?= $page ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($pageWindowEnd < $totalPages): ?>
                    <?php if ($pageWindowEnd < $totalPages - 1): ?>
                        <span class="hidden h-9 min-w-9 items-center justify-center px-2 text-sm text-gray-500 sm:inline-flex">...</span>
                    <?php endif; ?>
                    <a href="<?= htmlspecialchars($paginationUrl($totalPages)) ?>" class="hidden h-9 min-w-9 items-center justify-center rounded-md border border-gray-300 px-3 text-sm font-medium text-gray-700 hover:bg-gray-50 sm:inline-flex"><?= $totalPages ?></a>
                <?php endif; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <a href="<?= htmlspecialchars($paginationUrl($currentPage + 1)) ?>" class="inline-flex h-9 min-w-9 items-center justify-center rounded-md border border-gray-300 px-3 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Next
                        <i class="fa-solid fa-chevron-right ml-1 text-xs"></i>
                    </a>
                <?php else: ?>
                    <span class="inline-flex h-9 min-w-9 cursor-not-allowed items-center justify-center rounded-md border border-gray-200 px-3 text-sm font-medium text-gray-400">
                        Next
                        <i class="fa-solid fa-chevron-right ml-1 text-xs"></i>
                    </span>
                <?php endif; ?>
            </nav>
        </div>
    <?php endif; ?>
</div>
