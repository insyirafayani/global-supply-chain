<x-dashboard-layout>
    <style>
        .profile-header h3 {
            color: #FFFFFF;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .profile-header p {
            color: #94A3B8;
            font-size: 14px;
            margin-bottom: 32px;
        }

        .gerip-card {
            background-color: #111827;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 18px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.30);
            padding: 30px;
            margin-bottom: 24px;
            transition: all 0.3s ease;
        }
        
        .gerip-card-danger {
            background-color: rgba(220, 38, 38, 0.05);
            border: 1px solid rgba(220, 38, 38, 0.2);
        }

        .gerip-card:hover {
            box-shadow: 0 25px 50px rgba(0,0,0,0.40);
        }

        .gerip-input {
            background-color: #0F172A;
            border: 1px solid rgba(255,255,255,0.08);
            color: #FFFFFF;
            border-radius: 10px;
            padding: 12px 16px;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .gerip-input::placeholder {
            color: #64748B;
        }

        .gerip-input:focus {
            border-color: #38BDF8;
            box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.25);
            outline: none;
            background-color: #0F172A;
            color: #FFFFFF;
        }

        .gerip-label {
            color: #CBD5E1;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
        }

        .gerip-btn {
            background: linear-gradient(135deg, #2563eb, #38bdf8);
            color: #FFFFFF;
            border: none;
            border-radius: 10px;
            padding: 10px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .gerip-btn:hover {
            background: linear-gradient(135deg, #3b82f6, #7dd3fc);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
            color: #FFFFFF;
        }
        
        .gerip-btn-danger {
            background: #DC2626;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }
        .gerip-btn-danger:hover {
            background: #EF4444;
            box-shadow: 0 6px 16px rgba(220, 38, 38, 0.4);
        }

        .card-title {
            color: #FFFFFF;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .card-subtitle {
            color: #94A3B8;
            font-size: 13px;
            margin-bottom: 24px;
        }
        
        .summary-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #38bdf8, #2563eb);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
            margin-bottom: 16px;
            font-weight: 700;
        }
        
        .summary-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .summary-list li {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            font-size: 14px;
        }
        
        .summary-list li:last-child {
            border-bottom: none;
        }
        
        .summary-label {
            color: #94A3B8;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .summary-value {
            color: #FFFFFF;
            font-weight: 500;
        }
        
        .status-badge {
            background: rgba(34, 197, 94, 0.15);
            color: #4ADE80;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
    </style>

    <div class="container-fluid py-4">
        
        <div class="profile-header">
            <h3>👤 My Profile</h3>
            <p>Manage your GERIP account information and security.</p>
        </div>

        <div class="row">
            <!-- Left Column: Forms -->
            <div class="col-12 col-lg-8">
                
                <!-- Profile Information Card -->
                <div class="gerip-card">
                    <h4 class="card-title">📝 Profile Information</h4>
                    <p class="card-subtitle">Update your account's profile information and email address.</p>
                    
                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="mb-4">
                            <label class="gerip-label" for="name">Name</label>
                            <input id="name" name="name" type="text" class="gerip-input" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                            @if($errors->get('name'))
                                <span class="text-danger mt-1 d-block" style="font-size: 13px;">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <div class="mb-4">
                            <label class="gerip-label" for="email">Email</label>
                            <input id="email" name="email" type="email" class="gerip-input" value="{{ old('email', $user->email) }}" required autocomplete="username">
                            @if($errors->get('email'))
                                <span class="text-danger mt-1 d-block" style="font-size: 13px;">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <div class="d-flex align-items-center gap-3 mt-4">
                            <button type="submit" class="gerip-btn">Save Changes</button>
                            @if (session('status') === 'profile-updated')
                                <span class="text-success" style="font-size: 14px;">✓ Saved successfully.</span>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Password Card -->
                <div class="gerip-card">
                    <h4 class="card-title">🔒 Security</h4>
                    <p class="card-subtitle">Change your password to keep your account secure.</p>
                    
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="mb-4">
                            <label class="gerip-label" for="update_password_current_password">Current Password</label>
                            <input id="update_password_current_password" name="current_password" type="password" class="gerip-input" autocomplete="current-password" placeholder="Enter current password">
                            @if($errors->updatePassword->get('current_password'))
                                <span class="text-danger mt-1 d-block" style="font-size: 13px;">{{ $errors->updatePassword->first('current_password') }}</span>
                            @endif
                        </div>

                        <div class="mb-4">
                            <label class="gerip-label" for="update_password_password">New Password</label>
                            <input id="update_password_password" name="password" type="password" class="gerip-input" autocomplete="new-password" placeholder="Enter new password">
                            @if($errors->updatePassword->get('password'))
                                <span class="text-danger mt-1 d-block" style="font-size: 13px;">{{ $errors->updatePassword->first('password') }}</span>
                            @endif
                        </div>

                        <div class="mb-4">
                            <label class="gerip-label" for="update_password_password_confirmation">Confirm Password</label>
                            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="gerip-input" autocomplete="new-password" placeholder="Confirm new password">
                            @if($errors->updatePassword->get('password_confirmation'))
                                <span class="text-danger mt-1 d-block" style="font-size: 13px;">{{ $errors->updatePassword->first('password_confirmation') }}</span>
                            @endif
                        </div>

                        <div class="d-flex align-items-center gap-3 mt-4">
                            <button type="submit" class="gerip-btn">Update Password</button>
                            @if (session('status') === 'password-updated')
                                <span class="text-success" style="font-size: 14px;">✓ Password updated.</span>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Delete Account Card -->
                <div class="gerip-card gerip-card-danger">
                    <h4 class="card-title text-danger">⚠️ Delete Account</h4>
                    <p class="card-subtitle">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
                    
                    <form method="post" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('delete')
                        
                        <div class="mb-4">
                            <label class="gerip-label" for="password">Password to confirm deletion</label>
                            <input id="password" name="password" type="password" class="gerip-input" placeholder="Password">
                            @if($errors->userDeletion->get('password'))
                                <span class="text-danger mt-1 d-block" style="font-size: 13px;">{{ $errors->userDeletion->first('password') }}</span>
                            @endif
                        </div>

                        <button type="submit" class="gerip-btn gerip-btn-danger" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                            Delete Account
                        </button>
                    </form>
                </div>

            </div>

            <!-- Right Column: Profile Summary -->
            <div class="col-12 col-lg-4">
                <div class="gerip-card d-flex flex-column align-items-center text-center">
                    <div class="summary-avatar">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h4 style="color: #FFF; font-weight: 700; margin-bottom: 2px;">{{ $user->name }}</h4>
                    <p style="color: #94A3B8; font-size: 14px; margin-bottom: 16px;">{{ $user->email }}</p>
                    <span class="status-badge mb-4">● Active</span>

                    <ul class="summary-list w-100 text-start">
                        <li>
                            <span class="summary-label">👑 Role</span>
                            <span class="summary-value">{{ ucfirst($user->role ?? 'User') }}</span>
                        </li>
                        <li>
                            <span class="summary-label">📅 Joined</span>
                            <span class="summary-value">{{ $user->created_at->format('M d, Y') }}</span>
                        </li>
                        <li>
                            <span class="summary-label">⭐ Watchlists</span>
                            <span class="summary-value">{{ $user->watchlists()->count() ?? 0 }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            
        </div>
    </div>
</x-dashboard-layout>
