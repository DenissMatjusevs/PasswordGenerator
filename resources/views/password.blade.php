<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Password Generator</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="offset-3 col-6">
                <h1 class="text-center">Password Generator</h1>
                <div class="card mt-5">
                    <h5 class="card-header text-center">Type number of symbols</h5>
                    <div class="card-body">
                        {{ Form::open(['route' => 'generate', 'method' => 'POST']) }}
                            <div class="mb-3 custom">
                                {{ Form::number('password_length', $password_length, ['class' => 'form-control' . (($errors->has('password_length')) ? ' is-invalid' : '')]) }}
                            </div>
                            <div class="mb-3 form-check">
                                {{ Form::checkbox('numbers', true,  $numbers, ['class' => 'form-check-input']) }}
                                {{ Form::label('numbers', 'Numbers without 0 and 1', ['class' => 'form-check-label']) }}
                            </div>
                            <div class="mb-3 form-check">
                                {{ Form::checkbox('big_letters', true,  $big_letters, ['class' => 'form-check-input']) }}
                                {{ Form::label('big_letters', 'Big letters without "O"', ['class' => 'form-check-label']) }}
                            </div>
                            <div class="mb-3 form-check">
                                {{ Form::checkbox('small_letters', true,  $small_letters, ['class' => 'form-check-input']) }}
                                {{ Form::label('small_letters', 'Small letters without "l"', ['class' => 'form-check-label']) }}
                            </div>
                            <div class="mb-3 form-check text-center">
                                {{ Form::submit('Generate', ['class' => 'btn btn-primary']) }}
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>

                @if ($password_is_generated)
                    <div class="card mt-5">
                        <h5 class="card-header text-center">Password is generated!</h5>
                        <div class="card-body">
                            <div class="text-center custom-password">
                                {{ $password }}
                            </div>
                        </div>
                    </div>
                @endif

                @if ($is_error)
                    <div class="alert alert-danger text-center" role="alert">
                        {{ $error_message }}
                    </div>
                @endif

                @if (count($errors) > 0)
                    <div class="alert alert-danger text-center">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
