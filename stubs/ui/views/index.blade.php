@extends('canvas-ui.layout')

@section('title', 'Canvas UI')

@section('content')
    <div class="bg-white pt-16 pb-20 px-4 sm:px-6 lg:pt-24 lg:pb-28 lg:px-8">
        <div class="relative max-w-lg mx-auto divide-y-2 divide-gray-200 lg:max-w-7xl">
            <div>
                <h2 class="text-3xl tracking-tight font-extrabold text-gray-900 sm:text-4xl">Canvas UI</h2>
                <div class="mt-3 sm:mt-4 lg:grid lg:grid-cols-2 lg:gap-5 lg:items-center">
                    <p class="text-xl text-gray-500">Sometimes creating a blog is easier said than done. With Canvas, it's just easier.</p>
                    <div class="mt-6 flex flex-col sm:flex-row lg:mt-0 lg:justify-end">
                        <div class="mt-2 mr-3 flex-shrink-0 w-full flex sm:mt-0 sm:w-auto sm:inline-flex">
                            <a href="#" class="mt-2 text-base font-medium text-gray-500 hover:text-gray-900"> Sign in </a>
                        </div>
                        <div class="mt-2 flex-shrink-0 w-full flex rounded-md shadow-sm sm:mt-0 sm:ml-3 sm:w-auto sm:inline-flex">
                            <button type="button" class="w-full bg-indigo-600 px-4 py-2 border border-transparent rounded-md flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:w-auto sm:inline-flex">Get Started</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 pt-10 grid gap-16 lg:grid-cols-2 lg:gap-x-5 lg:gap-y-12">
                @foreach($posts as $post)
                    <div>
                        <p class="text-sm text-gray-500">
                            <time datetime="2020-03-16">{{ $post['published_at']->format('M j, Y') }}</time>
                        </p>
                        <a href="{{ url($post['slug']) }}" class="mt-2 block">
                            <p class="text-xl font-semibold text-gray-900">{{ $post['title'] }}</p>
                            <p class="mt-3 text-base text-gray-500">{!! \Illuminate\Support\Str::words(strip_tags($post['body']), 30, '...') !!}</p>
                        </a>
                        <div class="mt-3">
                            <a href="{{ url($post['slug']) }}" class="text-base font-semibold text-indigo-600 hover:text-indigo-500"> Read full post </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
