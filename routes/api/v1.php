<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/


use App\Account\Models\User;
use App\Manga\Models\Manga;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Infra\Http\Api\Controllers\Chapter\DeleteChapterController;
use Infra\Http\Api\Controllers\Chapter\DestroyChapterController;
use Infra\Http\Api\Controllers\Chapter\GetChapterDetailsController;
use Infra\Http\Api\Controllers\Chapter\GetFirstChapterByMangaController;
use Infra\Http\Api\Controllers\Chapter\GetPagesByChapterController;
use Infra\Http\Api\Controllers\Chapter\LatestChaptersController;
use Infra\Http\Api\Controllers\Chapter\ListChaptersByMangaController;
use Infra\Http\Api\Controllers\Chapter\PublishChapterController;
use Infra\Http\Api\Controllers\Chapter\RestoreChapterController;
use Infra\Http\Api\Controllers\Genre\DeleteGenreController;
use Infra\Http\Api\Controllers\Genre\DestroyGenreController;
use Infra\Http\Api\Controllers\Genre\ListAllGenresController;
use Infra\Http\Api\Controllers\Genre\RegisterGenreController;
use Infra\Http\Api\Controllers\Genre\RestoreGenreController;
use Infra\Http\Api\Controllers\Genre\UpdateGenreController;
use Infra\Http\Api\Controllers\Manga\DeleteMangaController;
use Infra\Http\Api\Controllers\Manga\DestroyMangaController;
use Infra\Http\Api\Controllers\Manga\GetMangaBySlugController;
use Infra\Http\Api\Controllers\Manga\ListMangasByGenreController;
use Infra\Http\Api\Controllers\Manga\RegisterMangaController;
use Infra\Http\Api\Controllers\Manga\RestoreMangaController;
use Infra\Http\Api\Controllers\Manga\SearchMangaController;
use Infra\Http\Api\Controllers\Manga\UpdateMangaController;
use Infra\Http\Api\Controllers\People\DeletePeopleController;
use Infra\Http\Api\Controllers\People\DestroyPeopleController;
use Infra\Http\Api\Controllers\People\ListWorksByPeopleController;
use Infra\Http\Api\Controllers\People\RegisterPeopleController;
use Infra\Http\Api\Controllers\People\RestorePeopleController;
use Infra\Http\Api\Controllers\People\SearchPeopleController;
use Infra\Http\Api\Controllers\People\UpdatePeopleController;
use Infra\Http\Api\Controllers\Scan\AssociateMangaToScanController;
use Infra\Http\Api\Controllers\Scan\DeleteScanController;
use Infra\Http\Api\Controllers\Scan\DestroyScanController;
use Infra\Http\Api\Controllers\Scan\GetScanBySlugController;
use Infra\Http\Api\Controllers\Scan\ListMangasByScanController;
use Infra\Http\Api\Controllers\Scan\ListMembersByScanController;
use Infra\Http\Api\Controllers\Scan\RegisterScanController;
use Infra\Http\Api\Controllers\Scan\RestoreScanController;
use Infra\Http\Api\Controllers\Scan\SearchScanController;
use Infra\Http\Api\Controllers\Scan\UpdateScanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('mangas', SearchMangaController::class);
Route::get('mangas/genres/{genre}', ListMangasByGenreController::class);
Route::get('mangas/{slug}/details', GetMangaBySlugController::class);
Route::get('mangas/{slug}/chapters', ListChaptersByMangaController::class);
Route::get('mangas/{slug}/chapters/first', GetFirstChapterByMangaController::class);

Route::get('chapters/latest', LatestChaptersController::class);

Route::get('latest', function (Request $request) {
    $showNotAdultContent = !$request->boolean('show_adult_content', true);
    $format = $request->query('format');
    $page = $request->query('page');

    $key = 'mangas_latest::show_not_adult::'.$showNotAdultContent.'::format::'.$format.'::page::'.$page;

    $mangas = Cache::remember(
        $key,
        60,
        function () use ($showNotAdultContent, $format) {
            return Manga::query()
                ->whereHas('chapters', function ($query) {
                    $query
                        ->where('published_at', '>=', now()->subWeek())
                        ->orderBy('published_at', 'desc');
                })
                ->with(['chapters' => function ($query) {
                    $query
                        ->withCount(['pages'])
                        ->where('published_at', '>=', now()->subWeek())
                        ->orderBy('published_at', 'desc')
                        ->get();
                }])
                ->when($showNotAdultContent, function (Builder $query) {
                    $query->whereIsAdult(false);
                })
                ->when(!!$format, function (Builder $query) use ($format) {
                    $query->whereFormat($format);
                })
                ->orderBy('last_published_at', 'desc')
                ->paginate();
        }
    );

    return response()->json($mangas);
});
Route::get('chapters/{chapter}', [GetChapterDetailsController::class, 'show']);
Route::get('chapters/{id}/pages', GetPagesByChapterController::class);

