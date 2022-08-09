<?php

declare(strict_types=1);

namespace Canvas\Http\Controllers;

use Canvas\Canvas;
use Canvas\Models\User;
use Illuminate\Routing\Controller;

class ViewController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('canvas::layout')->with([
            'scripts' => [
                'languageCodes' => Canvas::availableLanguageCodes(),
                'maxUpload' => config('canvas.upload_filesize'),
                'path' => Canvas::basePath(),
                'roles' => User::roles(),
                'timezone' => config('app.timezone'),
                'translations' => Canvas::availableTranslations(request()->user('canvas')->locale),
                'unsplash' => config('canvas.unsplash.access_key'),
                'user' => request()->user('canvas'),
                'version' => Canvas::installedVersion(),
            ],
        ]);
    }
}
