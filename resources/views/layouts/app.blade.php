<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    {{-- Gettings Assets from CDN --}}
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
</head>
<body>
    <div class="container">
    @if(session()->has('message'))
    <div class="alert alert-{{ session()->get('message.type') ?: 'success' }}">
        {{ session()->get('message.text') }}
    </div>
    @endif

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <h4>Oops! Something failed...</h4>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
    </div>

    @yield('scripts')
</body>
</html>