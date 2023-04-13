<?php
/*
 * @OA\Get(
 *     path="/api/v1/announcements/",
 *     summary="get list of all announcements",
 *      tags={"Announcements"},
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *           @OA\Schema(ref="#/components/schemas/Results"),
 *               @OA\Examples(example="result", summary="An result object.", value={"current_page":1,"to":10,"total":25}, ),
 *             )
 *     )
 * ,
 * @OA\Response(
 *         response=401,
 *         description="Unauthenticated"
 *     )
 * )
 */
