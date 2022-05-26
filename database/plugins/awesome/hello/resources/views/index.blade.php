<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Hello world from Awesome Team</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" crossorigin="anonymous">

  </head>

  <body>

    <!-- Begin page content -->
    <main role="main" class="container">
      <h1 class="mt-5">Hello World</h1>
      <p class="lead">This is a sample dashboard page, powered by <strong>{{ $plugin->name }}</strong> plugin!</p>
      <p class="lead">Below is a phrase retrieved from I18n. If it does not work, you can issue the <code>php artisan translation:upgrade</code> command then refresh this page</p>
      <p class="lead">{{ trans('hello::messages.intro') }}</p>

      <p class="lead">Click <a href="{{ action('\Awesome\Hello\Controllers\DashboardController@index') }}">here</a> to reload the page. Or <a href="{{ action('Admin\PluginController@index') }}">go back to the Plugin list</a></p>
    </main>

  </body>
</html>
