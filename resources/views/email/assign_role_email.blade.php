@if ($user->name)
{{ __('Hello :name', ['name' => $user->name]) }},
@endif
{{ __('User without role is logged in:') }}
@if (!empty($noRoleUser->name) && !empty($noRoleUser->email))
{{ $noRoleUser->name }}, {{ $noRoleUser->email }}
<a href="{{ route('users.edit', $noRoleUser->id) }}">{{ __('Please assign him/her a role!') }}</a>
@endif