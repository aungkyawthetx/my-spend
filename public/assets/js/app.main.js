// Monthly Expenses Chart
const monthlyExpensesCtx = document.getElementById('monthlyExpensesChart').getContext('2d');
const monthlyExpensesChart = new Chart(monthlyExpensesCtx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        datasets: [{
            label: 'Expenses',
            data: [100000, 190000, 150000, 200000, 180000, 220000, 170000],
            backgroundColor: '#6366F1',
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    drawBorder: false
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Expense Breakdown Chart
const expenseBreakdownCtx = document.getElementById('expenseBreakdownChart').getContext('2d');
const expenseBreakdownChart = new Chart(expenseBreakdownCtx, {
    type: 'doughnut',
    data: {
        labels: ['Food', 'Transportation', 'Utilities', 'Entertainment', 'Shopping', 'Others'],
        datasets: [{
            data: [25, 15, 20, 10, 20, 10],
            backgroundColor: [
                '#6366F1',
                '#10B981',
                '#F59E0B',
                '#8B5CF6',
                '#EF4444',
                '#64748B'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'right'
            }
        },
        cutout: '70%'
    }
});
// categories
function openEditCategoryModal(btn) {
  document.getElementById('edit_category_id').value = btn.dataset.id;
  document.getElementById('categoryName').value = btn.dataset.name;
  document.getElementById('monthlyBudget').value = btn.dataset.budget;
  document.getElementById('editCategoryModal').classList.remove('hidden');
}
function closeEditCategoryModal() {
    document.getElementById('editCategoryModal').classList.add('hidden');
}
// expenses
// Modal functions
function openAddExpenseModal() {
    document.getElementById('expenseModal').classList.remove('hidden');
}

function closeAddExpenseModal() {
    document.getElementById('expenseModal').classList.add('hidden');
}

function openEditExpenseModal(btn) {
  console.log(btn.dataset);
  document.getElementById('edit_expense_id').value = btn.dataset.id;
  document.getElementById('edit_expense_date').value = btn.dataset.date;
  document.getElementById('edit_amount').value = btn.dataset.amount;
  document.getElementById('edit_category').value = btn.dataset.category;
  document.getElementById('edit_payment_method').value = btn.dataset.paymentMethodId;
  document.getElementById('edit_note').value = btn.dataset.note;
  document.getElementById('edit_status').checked = btn.dataset.status === '1';
  
  document.getElementById('editExpenseModal').classList.remove('hidden');
}

function closeEditExpenseModal() {
    document.getElementById('editExpenseModal').classList.add('hidden');
}

function openDeleteExpenseModal(id) {
    document.getElementById('delete-id').value = id;
    document.getElementById('deleteExpenseModal').classList.remove('hidden');
}

function closeDeleteExpenseModal() {
    document.getElementById('deleteExpenseModal').classList.add('hidden');
}
// reports
// Expense Trend Chart
const trendCtx = document.getElementById('expenseTrendChart').getContext('2d');
const expenseTrendChart = new Chart(trendCtx, {
  type: 'line',
  data: {
    labels: ['Jun 1', 'Jun 5', 'Jun 10', 'Jun 15', 'Jun 20', 'Jun 25', 'Jun 30'],
    datasets: [{
      label: 'Daily Expenses',
      data: [85, 120, 95, 245, 180, 150, 90],
      backgroundColor: 'rgba(99, 102, 241, 0.1)',
      borderColor: '#6366F1',
      borderWidth: 2,
      tension: 0.3,
      fill: true
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: false
      },
      tooltip: {
        mode: 'index',
        intersect: false
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        grid: {
          drawBorder: false
        }
      },
      x: {
        grid: {
          display: false
        }
      }
    }
  }
});

// Category Distribution Chart
const categoryCtx = document.getElementById('categoryDistributionChart').getContext('2d');
const categoryDistributionChart = new Chart(categoryCtx, {
  type: 'doughnut',
  data: {
    labels: ['Food & Dining', 'Shopping', 'Transportation', 'Utilities', 'Entertainment', 'Others'],
    datasets: [{
      data: [1245, 845, 520, 320, 215, 100],
      backgroundColor: [
        '#EF4444',
        '#10B981',
        '#3B82F6',
        '#F59E0B',
        '#8B5CF6',
        '#64748B'
      ],
      borderWidth: 0
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: 'right',
        labels: {
          boxWidth: 12,
          padding: 20
        }
      }
    },
    cutout: '70%'
  }
});