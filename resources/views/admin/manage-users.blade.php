@extends('layouts.app')

@section('title', 'Manage Users - Admin Dashboard')

@section('page-title', 'Manage Users')

@section('content')
<div class="content-card">
    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <div>
            <h3 style="margin-bottom: 5px;">All Users ({{ $userCount }})</h3>
            <p style="color: #6c757d; margin: 0;">Manage user accounts, roles, and approval status</p>
        </div>
        <form method="GET" action="" style="display: flex; gap: 8px; align-items: center;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email" style="padding: 7px 12px; border: 1px solid #ccc; border-radius: 4px; min-width: 220px;">
            <button type="submit" style="padding: 7px 16px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer;">Search</button>
            @if(request('search'))
                <a href="{{ route('admin.manage-users') }}" style="margin-left: 5px; color: #e74c3c; text-decoration: underline; font-size: 14px;">Clear</a>
            @endif
        </form>
    </div>
    
    <style>
.user-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 12px;
    margin-top: 18px;
}
.user-table th, .user-table td {
    padding: 14px 12px;
    text-align: left;
}
.user-table th {
    background: #f8f9fa;
    color: #2c3e50;
    font-size: 15px;
    font-weight: 700;
    border-bottom: 2px solid #e9ecef;
}
.user-table tr {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(44,62,80,0.04);
}
.user-table td {
    font-size: 15px;
    color: #34495e;
    vertical-align: middle;
}
.user-table tr td:first-child {
    border-radius: 10px 0 0 10px;
}
.user-table tr td:last-child {
    border-radius: 0 10px 10px 0;
}
.user-actions {
    display: flex;
    gap: 8px;
    align-items: center;
}
.user-actions button, .user-actions span {
    margin: 0;
}
.user-role-select {
    min-width: 120px;
}
@media (max-width: 900px) {
    .user-table th, .user-table td { font-size: 13px; padding: 10px 6px; }
    .user-role-select { min-width: 90px; }
}
</style>

<table class="user-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Registered</th>
            <th>Last Login</th>
            <th style="text-align:center;">Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach($users as $user)
        <tr data-user-row-id="{{ $user->id }}">
            <td><span class="user-full-name">{{ $user->first_name }} {{ $user->last_name }}</span></td>
            <td><span class="user-email">{{ $user->email }}</span></td>
            <td>
                <select class="user-role-select" onchange="updateUserRole({{ $user->id }}, this.value)" @if($user->id === auth()->id()) disabled @endif>
                    <option value="Staff Member" {{ $user->role === 'Staff Member' ? 'selected' : '' }}>Staff Member</option>
                    <option value="Administrator" {{ $user->role === 'Administrator' ? 'selected' : '' }} @if($user->id !== auth()->id()) disabled @endif>Administrator</option>
                    <option value="Head Of Section" {{ $user->role === 'Head Of Section' ? 'selected' : '' }}>Head Of Section</option>
                </select>
            </td>
            <td>
                <span style="color: {{ $user->is_approved ? '#27ae60' : '#e74c3c' }}; font-weight: bold;">
                    {{ $user->is_approved ? 'Approved' : 'Pending' }}
                </span>
            </td>
            <td>{{ $user->created_at->format('M d, Y H:i') }}</td>
            <td>{{ $user->updated_at->format('M d, Y H:i') }}</td>
            <td style="text-align:center;">
                <div class="user-actions">
                    @if($user->id !== auth()->id())
                        <button onclick="showEditModal({{ $user->id }}, '{{ addslashes($user->first_name) }}', '{{ addslashes($user->last_name) }}', '{{ addslashes($user->email) }}')" style="background: #f39c12; color: white; border: none; padding: 7px 14px; border-radius: 5px; cursor: pointer; font-size: 13px; font-weight:600;">✎ Edit</button>
                    @endif
                    @if(!$user->is_approved)
                        <button onclick="approveUser({{ $user->id }})" style="background: #27ae60; color: white; border: none; padding: 7px 14px; border-radius: 5px; cursor: pointer; font-size: 13px; font-weight:600;">✓ Approve</button>
                    @endif
                    @if($user->id !== auth()->id())
                        <button onclick="deleteUser({{ $user->id }})" style="background: #e74c3c; color: white; border: none; padding: 7px 14px; border-radius: 5px; cursor: pointer; font-size: 13px; font-weight:600;">🗑️ Delete</button>
                    @else
                        <span style="color: #6c757d; font-size: 12px;">Current User</span>
                    @endif
                </div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<div style="margin-top: 30px; text-align: center;">
    {{ $users->links() }}
</div>
</div>

<!-- Toast Notification -->
<div id="toastNotification" style="display:none; opacity:0; position:fixed; top:30px; left:50%; transform:translateX(-50%); background:#27ae60; color:white; padding:16px 32px; border-radius:8px; box-shadow:0 4px 16px rgba(0,0,0,0.12); font-size:16px; z-index:99999; min-width:220px; text-align:center; font-weight:500; letter-spacing:0.5px; transition:opacity 0.4s; pointer-events:none;"></div>

