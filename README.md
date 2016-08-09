# Canvas

<a href="https://travis-ci.org/austintoddj/canvas" target="_blank"><img src="https://travis-ci.org/austintoddj/canvas.svg?branch=master" alt="Build Status"></a> 
<a href="https://styleci.io/repos/52815899" target="_blank"><img src="https://styleci.io/repos/52815899/shield?style=flat" alt="StyleCI"></a>
<a href="https://github.com/austintoddj/canvas/issues"><img src="https://img.shields.io/github/issues/austintoddj/canvas.svg" alt="GitHub Issues"></a>
<a href="https://packagist.org/packages/austintoddj/canvas" target="_blank"><img src="https://poser.pugx.org/austintoddj/canvas/downloads" alt="Total Downloads"></a>
<a href="https://github.com/austintoddj/canvas/stargazers"><img src="https://img.shields.io/github/stars/austintoddj/canvas.svg" alt="Stars"></a>
<a href="https://github.com/austintoddj/canvas/network"><img src="https://img.shields.io/github/forks/austintoddj/canvas.svg" alt="GitHub Forks"></a>
<a href="https://packagist.org/packages/austintoddj/canvas" target="_blank"><img src="https://poser.pugx.org/austintoddj/canvas/v/stable" alt="Latest Stable Version"></a>
<a href="https://github.com/austintoddj/canvas/blob/master/LICENSE"><img src="https://poser.pugx.org/austintoddj/canvas/license" alt="License"></a>

<a href="http://canvas.toddaustin.io">Canvas</a> is a minimal blogging application for developers. It attempts to make blogging simple and enjoyable by utilizing the latest technologies and keeping the administration as simple as possible with the primary focus on writing.

## Requirements

Before you proceed make sure your server meets the following requirements:

- PHP >= 5.5.9
- PDO PHP Extension
- SQLite PHP Extension
- OpenSSL PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- PDO compliant database (SQL, MySQL, PostgreSQL, SQLite)

## Download

Getting Canvas up and running is simple. You can choose either of the following download options:

Option 1 - Use Packagist:

```sh
composer create-project austintoddj/canvas
```

Option 2 - Use GitHub:

```sh
git clone https://github.com/austintoddj/canvas.git
```

If you chose Option 1, skip this step. If you chose Option 2, run the following command from the project root:

```sh
composer install
```

To enable uploads on the blog, give ownership of the uploads directory to the web server:

```sh
sudo chown -R www-data:www-data public/uploads
```

## Application Configuration

You will need to create a new `.env` file and fill in the necessary variables:

```sh
cat .env.example > .env; vim .env;
```

## User Configuration

|Data Key|Value|
|---|---|
|Login Email|`admin@canvas.com`(default)|
|Login Password|`password`(default)|

When you download Canvas, you may want to change the default admin user credentials. To update admin user information including setting a new password (Recommended), edit the file `Canvas/database/seeds/UsersTableSeeder.php` and save it. Don't worry, you can always change this information within the application after the install process.

## The 30 Second Canvas Installation

Installing Canvas is really simple. Just run `php artisan canvas:install` and follow the on-screen prompts.

## Search Indexing

Search functionality in Canvas is provided by [TNTSearch](https://github.com/teamtnt/tntsearch) and requires the [SQLite](http://php.net/manual/en/book.sqlite3.php) PHP extension to be installed on your server as listed above.

To build the index, simply run `php artisan canvas:index`.

After you run the command, you just need to set the permissions of the storage directory:

```sh
sudo chmod o+w -R storage
```

**Congratulations!** Your new blog is set up and ready to go. Feeling adventurous? Continue on with the advanced options below to get even more out of Canvas.

# Advanced Options

## Theming Canvas

Adding or modifying styles with Canvas is a breeze. None of this needs to be done out of the box, it simply works on its own. But if you're feeling a little creative and want to make it stand out more, follow these steps:

Install the project dependencies via `npm`:

```sh
sudo npm install
```

Install Gulp globally:

```sh
sudo npm install --global gulp-cli
```

After you make any modifications to the files in `Canvas/resources/assets/less/`, run gulp:

```sh
gulp
```

## Google Analytics

Canvas natively supports [Google Analytics](https://www.google.com/analytics/#?modal_active=none).

1. Set up a web property on [Google Analytics](https://www.google.com/analytics/#?modal_active=none).
2. Enter your `GA_ID`(Tracking ID) into the `.env` file.
3. Enable Google Analytics in the `.env` file by setting `GA_ENABLE` to `true`.

## Disqus Comments

Canvas allows the integration of [Disqus](https://disqus.com) comments into your blog.

1. Grab a unique shortname from [Official Documentation](https://help.disqus.com/customer/portal/articles/466208-what-s-a-shortname-).
2. Enter your `DISQUS_NAME`(Shortname) into the `.env` file.

## Contributing

Thank you for considering contributing to Canvas! The contribution guide can be found [here](https://github.com/austintoddj/Canvas/blob/master/CONTRIBUTING.md) and also has details about joining the official [HipChat group](https://canvas-blog.hipchat.com/home) for those who want to be a part of Canvas' future development.

## Changelog

Detailed changes for each release are documented in the [release notes](https://github.com/austintoddj/Canvas/releases).

## License

Canvas is open-sourced software licensed under the [MIT license](https://github.com/austintoddj/Canvas/blob/master/LICENSE).
