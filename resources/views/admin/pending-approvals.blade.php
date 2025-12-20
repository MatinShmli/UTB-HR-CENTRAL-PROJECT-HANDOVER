@extends('layouts.app')

@section('title', 'Pending Approvals - Admin Dashboard')

@section('page-title', 'Pending Approvals')

@section('content')
<div class="content-card">
    @if($pendingUsers->count() > 0)
        <div style="margin-bottom: 20px;">
            <h3>Pending User Approvals ({{ $pendingUsers->total() }})</h3>
            <p style="color: #6c757d;">Review and approve new user registrations</p>
        </div>
        
        @foreach($pendingUsers as $user)
        <div style="padding: 20px; border: 1px solid #e9ecef; border-radius: 8px; margin-bottom: 15px; background: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="flex: 1;">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <span style="margin-right: 15px; font-size: 24px;">👤</span>
                        <div>
                            <h4 style="margin: 0; color: #2c3e50;">{{ $user->name }}</h4>
                            <p style="margin: 5px 0 0; color: #6c757d;">{{ $user->email }}</p>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 15px;">
                        <div>
                            <strong style="color: #2c3e50;">Role:</strong>
                            <span style="color: #6c757d; margin-left: 5px;">{{ $user->role }}</span>
                        </div>
                        <div>
                            <strong style="color: #2c3e50;">Registered:</strong>
                            <span style="color: #6c757d; margin-left: 5px;">{{ $user->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div>
                            <strong style="color: #2c3e50;">Status:</strong>
                            <span style="color: #e74c3c; margin-left: 5px; font-weight: bold;">Pending Approval</span>
                        </div>
                    </div>
                </div>
                
                <div style="display: flex; gap: 10px; margin-left: 20px;">
                    <button onclick="approveUser({{ $user->id }})" style="background: #27ae60; color: white; border: none; padding: 12px 24px; border-radius: 5px; cursor: pointer; font-weight: 500;">✓ Approve</button>
                    <button onclick="rejectUser({{ $user->id }})" style="background: #e74c3c; color: white; border: none; padding: 12px 24px; border-radius: 5px; cursor: pointer; font-weight: 500;">✗ Reject</button>
                </div>
            </div>
        </div>
        @endforeach
        
        <div style="margin-top: 30px; text-align: center;">
            {{ $pendingUsers->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 40px;">
            <span style="font-size: 48px; margin-bottom: 20px; display: block;">✅</span>
            <h3 style="color: #2c3e50; margin-bottom: 10px;">No Pending Approvals</h3>
            <p style="color: #6c757d;">All user registrations have been processed.</p>
        </div>
    @endif
</div>
@endsection

<script>
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

function rejectUser(userId) {
    if (confirm('Are you sure you want to reject this user? This action cannot be undone.')) {
        fetch(`/admin/reject-user/${userId}`, {
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
</script> 