<?php

namespace Infra\Providers;

use App\Account\Repositories\IUserRepository;
use App\Chapter\Repositories\IChapterRepository;
use App\Genre\Repositories\IGenreRepository;
use App\History\Repositories\IHistoryRepository;
use App\Library\Repositories\ILibraryRepository;
use App\Manga\Repositories\IMangaRepository;
use App\People\Repositories\IPeopleRepository;
use App\Scan\Repositories\IScanRepository;
use Illuminate\Support\ServiceProvider;
use Infra\Database\Eloquent\Repositories\ChapterRepository;
use Infra\Database\Eloquent\Repositories\GenreRepository;
use Infra\Database\Eloquent\Repositories\HistoryRepository;
use Infra\Database\Eloquent\Repositories\LibraryRepository;
use Infra\Database\Eloquent\Repositories\MangaRepository;
use Infra\Database\Eloquent\Repositories\PeopleRepository;
use Infra\Database\Eloquent\Repositories\ScanRepository;
use Infra\Database\Eloquent\Repositories\UserRepository;

class RepositoryServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(IMangaRepository::class, MangaRepository::class);
        $this->app->bind(IScanRepository::class, ScanRepository::class);
        $this->app->bind(IGenreRepository::class, GenreRepository::class);
        $this->app->bind(IPeopleRepository::class, PeopleRepository::class);
        $this->app->bind(ILibraryRepository::class, LibraryRepository::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IChapterRepository::class, ChapterRepository::class);
        $this->app->bind(IHistoryRepository::class, HistoryRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
