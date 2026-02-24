<div class="hidden md:flex md:shrink-0">
  <div class="flex flex-col w-64 bg-blue-800">
    <div class="bg-blue-900 p-1">
      <a href="<?= url('/') ?>" class="flex items-center space-x-2">
        <img src="/public/assets/favicon.png" alt="TraceX favicon" class="h-14 w-14 rounded-md object-contain">
        <p class="text-white font-bold text-2xl">TraceX</p>
      </a>
    </div>
    <div class="flex flex-col flex-1 px-4 py-4 overflow-y-auto">
      <div class="space-y-1">
        <a href="<?= url('public/index.php') ?>" class="flex items-center px-2 py-3 text-sm font-medium <?= isActive('public/index.php') ? 'text-white bg-blue-700' : 'text-indigo-200 hover:text-white hover:bg-blue-700' ?> rounded-lg">
          <i class="fas fa-house mr-3"></i>
          Dashboard
        </a>
        <a href="<?= url('public/expenses.php') ?>" class="flex items-center px-2 py-3 text-sm font-medium <?= isActive('public/expenses.php') ? 'text-white bg-blue-700' : 'text-indigo-200 hover:text-white hover:bg-blue-700' ?> rounded-lg">
          <i class="fa-solid fa-dollar-sign mr-3"></i>
          Expenses
        </a>
        <a href="<?= url('public/categories.php') ?>" class="flex items-center px-2 py-3 text-sm font-medium <?= isActive('public/categories.php') ? 'text-white bg-blue-700' : 'text-indigo-200 hover:text-white hover:bg-blue-700' ?> rounded-lg">
          <i class="fas fa-tags mr-3"></i>
          Categories
        </a>
        <a href="<?= url('public/savings.php') ?>" class="flex items-center px-2 py-3 text-sm font-medium <?= isActive('public/savings.php') ? 'text-white bg-blue-700' : 'text-indigo-200 hover:text-white hover:bg-blue-700' ?> rounded-lg">
          <i class="fas fa-piggy-bank mr-3"></i>
          Savings
        </a>
        <a href="<?= url('public/budgets.php') ?>" class="flex items-center px-2 py-3 text-sm font-medium <?= isActive('public/budgets.php') ? 'text-white bg-blue-700' : 'text-indigo-200 hover:text-white hover:bg-blue-700' ?> rounded-lg">
          <i class="fas fa-wallet mr-3"></i>
          Budgets
        </a>
      </div>
    </div>
  </div>
</div>

<div id="mobile-sidebar" class="fixed inset-0 z-40 md:hidden pointer-events-none">
  <div id="mobile-sidebar-backdrop" class="absolute inset-0 bg-black/40 opacity-0 transition-opacity duration-300 ease-out"></div>
  <aside id="mobile-sidebar-panel" class="absolute left-0 top-0 h-full w-64 bg-blue-800 transform -translate-x-full transition-transform duration-300 ease-out shadow-xl">
    <div class="flex items-center justify-between gap-2 bg-blue-900 p-3">
      <a href="<?= url('/') ?>" class="mobile-sidebar-link min-w-0 flex items-center">
        <img src="/public/assets/favicon.png" alt="TraceX favicon" class="h-8 w-8 rounded-md object-contain">
        <p class="text-white ms-2 font-bold text-2xl">TraceX</p>
      </a>
      <button id="mobile-sidebar-close" type="button" class="shrink-0 inline-flex h-8 w-8 items-center justify-center rounded-md text-white/90 hover:bg-blue-800 hover:text-white cursor-pointer">
        <i class="fas fa-xmark text-lg"></i>
      </button>
    </div>
    <div class="flex flex-col px-4 py-4 overflow-y-auto">
      <div class="space-y-1">
        <a href="<?= url('public/index.php') ?>" class="mobile-sidebar-link flex items-center px-2 py-3 text-sm font-medium <?= isActive('public/index.php') ? 'text-white bg-blue-700' : 'text-indigo-200 hover:text-white hover:bg-blue-700' ?> rounded-lg">
          <i class="fas fa-house mr-3"></i>
          Dashboard
        </a>
        <a href="<?= url('public/expenses.php') ?>" class="mobile-sidebar-link flex items-center px-2 py-3 text-sm font-medium <?= isActive('public/expenses.php') ? 'text-white bg-blue-700' : 'text-indigo-200 hover:text-white hover:bg-blue-700' ?> rounded-lg">
          <i class="fa-solid fa-dollar-sign mr-3"></i>
          Expenses
        </a>
        <a href="<?= url('public/categories.php') ?>" class="mobile-sidebar-link flex items-center px-2 py-3 text-sm font-medium <?= isActive('public/categories.php') ? 'text-white bg-blue-700' : 'text-indigo-200 hover:text-white hover:bg-blue-700' ?> rounded-lg">
          <i class="fas fa-tags mr-3"></i>
          Categories
        </a>
        <a href="<?= url('public/savings.php') ?>" class="mobile-sidebar-link flex items-center px-2 py-3 text-sm font-medium <?= isActive('public/savings.php') ? 'text-white bg-blue-700' : 'text-indigo-200 hover:text-white hover:bg-blue-700' ?> rounded-lg">
          <i class="fas fa-piggy-bank mr-3"></i>
          Savings
        </a>
        <a href="<?= url('public/budgets.php') ?>" class="mobile-sidebar-link flex items-center px-2 py-3 text-sm font-medium <?= isActive('public/budgets.php') ? 'text-white bg-blue-700' : 'text-indigo-200 hover:text-white hover:bg-blue-700' ?> rounded-lg">
          <i class="fas fa-wallet mr-3"></i>
          Budgets
        </a>
      </div>
    </div>
  </aside>
</div>
