// Initialize date pickers
flatpickr("#date-range", {
    mode: "range",
    dateFormat: "Y-m-d",
});

flatpickr("#expenseDate", {
    dateFormat: "Y-m-d",
    defaultDate: "today"
});

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
    document.getElementById('edit_description').value = btn.dataset.description;
    document.getElementById('edit_category').value = btn.dataset.category;
    document.getElementById('edit_payment_method').value = btn.dataset.paymentMethod;
    document.getElementById('edit_note').value = btn.dataset.note;
    document.getElementById('edit_status').checked = btn.dataset.status === '1';
    
    document.getElementById('editExpenseModal').classList.remove('hidden');
}

function closeEditExpenseModal() {
    document.getElementById('editExpenseModal').classList.add('hidden');
}

function openDeleteExpenseModal() {
    document.getElementById('deleteExpenseModal').classList.remove('hidden');
}

function closeDeleteExpenseModal() {
    document.getElementById('deleteExpenseModal').classList.add('hidden');
}