Route::get('genres', ListAllGenresController::class);

Route::get('scans', SearchScanController::class);
Route::get('scans/{slug}/details', GetScanBySlugController::class);
Route::get('scans/{id}/mangas', ListMangasByScanController::class);
Route::get('scans/{id}/members', ListMembersByScanController::class);

Route::get('peoples', SearchPeopleController::class);
Route::get('peoples/{id}/works', ListWorksByPeopleController::class);

Route::get('mangas/random', function (Request $request) {
    return response()->json([
        Manga::query()
            ->with(['genres'])
            ->where('is_adult', false)
            ->inRandomOrder()
            ->first()
    ]);
});

Route::middleware(['auth:api'])->group(function () {
    Route::post('peoples/new', RegisterPeopleController::class);
    Route::delete('peoples/{id}/delete', DeletePeopleController::class);
});

Route::middleware(['auth:api', 'can:admin'])->group(function () {
    Route::delete('users/{id}/delete', function (Request $request) {
        return response()->json(\App\Account\Models\User::destroy($request->id));
    });

    Route::delete('users/{id}/destroy', function (Request $request, $id) {
        \App\Account\Models\User::find($id)?->forceDelete($request->id);
        return response()->json(['status' => 'success']);
    });

    Route::get('accounts', function (Request $request) {
        return response()->json(User::all());
    });

    Route::get('tests', function () {
        return response()->json(['status' => 'success']);
    });

    Route::post('genres/new', RegisterGenreController::class);
    Route::delete('genres/{id}/delete', DeleteGenreController::class);
    Route::delete('genres/{id}/destroy', DestroyGenreController::class);
    Route::patch('genres/{id}/restore', RestoreGenreController::class);
    Route::patch('genres/{id}/update', UpdateGenreController::class);


    Route::post('mangas/new', RegisterMangaController::class);
    Route::delete('mangas/{id}/delete', DeleteMangaController::class);
    Route::delete('mangas/{id}/destroy', DestroyMangaController::class);
    Route::patch('mangas/{id}/restore', RestoreMangaController::class);
    Route::patch('mangas/{id}/update', UpdateMangaController::class);


    Route::delete('scans/{id}/destroy', DestroyScanController::class);
    Route::patch('scans/{id}/restore', RestoreScanController::class);
    Route::post('scans/new', RegisterScanController::class);
    Route::patch('scans/{slug}/mangas/associate', AssociateMangaToScanController::class);
    Route::delete('scans/{id}/delete', DeleteScanController::class);
    Route::patch('scans/{id}/update', UpdateScanController::class);

    Route::delete('peoples/{id}/destroy', DestroyPeopleController::class);
    Route::patch('peoples/{id}/restore', RestorePeopleController::class);
    Route::patch('peoples/{id}/update', UpdatePeopleController::class);

    Route::delete('chapters/{id}/delete', DeleteChapterController::class);
    Route::delete('chapters/{id}/destroy', DestroyChapterController::class);
    Route::patch('chapters/{id}/restore', RestoreChapterController::class);
    Route::post('chapters/upload', PublishChapterController::class);

    Route::get('mangas/deleted', function () {
        return response()->json(\App\Manga\Models\Manga::onlyTrashed()->get());
    });
    Route::get('chapters/deleted', function () {
        return response()->json(\App\Chapter\Models\Chapter::onlyTrashed()->get());
    });
    Route::get('peoples/deleted', function () {
        return response()->json(\App\People\Models\People::onlyTrashed()->get());
    });
    Route::get('scans/deleted', function () {
        return response()->json(\App\Scan\Models\Scan::onlyTrashed()->get());
    });
    Route::get('genres/deleted', function () {
        return response()->json(\App\Genre\Models\Genre::onlyTrashed()->get());
    });

    Route::post('/mangas/{manga}/chapter/upload', PublishChapterController::class);
});
