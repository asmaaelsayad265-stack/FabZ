<?php

namespace App\Providers;

use App\Models\Course;
use App\Repositories\CourseRepository;
use App\Repositories\CourseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
    }

    public function boot(): void
    {
        Model::shouldBeStrict(! app()->isProduction());

        app('router')->bind('course', static fn (string $value) => Course::query()->whereKey($value)->firstOrFail());
    }
}
