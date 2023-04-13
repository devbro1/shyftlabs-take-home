<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class AnnouncementController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/announcements",
     *     summary="get list of all announcements",
     *     tags={"Announcements"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/v1/announcements",
     *     summary="create new announcement",
     *     tags={"Announcements"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated_data = Announcement::validate($request);
        $announcement = new Announcement($validated_data);
        $announcement->save();

        return ['message' => 'Annoumcement was created successfully', 'data' => $announcement];
    }

    /**
     * @OA\Get(
     *     path="/api/v1/announcements/{id}",
     *     summary="get a announcement",
     *     tags={"Announcements"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true, @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function show(Request $request, Announcement $announcement)
    {
        return $announcement;
    }

    /**
     * @OA\Put(
     *     path="/api/v1/announcements/{id}",
     *     summary="update an announcement",
     *     tags={"Announcements"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true, @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 ref="#/components/schemas/Announcement"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validated_data = Announcement::validate($request, $announcement);
        $announcement->update($validated_data);

        return ['message' => 'Announcement was updated successfully', 'data' => $announcement];
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/announcements/{id}",
     *     summary="delete an announcement",
     *     tags={"Announcements"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true, @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     )
     * )
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return ['message' => 'Announcement was deleted successfully'];
    }
}
