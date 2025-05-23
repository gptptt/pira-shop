@extends($layout ?? 'layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form method="POST" action="{{ route('admin.profile.update') }}">
                @csrf
                @method('PUT')
                
                @component('shared.components.profile.form-card', [
                    'title' => 'Edit Profile',
                    'cancelRoute' => route('admin.profile.show'),
                    'submitText' => 'Save Changes'
                ])

                    <div class="row">
                        <div class="col-md-6">
                            @include('shared.components.forms.input', [
                                'name' => 'first_name',
                                'label' => 'First Name',
                                'value' => $user->first_name,
                                'required' => true,
                                'autofocus' => true,
                                'autocomplete' => 'first_name'
                            ])
                        </div>

                        <div class="col-md-6">
                            @include('shared.components.forms.input', [
                                'name' => 'last_name',
                                'label' => 'Last Name',
                                'value' => $user->last_name,
                                'required' => true,
                                'autocomplete' => 'last_name'
                            ])
                        </div>
                    </div>

                    @include('shared.components.forms.input', [
                        'name' => 'email',
                        'label' => 'Email Address',
                        'type' => 'email',
                        'value' => $user->email,
                        'required' => true,
                        'autocomplete' => 'email'
                    ])

                    @include('shared.components.forms.input', [
                        'name' => 'phone',
                        'label' => 'Phone Number',
                        'value' => $user->phone,
                        'autocomplete' => 'phone'
                    ])

                    <h5 class="mt-4 mb-3">Address Information</h5>

                    @include('shared.components.forms.input', [
                        'name' => 'address_line1',
                        'label' => 'Address Line 1',
                        'value' => $user->address_line1,
                        'autocomplete' => 'address-line1'
                    ])

                    @include('shared.components.forms.input', [
                        'name' => 'address_line2',
                        'label' => 'Address Line 2',
                        'value' => $user->address_line2,
                        'autocomplete' => 'address-line2'
                    ])

                    <div class="row">
                        <div class="col-md-6">
                            @include('shared.components.forms.input', [
                                'name' => 'city',
                                'label' => 'City',
                                'value' => $user->city,
                                'autocomplete' => 'address-level2'
                            ])
                        </div>

                        <div class="col-md-6">
                            @include('shared.components.forms.input', [
                                'name' => 'state',
                                'label' => 'State/Province',
                                'value' => $user->state,
                                'autocomplete' => 'address-level1'
                            ])
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            @include('shared.components.forms.input', [
                                'name' => 'postal_code',
                                'label' => 'Postal Code',
                                'value' => $user->postal_code,
                                'autocomplete' => 'postal-code'
                            ])
                        </div>

                        <div class="col-md-6">
                            @include('shared.components.forms.input', [
                                'name' => 'country',
                                'label' => 'Country',
                                'value' => $user->country,
                                'autocomplete' => 'country-name'
                            ])
                        </div>
                    </div>

                    <h5 class="mt-4 mb-3">Preferences</h5>

                    <div class="row">
                        <div class="col-md-6">
                            @php
                                $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
                                $timezoneOptions = [];
                                foreach($timezones as $tz) {
                                    $timezoneOptions[$tz] = $tz;
                                }
                            @endphp

                            @include('shared.components.forms.select', [
                                'name' => 'timezone',
                                'label' => 'Timezone',
                                'value' => $user->timezone,
                                'options' => $timezoneOptions
                            ])
                        </div>

                        <div class="col-md-6">
                            @include('shared.components.forms.select', [
                                'name' => 'language',
                                'label' => 'Language',
                                'value' => $user->language,
                                'options' => [
                                    'en' => 'English',
                                    'fr' => 'French',
                                    'es' => 'Spanish',
                                    'de' => 'German'
                                ]
                            ])
                        </div>
                    </div>
                @endcomponent
            </form>
        </div>
    </div>
</div>
@endsection 