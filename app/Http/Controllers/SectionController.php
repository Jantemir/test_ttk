<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectionRequest;
use App\Models\Section;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Illuminate\Routing\Controller;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(! Gate::allows('sections.viewAny')) {
            abort(403, 'This action is unauthorized');
        }
        $sections = Section::orderBy('name', 'asc')->paginate(5);
        $pages = $sections->toArray();
        unset($pages['data']);
        return Inertia::render('Section/List', [
            'items' => $sections->items(),
            'pagination' => $pages,
            'can' => [
                'create' => auth()->user()?->can('sections.create') ?? false,
                'viewHidden' => auth()->user()?->can('sections.viewHidden') ?? false,
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(! Gate::allows('sections.create', Auth::user())) {
            abort(403, 'This action is unauthorized');
        }
        return Inertia::render('Section/Edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SectionRequest $request)
    {
        if(! Gate::allows('sections.create', Auth::user())) {
            abort(403, 'This action is unauthorized');
        }
        $fields = $request->safe()->only(['name', 'description']);
        $section = Section::create($fields);
        return redirect()->route('sections.show', $section->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $query = Section::query();
        if (Auth::user()?->isAdmin()) {
            $query->withoutGlobalScope('active');
        }
        $section = $query->findOrFail($id);
        if(! Gate::allows('sections.view', $section)) {
            abort(403, 'This action is unauthorized');
        }
        return Inertia::render('Section/Detail', [
            'item' => $section,
            'can' => [
                'update' => auth()->user()?->can('sections.update', $section) ?? false,
                'delete' => auth()->user()?->can('sections.delete', $section) ?? false,
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $query = Section::query();
        if (Auth::user()?->isAdmin()) {
            $query->withoutGlobalScope('active');
        }
        $section = $query->findOrFail($id);
        if(! Gate::allows('sections.update', $section)) {
            abort(403, 'This action is unauthorized');
        }
        return Inertia::render('Section/Edit', [
            'item' => $section,
            'action' => 'change',
            'can' => [
                'hide' => auth()->user()?->can('sections.hide') ?? false,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SectionRequest $request, int $id)
    {
        $query = Section::query();
        if (Auth::user()?->isAdmin()) {
            $query->withoutGlobalScope('active');
        }
        $section = $query->findOrFail($id);
        if(! Gate::allows('sections.update', $section)) {
            abort(403, 'This action is unauthorized');
        }
        $fields = $request->safe()->only(['name', 'description', 'active']);
        $section->update($fields);
        return redirect()->route('sections.show', $section->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $query = Section::query();
        if (Auth::user()?->isAdmin()) {
            $query->withoutGlobalScope('active');
        }
        $section = $query->findOrFail($id);
        if(! Gate::allows('sections.delete',$section)) {
            abort(403, 'This action is unauthorized');
        }
        $section->delete();
        return redirect()->to(route('sections.index'));
    }

    public function hidden()
    {
        if(! Gate::allows('sections.viewHidden')) {
            abort(403, 'This action is unauthorized');
        }
        $sections = Section::withoutGlobalScope('active')
            ->hidden()
            ->orderBy('name', 'asc')
            ->paginate(5);
        $pages = $sections->toArray();
        unset($pages['data']);
        return Inertia::render('Section/List', [
            'items' => $sections->items(),
            'pagination' => $pages,
            'can' => [
                'create' => false,
            ]
        ]);
    }
}
