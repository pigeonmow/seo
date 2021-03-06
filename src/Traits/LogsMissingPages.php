<?php

namespace Knash94\Seo\Traits;

use Exception;
use Illuminate\Http\RedirectResponse;
use Knash94\Seo\Contracts\HttpErrorsContract;
use Knash94\Seo\PageNotFoundHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait LogsMissingPages
{
    /**
     * Catches 404 errors and logs them
     *
     * @param  \Exception  $e
     * @return RedirectResponse|mixed
     */
    public function reportNotFound(Exception $e)
    {
        if ($e instanceof NotFoundHttpException) {
            $service = app()->make(PageNotFoundHandler::class);
            return $service->handleHttpNotFoundException($e);
        }
    }
}