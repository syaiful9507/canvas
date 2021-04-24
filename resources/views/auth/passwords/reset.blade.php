@extends('canvas::auth')

@section('content')
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <img class="mx-auto h-12 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg" alt="Workflow">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Reset your password
        </h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form class="space-y-6" action="{{ route('canvas.password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700"> Email address </label>
                    <div class="mt-1 relative">
                        <input id="email"
                                value="{{ old('email') }}"
                                name="email"
                                type="email"
                                autocomplete="email"
                                required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('email') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('email')
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <!-- Heroicon name: solid/exclamation-circle -->
                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        @enderror
                    </div>
                    @error('email')
                    <p class="mt-2 text-sm text-red-600" id="email-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700"> New Password </label>
                    <div class="mt-1 relative">
                        <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700"> Confirm Password </label>
                    <div class="mt-1 relative">
                        <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('password') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror">
                        @error('password')
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <!-- Heroicon name: solid/exclamation-circle -->
                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        @enderror
                    </div>
                    @error('password')
                    <p class="mt-2 text-sm text-red-600" id="password-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update password
                    </button>
                </div>
            </form>
        </div>
    </div>















{{--    <main class="col-12 col-lg-5">--}}
{{--        <div class="mb-5 text-center">--}}
{{--            <h1>Please <span class="font-cursive">reset</span> your password</h1>--}}
{{--        </div>--}}
{{--        <div class="card shadow-lg w-auto">--}}
{{--            <div class="card-body">--}}
{{--                <form method="POST" action="{{ route('canvas.password.update') }}" class="w-100 my-auto">--}}
{{--                    @csrf--}}

{{--                    <input type="hidden" name="token" value="{{ $request->route('token') }}">--}}

{{--                    <div class="form-group row">--}}
{{--                        <div class="col-12">--}}
{{--                            <label for="email" class="font-weight-bold text-uppercase text-muted small"> Email </label>--}}
{{--                            <input--}}
{{--                                type="email"--}}
{{--                                name="email"--}}
{{--                                value="{{ old('email') }}"--}}
{{--                                id="email"--}}
{{--                                class="form-control border-0 @error('email') is-invalid @enderror"--}}
{{--                                placeholder="Email address"--}}
{{--                                required--}}
{{--                                autofocus--}}
{{--                            />--}}
{{--                            @error('email')--}}
{{--                            <span class="invalid-feedback" role="alert">--}}
{{--                                <strong>{{ $message }}</strong>--}}
{{--                            </span>--}}
{{--                            @enderror--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="form-group row">--}}
{{--                        <div class="col-12">--}}
{{--                            <label for="password" class="font-weight-bold text-uppercase text-muted small"> Password </label>--}}
{{--                            <input--}}
{{--                                type="password"--}}
{{--                                name="password"--}}
{{--                                id="password"--}}
{{--                                class="form-control border-0 @error('password') is-invalid @enderror"--}}
{{--                                placeholder="Password"--}}
{{--                                required--}}
{{--                            />--}}
{{--                            @error('password')--}}
{{--                            <span class="invalid-feedback" role="alert">--}}
{{--                                <strong>{{ $message }}</strong>--}}
{{--                            </span>--}}
{{--                            @enderror--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="form-group row">--}}
{{--                        <div class="col-12">--}}
{{--                            <label for="password_confirmation" class="font-weight-bold text-uppercase text-muted small"> Confirm password </label>--}}
{{--                            <input--}}
{{--                                type="password"--}}
{{--                                name="password_confirmation"--}}
{{--                                id="password_confirmation"--}}
{{--                                class="form-control border-0 @error('password') is-invalid @enderror"--}}
{{--                                placeholder="Confirm password"--}}
{{--                                required--}}
{{--                            />--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <div class="form-group row">--}}
{{--                        <div class="col-12">--}}
{{--                            <button class="btn btn-success btn-block mt-3" type="submit">Reset password</button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="mt-5 text-center">--}}
{{--            <p class="text-muted">Powered by <a href="https://trycanvas.app" class="text-primary text-decoration-none">Canvas</a></p>--}}
{{--        </div>--}}
{{--    </main>--}}
@endsection
