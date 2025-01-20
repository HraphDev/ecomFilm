// Initialize admins array from localStorage or set default admin
let admins = JSON.parse(localStorage.getItem('admins')) || [{
  email: 'admin@admin.com',
  password: 'admin123',
  role: 'Admin',
  active: true
}];

// Initialize modal on page load
let editModal;
document.addEventListener('DOMContentLoaded', () => {
  renderUsers();
  editModal = new bootstrap.Modal(document.getElementById('editModal'));
});

// Render users table
function renderUsers() {
  const tbody = document.getElementById('adminTableBody');
  if (!tbody) return;

  tbody.innerHTML = admins.map((admin, index) => `
      <tr class="bg-gray-800 border-b border-gray-700">
          <td class="px-6 py-4 text-white">${admin.email}</td>
          <td class="px-6 py-4 text-white">${admin.role}</td>
          <td class="px-6 py-4">
              <span class="px-2 py-1 rounded text-white ${admin.active ? 'bg-green-500' : 'bg-red-500'}">
                  ${admin.active ? 'Active' : 'Inactive'}
              </span>
          </td>
          <td class="px-6 py-4 text-white">
              <button onclick="editUser(${index})" class="text-blue-500 hover:text-blue-700 mr-2">
                  <i class="fas fa-edit"></i> 
              </button>
              <button onclick="deleteUser(${index})" class="text-red-600 hover:text-red-700">
                  <i class="fas fa-trash"></i> 
              </button>
          </td>
      </tr>
  `).join('');
}

// Add new user
function addUser() {
  const email = document.getElementById('newEmail').value;
  const password = document.getElementById('newPassword').value;
  const role = document.getElementById('newRole').value;

  if (!email || !password || !role) {
      alert('Please fill all fields');
      return;
  }

  admins.push({
      email,
      password,
      role,
      active: true
  });

  localStorage.setItem('admins', JSON.stringify(admins));
  renderUsers();
  document.getElementById('addUserForm').reset();
}

// Edit user
function editUser(index) {
  const admin = admins[index];
  
  document.getElementById('editForm').innerHTML = `
      <div class="mb-4">
          <label class="block bg-gray-700 text-black w-full text-sm font-bold mb-2">Email</label>
          <input type="email" id="editEmail" class="  bg-gray-700 text-black w-fullform-inputtext-blackw-full" value="${admin.email}">
      </div>
      <div class="mb-4">
          <label class="block bg-gray-700 text-black w-full text-sm font-bold mb-2">New Password</label>
          <input type="password" id="editPassword" class="form-input bg-gray-700 text-black w-full" placeholder="Leave blank to keep current">
      </div>
      <div class="mb-4">
          <label class="block text-sm font-bold mb-2  bg-gray-700 text-black w-full">Role</label>
          <select id="editRole" class="form-select bg-gray-700 text-black w-full">
              <option value="Super Admin" ${admin.role === 'Super Admin' ? 'selected' : ''}>Super Admin</option>
              <option value="Admin" ${admin.role === 'Admin' ? 'selected' : ''}>Admin</option>
              <option value="Developer" ${admin.role === 'Developer' ? 'selected' : ''}>Developer</option>
          </select>
      </div>
      <div class="mb-4">
          <label class="block text-black text-sm font-bold mb-2">Active Status</label>
          <input type="checkbox" id="editActive" class="form-checkbox" ${admin.active ? 'checked' : ''}>
      </div>
  `;

  editModal.show();

  document.getElementById('saveChanges').onclick = () => {
      try {
          admins[index] = {
              email: document.getElementById('editEmail').value,
              password: document.getElementById('editPassword').value || admin.password,
              role: document.getElementById('editRole').value,
              active: document.getElementById('editActive').checked
          };

          localStorage.setItem('admins', JSON.stringify(admins));
          editModal.hide();
          renderUsers();
      } catch (error) {
          console.error('Error updating admin:', error);
          alert('Failed to update admin');
      }
  };
}

// Delete user
function deleteUser(index) {
  if (!confirm('Are you sure you want to delete this user?')) return;
  
  admins.splice(index, 1);
  localStorage.setItem('admins', JSON.stringify(admins));
  renderUsers();
}

