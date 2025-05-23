@extends($layout ?? 'layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="POST" action="{{ route('admin.profile.update-notifications') }}">
                @csrf
                @method('PUT')
                
                @component('shared.components.profile.form-card', [
                    'title' => 'Notification Preferences',
                    'cancelRoute' => route('admin.profile.show'),
                    'submitText' => 'Save Preferences'
                ])
                    @include('shared.components.forms.switch', [
                        'name' => 'notification_preferences[email_notifications]',
                        'label' => 'Email Notifications',
                        'checked' => isset($preferences['email_notifications']) && $preferences['email_notifications'],
                        'description' => 'Receive general notifications via email.'
                    ])
                    
                    @include('shared.components.forms.switch', [
                        'name' => 'notification_preferences[marketing_emails]',
                        'label' => 'Marketing Emails',
                        'checked' => isset($preferences['marketing_emails']) && $preferences['marketing_emails'],
                        'description' => 'Receive marketing and promotional emails from us.'
                    ])
                    
                    @include('shared.components.forms.switch', [
                        'name' => 'notification_preferences[system_updates]',
                        'label' => 'System Updates',
                        'checked' => isset($preferences['system_updates']) && $preferences['system_updates'],
                        'description' => 'Receive notifications about system updates and maintenance.'
                    ])
                    
                    @include('shared.components.forms.switch', [
                        'name' => 'notification_preferences[new_features]',
                        'label' => 'New Features',
                        'checked' => isset($preferences['new_features']) && $preferences['new_features'],
                        'description' => 'Receive notifications about new features and improvements.'
                    ])
                @endcomponent
            </form>
        </div>
    </div>
</div>
@endsection 