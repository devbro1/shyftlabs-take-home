<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * @group Announcements
 */
class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Announcement::class)
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::partial('title'),
                AllowedFilter::partial('body'),
            ])
            ->allowedSorts(['id', 'title'])
            ->jsonPaginate()
        ;
    }

    public function store(Request $request)
    {
        $validated_data = Announcement::validate($request);
        $announcement = new Announcement($validated_data);
        $announcement->save();

        return ['message' => 'Annoumcement was created successfully', 'data' => $announcement];
    }

    public function show(Request $request, Announcement $announcement)
    {
        return $announcement;
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated_data = Announcement::validate($request, $announcement);
        $announcement->update($validated_data);

        return ['message' => 'Announcement was updated successfully', 'data' => $announcement];
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return ['message' => 'Announcement was deleted successfully'];
    }
}
