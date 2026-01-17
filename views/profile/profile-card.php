<div class="bg-white rounded-xl shadow-sm p-6 md:p-8">
  <?php 
    if ($editMode):
      include __DIR__ . '/edit.php';
    else: 
  ?>
    <div class="flex flex-col lg:flex-row items-start gap-8">
      <div class="shrink-0 text-center lg:text-left">
        <div class="relative inline-block">
          <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user_name'] ?? 'Guest') ?>&background=6366f1&color=fff&size=256&bold=true" 
            alt="Profile picture of <?= htmlspecialchars($_SESSION['user_name'] ?? 'Guest') ?>"
            class="w-40 h-40 rounded-full shadow-xl object-cover border-4 border-white ring-2 ring-indigo-100"
            loading="lazy"
          >
        </div>
        
        <!-- Edit Button - Desktop -->
        <div class="mt-6 lg:block hidden">
          <a href="<?= url('public/profile.php?edit') ?>" onclick="openEditModal()" class="w-full px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-all duration-200 font-medium flex items-center justify-center shadow-md hover:shadow-lg">
            <i class="fas fa-edit mr-2"></i> Edit Profile
          </a>
        </div>
      </div>

      <!-- Profile Details -->
      <div class="flex-1 w-full">
        <div class="flex justify-between items-start mb-6">
          <div>
            <h2 class="text-2xl font-bold text-gray-800">Account Information</h2>
            <p class="text-gray-600 text-sm mt-1">View and manage your account details</p>
          </div>
          
          <a href="<?= url('public/profile.php?edit') ?>" class="lg:hidden px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium flex items-center justify-center shadow-sm">
            <i class="fas fa-edit mr-2"></i> Edit
          </a>
        </div>
        
        <!-- Profile Information -->
        <div class="space-y-6">
          <div class="space-y-2">
            <div class="flex items-center text-gray-500 text-sm font-medium">
              <i class="fas fa-user-circle mr-2 text-indigo-500"></i>
              Username
            </div>
            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
              <p class="text-sm font-semibold text-gray-800">
                <?= htmlspecialchars($user['name'] ?? 'Guest') ?>
              </p>
            </div>
          </div>

          <!-- Email -->
          <div class="space-y-2">
            <div class="flex items-center text-gray-500 text-sm font-medium">
              <i class="fas fa-envelope mr-2 text-indigo-500"></i>
              Email Address
            </div>
            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
              <p class="text-sm font-semibold text-gray-800">
                <?= htmlspecialchars($user['email'] ?? 'No email provided') ?>
              </p>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-6 border-t border-gray-200">
            <div class="space-y-2">
              <div class="flex items-center text-gray-500 text-sm">
                <i class="far fa-calendar-alt mr-2 text-gray-400"></i>
                Member Since
              </div>
              <div class="p-3 bg-gray-50 rounded-lg">
                <p class="text-gray-700 font-medium">
                  <?= date('F Y', strtotime($user['created_at'] ?? 'now')) ?>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>