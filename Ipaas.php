<?php

namespace App\Ipaas;

use App\Ipaas\AuthAndLog;
use App\Ipaas\Info\Client;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpKernel\Exception\HttpException;
use XeroPHP\Remote\Exception\BadRequestException;
use XeroPHP\Remote\Exception\InternalErrorException;
use XeroPHP\Remote\Exception\NotAvailableException;
use XeroPHP\Remote\Exception\NotFoundException;
use XeroPHP\Remote\Exception\NotImplementedException;
use XeroPHP\Remote\Exception\OrganisationOfflineException;
use XeroPHP\Remote\Exception\RateLimitExceededException;
use XeroPHP\Remote\Exception\UnauthorizedException;

class Ipaas extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // nothing here
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        /*
         * Load helpers from Ipaas/Helper directory
         */
        require_once __DIR__ . '/Helper/include.php';

        /*
         * Init singleton ipaas-info with Ipaas/Info/Client
         */
        $this->app->singleton('ipaas-info', function ($app) {
            return new Client();
        });

        /*
        * Init singleton ipaas-info with Ipaas/Response
        */
        $this->app->singleton('ipaas-response', function ($app) {
            return new Response();
        });

        /*
        * Take over exception handler
        */
        $this->app->singleton(
            'Illuminate\Contracts\Debug\ExceptionHandler',
            GException::class
        );

        /**
         * Register dingo handlers
         */
        app('Dingo\Api\Exception\Handler')->register(function (HttpException $exception) {
            return JsonExceptionRender::render($exception);
        });
        app('Dingo\Api\Exception\Handler')->register(function (BadRequestException $exception) {
            return JsonExceptionRender::render($exception);
        });
        app('Dingo\Api\Exception\Handler')->register(function (InternalErrorException $exception) {
            return JsonExceptionRender::render($exception);
        });
        app('Dingo\Api\Exception\Handler')->register(function (NotAvailableException $exception) {
            return JsonExceptionRender::render($exception);
        });
        app('Dingo\Api\Exception\Handler')->register(function (NotFoundException $exception) {
            return JsonExceptionRender::render($exception);
        });
        app('Dingo\Api\Exception\Handler')->register(function (NotImplementedException $exception) {
            return JsonExceptionRender::render($exception);
        });
        app('Dingo\Api\Exception\Handler')->register(function (OrganisationOfflineException $exception) {
            return JsonExceptionRender::render($exception);
        });
        app('Dingo\Api\Exception\Handler')->register(function (RateLimitExceededException $exception) {
            return JsonExceptionRender::render($exception);
        });
        app('Dingo\Api\Exception\Handler')->register(function (UnauthorizedException $exception) {
            return JsonExceptionRender::render($exception);
        });
        app('Dingo\Api\Exception\Handler')->register(function (ValidationException $exception) {
            return JsonExceptionRender::render($exception);
        });
        app('Dingo\Api\Exception\Handler')->register(function (ModelNotFoundException $exception) {
            return JsonExceptionRender::render($exception);
        });
        app('Dingo\Api\Exception\Handler')->register(function (QueryException $exception) {
            return JsonExceptionRender::render($exception);
        });

        $kernel = $this->app->make(Kernel::class);
        $kernel->pushMiddleware(AuthAndLog::class);
    }
}