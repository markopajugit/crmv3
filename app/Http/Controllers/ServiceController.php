<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Services query
        $servicesQuery = Service::with('service_category');

        // Service categories query
        $categoriesQuery = ServiceCategory::withCount('services');

        // Text search for services
        $serviceSearch = $request->get('service_search', '');
        if (!empty($serviceSearch)) {
            $servicesQuery->where(function($q) use ($serviceSearch) {
                $q->where('name', 'LIKE', '%' . $serviceSearch . '%')
                  ->orWhere('cost', 'LIKE', '%' . $serviceSearch . '%')
                  ->orWhere('type', 'LIKE', '%' . $serviceSearch . '%')
                  ->orWhereHas('service_category', function($q) use ($serviceSearch) {
                      $q->where('name', 'LIKE', '%' . $serviceSearch . '%');
                  });
            });
        }

        // Text search for categories
        $categorySearch = $request->get('category_search', '');
        if (!empty($categorySearch)) {
            $categoriesQuery->where('name', 'LIKE', '%' . $categorySearch . '%');
        }

        // Paginate both
        $services = $servicesQuery->latest()->paginate(10, ['*'], 'services_page')->appends(request()->query());
        $service_categories = $categoriesQuery->latest()->paginate(10, ['*'], 'categories_page')->appends(request()->query());

        // If AJAX request, return JSON
        if ($request->ajax() || $request->has('ajax')) {
            return response()->json([
                'services_html' => view('services.partials.services-table', compact('services'))->render(),
                'services_pagination' => view('services.partials.services-pagination', compact('services'))->render(),
                'services_total' => $services->total(),
                'categories_html' => view('services.partials.categories-table', compact('service_categories'))->render(),
                'categories_pagination' => view('services.partials.categories-pagination', compact('service_categories'))->render(),
                'categories_total' => $service_categories->total()
            ]);
        }

        return view('services.index', compact('services', 'service_categories'))
            ->with('i', (request()->input('services_page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ServiceCategory::all();
        return view('services.create')->with('categories', $categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createCategory()
    {
        return view('services.createCategory');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $service = Service::create($request->all());

        return redirect()->route('services.index')
            ->with('success','Service created successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeCategory(Request $request)
    {
        $service = ServiceCategory::create($request->all());

        return redirect()->route('services.index')
            ->with('success','Service Category created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        return view('services.show',[
                'service' => $service,
                'service_categories' => ServiceCategory::all()
            ]
        );
    }

    public function showCategory(Request $request, $id)
    {
        $serviceCategory = ServiceCategory::find($id);
        return view('services.showCategory',[
                'service_category' => $serviceCategory
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function edit(Service $service)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        $service->update($request->all());
    }

    public function updateCategory(Request $request, $id)
    {
        $serviceCategory = ServiceCategory::find($id);
        $serviceCategory->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        $service->delete();
    }


    public function destroyCategory($id)
    {
        $serviceCategory = ServiceCategory::find($id);
        $serviceCategory->delete();
    }
}
