<?php namespace Krucas\Notification;

use Illuminate\Support\ServiceProvider;
use Krucas\Notification\Middleware\NotificationMiddleware;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes(array(
            __DIR__ . '/../../config/notification.php' => config_path('notification.php'),
        ), 'config');

        $this->app['events']->subscribe('Krucas\Notification\Subscriber');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/notification.php', 'notification');

        $this->app->singleton('Krucas\Notification\Notification', function ($app) {
            return $app['notification'];
        });

        $this->app->singleton('notification', function ($app) {
            $config = $app['config'];

            $notification = new Notification(
                $config->get('notification.default_container'),
                $config->get('notification.default_types'),
                $config->get('notification.default_format'),
                $config->get('notification.default_formats')
            );

            $notification->setEventDispatcher($app['events']);

            return $notification;
        });

        $this->app->singleton('Krucas\Notification\Subscriber', function ($app) {
            return new Subscriber($app['session.store'], $app['config']);
        });

        $this->app->singleton('Krucas\Notification\Middleware\NotificationMiddleware', function ($app) {
            return new NotificationMiddleware(
                $app['session.store'],
                $app['notification'],
                $app['config']->get('notification.session_prefix')
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array(
            'Krucas\Notification\Notification',
            'Krucas\Notification\Subscriber',
            'notification',
        );
    }
}