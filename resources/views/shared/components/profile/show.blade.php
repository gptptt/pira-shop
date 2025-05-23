@extends($layout ?? 'layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">My Profile</h5>
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-sm btn-light">Edit Profile</a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-3 text-center">
                            @if ($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo" class="img-thumbnail rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 150px; height: 150px; margin: 0 auto;">
                                    <span class="h1">{{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}</span>
                                </div>
                            @endif
                            
                            <form action="{{ route('admin.profile.upload-photo') }}" method="POST" enctype="multipart/form-data" class="mt-2">
                                @csrf
                                <div class="input-group">
                                    <input type="file" name="profile_photo" class="form-control form-control-sm" accept="image/*">
                                    <button type="submit" class="btn btn-sm btn-secondary">Upload</button>
                                </div>
                                @error('profile_photo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </form>
                        </div>
                        
                        <div class="col-md-9">
                            <h3>{{ $user->full_name }}</h3>
                            <p class="text-muted">
                                <i class="fas fa-envelope"></i> {{ $user->email }}<br>
                                @if($user->phone)
                                <i class="fas fa-phone"></i> {{ $user->formattedPhone() ?: $user->phone }}<br>
                                @endif
                                <i class="fas fa-user-shield"></i> 
                                @foreach($user->roles as $role)
                                    <span class="badge bg-info">{{ ucfirst($role->name) }}</span>
                                @endforeach
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            @component('shared.components.profile.profile-card', ['title' => 'Personal Information', 'editRoute' => route('admin.profile.edit')])
                                <p><strong>Name:</strong> {{ $user->full_name }}</p>
                                <p><strong>Email:</strong> {{ $user->email }}</p>
                                <p><strong>Phone:</strong> {{ $user->phone ?: 'Not provided' }}</p>
                                <p><strong>Language:</strong> {{ strtoupper($user->language) }}</p>
                                <p><strong>Timezone:</strong> {{ $user->timezone }}</p>
                            @endcomponent
                        </div>
                        
                        <div class="col-md-6">
                            @component('shared.components.profile.profile-card', ['title' => 'Address Information', 'editRoute' => route('admin.profile.edit')])
                                @if($user->full_address)
                                    <p>{{ $user->full_address }}</p>
                                @else
                                    <p class="text-muted">No address information provided.</p>
                                @endif
                            @endcomponent
                            
                            @component('shared.components.profile.profile-card', ['title' => 'Account Settings'])
                                <div class="list-group">
                                    <a href="{{ route('admin.profile.edit-password') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        Change Password
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                    <a href="{{ route('admin.profile.edit-notifications') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                        Notification Preferences
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </div>
                            @endcomponent
                        </div>
                    </div>
                    
                    @component('shared.components.profile.profile-card', ['title' => 'Account Information'])
                        <p><strong>Account Status:</strong> 
                            @if($user->status == 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($user->status == 'inactive')
                                <span class="badge bg-warning">Inactive</span>
                            @else
                                <span class="badge bg-danger">Suspended</span>
                            @endif
                        </p>
                        <p><strong>Email Verified:</strong> 
                            @if($user->email_verified_at)
                                <span class="badge bg-success">Yes</span> ({{ $user->email_verified_at->format('M d, Y') }})
                            @else
                                <span class="badge bg-danger">No</span>
                            @endif
                        </p>
                        <p><strong>Last Login:</strong> {{ $user->last_login_at ? $user->last_login_at->format('M d, Y h:i A') : 'Never' }}</p>
                        <p><strong>Account Created:</strong> {{ $user->created_at->format('M d, Y') }}</p>
                    @endcomponent
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 