<div class="mb-8">
  <h1 class="text-2xl font-bold text-gray-900">Spending Insights</h1>
  <p class="mt-2 text-gray-600">Analyze your spending patterns and get personalized savings tips for <?= date('F Y') ?>.</p>
</div>

<?php if (empty($categorySpending)): ?>
  <div class="bg-white rounded-lg shadow p-6">
    <p class="text-gray-500">No expenses recorded for this month yet.</p>
  </div>
<?php else: ?>
  <!-- Spending Overview -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-2">Total Spent</h3>
      <p class="text-3xl font-bold text-blue-600"><?= number_format($totalSpent, 2) ?> MMK</p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-2">Categories</h3>
      <p class="text-3xl font-bold text-green-600"><?= count($categorySpending) ?></p>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-2">Top Category</h3>
      <p class="text-lg font-semibold text-purple-600"><?= $categorySpending[0]['name'] ?? 'N/A' ?></p>
      <p class="text-sm text-gray-500"><?= number_format($categorySpending[0]['total_spent'] ?? 0, 2) ?> MMK</p>
    </div>
  </div>

  <!-- Category Spending Chart -->
  <div class="bg-white rounded-lg shadow p-6 mb-8">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Spending by Category</h2>
    <div class="h-64 md:h-72">
      <canvas id="spendingChart" class="w-full h-full"></canvas>
    </div>
  </div>

  <!-- Detailed Insights -->
  <div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold text-gray-900 mb-4">Detailed Insights & Tips</h2>
    <div class="space-y-6">
      <?php foreach ($insights as $insight): ?>
        <div class="border-b border-gray-200 pb-6 last:border-b-0">
          <div class="flex items-center justify-between mb-2">
            <h3 class="text-lg font-medium text-gray-900"><?= htmlspecialchars($insight['category']) ?></h3>
            <div class="flex items-center space-x-4">
              <span class="text-sm text-gray-500"><?= $insight['percentage'] ?>% of total</span>
              <?php if ($insight['over_budget']): ?>
                <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Over Budget</span>
              <?php endif; ?>
            </div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
            <div>
              <p class="text-sm text-gray-500">Spent</p>
              <p class="text-lg font-semibold text-gray-900"><?= number_format($insight['spent'], 2) ?> MMK</p>
            </div>
            <?php if ($insight['budget']): ?>
              <div>
                <p class="text-sm text-gray-500">Budget</p>
                <p class="text-lg font-semibold text-gray-900"><?= number_format($insight['budget'], 2) ?> MMK</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Remaining</p>
                <p class="text-lg font-semibold <?= $insight['spent'] > $insight['budget'] ? 'text-red-600' : 'text-green-600' ?>">
                  <?= number_format($insight['budget'] - $insight['spent'], 2) ?> MMK
                </p>
              </div>
            <?php else: ?>
              <div class="md:col-span-2">
                <p class="text-sm text-gray-500">No budget set</p>
              </div>
            <?php endif; ?>
          </div>
          <?php if (!empty($insight['suggestions'])): ?>
            <div class="bg-blue-50 rounded-lg p-4">
              <h4 class="text-sm font-medium text-blue-900 mb-2">Savings Tips:</h4>
              <ul class="list-disc list-inside space-y-1">
                <?php foreach ($insight['suggestions'] as $suggestion): ?>
                  <li class="text-sm text-blue-800"><?= htmlspecialchars($suggestion) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php endif; ?>

<script>
  <?php if (!empty($categorySpending)): ?>
    document.addEventListener('DOMContentLoaded', () => {
      if (!window.Chart) {
        return;
      }

      const canvas = document.getElementById('spendingChart');
      if (!canvas) {
        return;
      }

      const ctx = canvas.getContext('2d');
      const spendingChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: <?= json_encode(array_column($categorySpending, 'name')) ?>,
          datasets: [{
            data: <?= json_encode(array_column($categorySpending, 'total_spent')) ?>,
            backgroundColor: [
              '#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6',
              '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6B7280'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom',
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  const total = context.dataset.data.reduce((a, b) => a + b, 0);
                  const percentage = ((context.parsed / total) * 100).toFixed(1);
                  return context.label + ': ' + context.parsed.toFixed(2) + ' MMK (' + percentage + '%)';
                }
              }
            }
          }
        }
      });
    });
  <?php endif; ?>
</script>