<!-- Edit User Modal -->
<div id="editUserModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.18); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:18px; max-width:410px; width:92%; margin:auto; padding:36px 32px 28px 32px; position:relative; box-shadow:0 8px 32px rgba(44,62,80,0.18);">
        <button onclick="closeEditModal()" style="position:absolute; top:18px; right:22px; background:none; border:none; font-size:26px; color:#b2bec3; cursor:pointer; transition:color 0.2s;" onmouseover="this.style.color='#e74c3c'" onmouseout="this.style.color='#b2bec3'">&times;</button>
        <h2 style="margin-bottom:22px; font-size:22px; color:#2c3e50; font-weight:700; text-align:center;">Edit User Details</h2>
        <form id="editUserForm" autocomplete="off">
            <input type="hidden" id="edit_user_id">
            <div class="form-group" style="margin-bottom:18px;">
                <label for="edit_first_name" style="font-weight:600; color:#34495e; margin-bottom:6px; display:block;">First Name</label>
                <input type="text" id="edit_first_name" name="first_name" class="form-input" required style="width:100%; padding:10px 14px; border-radius:7px; border:1.5px solid #dfe6e9; font-size:16px;">
            </div>
            <div class="form-group" style="margin-bottom:18px;">
                <label for="edit_last_name" style="font-weight:600; color:#34495e; margin-bottom:6px; display:block;">Last Name</label>
                <input type="text" id="edit_last_name" name="last_name" class="form-input" required style="width:100%; padding:10px 14px; border-radius:7px; border:1.5px solid #dfe6e9; font-size:16px;">
            </div>
            <div class="form-group" style="margin-bottom:18px;">
                <label for="edit_email" style="font-weight:600; color:#34495e; margin-bottom:6px; display:block;">Email</label>
                <input type="email" id="edit_email" name="email" class="form-input" required style="width:100%; padding:10px 14px; border-radius:7px; border:1.5px solid #dfe6e9; font-size:16px;">
            </div>
            <div style="display:flex; gap:12px; margin-top:18px;">
                <button type="submit" class="btn" style="flex:1; background:#27ae60; color:white; font-weight:600; font-size:16px; border:none; border-radius:7px; padding:12px 0; transition:background 0.2s; cursor:pointer;">Save</button>
                <button type="button" onclick="closeEditModal()" class="btn" style="flex:1; background:#b2bec3; color:#2d3436; font-weight:600; font-size:16px; border:none; border-radius:7px; padding:12px 0; transition:background 0.2s; cursor:pointer;">Cancel</button>
            </div>
            <div id="editUserError" style="color:#e74c3c; margin-top:14px; display:none; text-align:center;"></div>
        </form>
    </div>
</div>
@endsection

<script>
function updateUserRole(userId, newRole) {
    fetch(`/admin/update-user-role/${userId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            role: newRole
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
        } else {
            alert('Error: ' + data.message);
        }
    });
}

function approveUser(userId) {
    if (confirm('Are you sure you want to approve this user?')) {
        fetch(`/admin/approve-user/${userId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        fetch(`/admin/delete-user/${userId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        });
    }
}

function showEditModal(id, firstName, lastName, email) {
    document.getElementById('edit_user_id').value = id;
    document.getElementById('edit_first_name').value = firstName;
    document.getElementById('edit_last_name').value = lastName;
    document.getElementById('edit_email').value = email;
    document.getElementById('editUserError').style.display = 'none';
    document.getElementById('editUserModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeEditModal() {
    document.getElementById('editUserModal').style.display = 'none';
    document.body.style.overflow = '';
}
document.addEventListener('DOMContentLoaded', function() {
    var editForm = document.getElementById('editUserForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var id = document.getElementById('edit_user_id').value;
            var firstName = document.getElementById('edit_first_name').value.trim();
            var lastName = document.getElementById('edit_last_name').value.trim();
            var email = document.getElementById('edit_email').value.trim();
            var errorDiv = document.getElementById('editUserError');
            errorDiv.style.display = 'none';
            fetch(`/admin/update-user-details/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    first_name: firstName,
                    last_name: lastName,
                    email: email
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('AJAX response for edit user:', data);
                if (data.success) {
                    console.log('About to show toast...');
                    // Update the user row in the table without reload
                    try {
                        var row = document.querySelector(`[data-user-row-id='${id}']`);
                        if (row) {
                            row.querySelector('.user-full-name').textContent = firstName + ' ' + lastName;
                            row.querySelector('.user-email').textContent = email;
                        }
                    } catch (e) {
                        console.error('Error updating row:', e);
                    }
                    showToast('User Successfully Edited');
                    console.log('Toast should be visible now.');
                    closeEditModal();
                } else {
                    errorDiv.textContent = data.message;
                    errorDiv.style.display = 'block';
                }
            })
            .catch(() => {
                errorDiv.textContent = 'An error occurred. Please try again.';
                errorDiv.style.display = 'block';
            });
        });
    }
});
function showToast(message) {
    console.log('showToast called with:', message);
    var toast = document.getElementById('toastNotification');
    toast.textContent = message;
    toast.style.display = 'block';
    setTimeout(function() {
        toast.style.opacity = '1';
    }, 10);
    setTimeout(function() {
        toast.style.opacity = '0';
    }, 2200);
    setTimeout(function() {
        toast.style.display = 'none';
    }, 2600);
}
</script> 