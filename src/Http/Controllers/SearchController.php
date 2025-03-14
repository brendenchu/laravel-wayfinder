<?php

namespace Brendenchu\Wayfinder\Http\Controllers;

use Brendenchu\Wayfinder\Facades\Wayfinder;
use Brendenchu\Wayfinder\Http\Requests\SearchRequest;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Handle the basic search form and results.
     *
     * @param SearchRequest $request
     * @param string|null $searchable
     * @return View
     */
    public function __invoke(SearchRequest $request, string $searchable = null): View
    {
        // Get searchable class from config
        $searchable = $searchable ?? config('wayfinder.default_searchable');
        $searchableClass = config("wayfinder.searchables.{$searchable}");

        if (!$searchableClass || !Wayfinder::canSearch($searchableClass)) {
            abort(404, 'Search not available for this resource');
        }

        // Get validated form params
        $formParams = $request->searchParams();

        // Get searchable config
        $config = Wayfinder::config($searchableClass);

        // Create a search response object
        $response = Wayfinder::search($formParams, $searchableClass);

        // Get view from config or use default
        $view = $config->view ?? config('wayfinder.view.template_name');

        return view($view, [
            config('wayfinder.view.response_name') => $response,
            'searchable' => $searchable,
            'searchableSingular' => Str::singular($searchable),
            'searchableClass' => $searchableClass,
            'title' => $config->title ?? ucfirst($searchable) . ' Search',
        ]);
    }
}
