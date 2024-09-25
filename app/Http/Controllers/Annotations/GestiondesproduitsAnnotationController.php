<?php

namespace App\Http\Controllers\Annotations ;

/**
 * @OA\Security(
 *     security={
 *         "BearerAuth": {}
 *     }),

 * @OA\SecurityScheme(
 *     securityScheme="BearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"),

 * @OA\Info(
 *     title="Your API Title",
 *     description="Your API Description",
 *     version="1.0.0"),

 * @OA\Consumes({
 *     "multipart/form-data"
 * }),

 *

 * @OA\GET(
 *     path="/api/produits",
 *     summary="Afficher la liste ",
 *     description="",
 *         security={
 *    {       "BearerAuth": {}}
 *         },
 * @OA\Response(response="200", description="OK"),
 * @OA\Response(response="404", description="Not Found"),
 * @OA\Response(response="500", description="Internal Server Error"),
 *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
 * ),
 *     tags={"Gestion des produits"},
*),


 * @OA\POST(
 *     path="/api/produits",
 *     summary="Ajouter",
 *     description="",
 *         security={
 *    {       "BearerAuth": {}}
 *         },
 * @OA\Response(response="201", description="Created successfully"),
 * @OA\Response(response="400", description="Bad Request"),
 * @OA\Response(response="401", description="Unauthorized"),
 * @OA\Response(response="403", description="Forbidden"),
 *     @OA\Parameter(in="path", name="image", required=false, @OA\Schema(type="text")
 * ),
 *     @OA\Parameter(in="path", name="nom", required=false, @OA\Schema(type="string")
 * ),
 *     @OA\Parameter(in="path", name="description", required=false, @OA\Schema(type="string")
 * ),
 *     @OA\Parameter(in="path", name="prix", required=false, @OA\Schema(type="string")
 * ),
 *     @OA\Parameter(in="path", name="quantite", required=false, @OA\Schema(type="string")
 * ),
 *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
 * ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 type="object",
 *                 properties={
 *                     @OA\Property(property="categorie_id", type="integer"),
 *                     @OA\Property(property="image", type="string"),
 *                     @OA\Property(property="description", type="string"),
 *                     @OA\Property(property="prix", type="integer"),
 *                     @OA\Property(property="quantite", type="integer"),
 *                     @OA\Property(property="reference", type="string"),
 *                 },
 *             ),
 *         ),
 *     ),
 *     tags={"Gestion des produits"},
*),


 * @OA\GET(
 *     path="/api/produits/1",
 *     summary="Voir detail",
 *     description="",
 *         security={
 *    {       "BearerAuth": {}}
 *         },
 * @OA\Response(response="200", description="OK"),
 * @OA\Response(response="404", description="Not Found"),
 * @OA\Response(response="500", description="Internal Server Error"),
 *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
 * ),
 *     tags={"Gestion des produits"},
*),


 * @OA\DELETE(
 *     path="/api/produits/3",
 *     summary="Supprimer",
 *     description="",
 *         security={
 *    {       "BearerAuth": {}}
 *         },
 * @OA\Response(response="204", description="Deleted successfully"),
 * @OA\Response(response="401", description="Unauthorized"),
 * @OA\Response(response="403", description="Forbidden"),
 * @OA\Response(response="404", description="Not Found"),
 *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
 * ),
 *     tags={"Gestion des produits"},
*),


 * @OA\PUT(
 *     path="/api/produits/5",
 *     summary="Modifier",
 *     description="",
 *         security={
 *    {       "BearerAuth": {}}
 *         },
 * @OA\Response(response="200", description="OK"),
 * @OA\Response(response="404", description="Not Found"),
 * @OA\Response(response="500", description="Internal Server Error"),
 *     @OA\Parameter(in="header", name="User-Agent", required=false, @OA\Schema(type="string")
 * ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/x-www-form-urlencoded",
 *             @OA\Schema(
 *                 type="object",
 *                 properties={
 *                     @OA\Property(property="categorie_id", type="integer"),
 *                     @OA\Property(property="image", type="string"),
 *                     @OA\Property(property="description", type="string"),
 *                     @OA\Property(property="prix", type="integer"),
 *                     @OA\Property(property="quantite", type="integer"),
 *                     @OA\Property(property="reference", type="string"),
 *                 },
 *             ),
 *         ),
 *     ),
 *     tags={"Gestion des produits"},
*),


*/

 class GestiondesproduitsAnnotationController {}
