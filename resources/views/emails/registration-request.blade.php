<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{__('New Registration Request')}}</title>
</head>
<body>
    <h1>{{__('New Registration Request')}}</h1>
    
    <p>{{__('A new user has registered and requires approval:')}}</p>
    
    <ul>
        <li><strong>{{__('Name:')}}</strong> {{ $newUser->name }}</li>
        <li><strong>{{__('Email:')}}</strong> {{ $newUser->email }}</li>
        <li><strong>{{__('Registration date:')}}</strong> {{ $newUser->created_at->format('Y-m-d H:i:s') }}</li>
    </ul>
    
    <p>{{__('Please log in to the admin panel to review this request.')}}</p>
    
    <p>
        <a href="{{ url('/registration-requests') }}">{{__('Review Registration Requests')}}</a>
    </p>
</body>
</html>