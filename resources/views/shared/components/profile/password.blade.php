@extends($layout ?? 'layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="POST" action="{{ route('admin.profile.update-password') }}">
                @csrf
                @method('PUT')
                
                @component('shared.components.profile.form-card', [
                    'title' => 'Change Password',
                    'cancelRoute' => route('admin.profile.show'),
                    'submitText' => 'Change Password'
                ])
                    @include('shared.components.forms.input', [
                        'name' => 'current_password', 
                        'label' => 'Current Password',
                        'type' => 'password',
                        'required' => true,
                        'autocomplete' => 'current-password'
                    ])
                    
                    @include('shared.components.forms.input', [
                        'name' => 'password',
                        'label' => 'New Password',
                        'type' => 'password',
                        'required' => true,
                        'autocomplete' => 'new-password',
                        'hint' => 'Password must be at least 8 characters long and contain a mix of letters, numbers, and symbols.'
                    ])
                    
                    @include('shared.components.forms.input', [
                        'name' => 'password_confirmation',
                        'label' => 'Confirm New Password',
                        'type' => 'password',
                        'required' => true,
                        'autocomplete' => 'new-password'
                    ])
                @endcomponent
            </form>
        </div>
    </div>
</div>
@endsection 