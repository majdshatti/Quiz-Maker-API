<?php

/**
 * Filter by a number with the ablility of using gt|lt|gte|lte operators
 *
 * @param query $query
 * @param array|int $urlParam
 *
 * @return void
 */
if (!function_exists("numberFilter")) {
    function numberFilter($query, $urlParam)
    {
        // Check if param is passed as an array
        if (is_array($urlParam)) {
            // Check if array has on of the gt|lt|gte|lte
            if (array_key_exists("gt", $urlParam)) {
                $query->when(
                    $urlParam["gt"],
                    fn($query, $rank) => $query->where("rank", ">", $rank)
                );
            } elseif (array_key_exists("gte", $urlParam)) {
                $query->when(
                    $urlParam["gte"],
                    fn($query, $rank) => $query->where("rank", ">=", $rank)
                );
            } elseif (array_key_exists("lt", $urlParam)) {
                $query->when(
                    $urlParam["lt"],
                    fn($query, $rank) => $query->where("rank", "<", $rank)
                );
            } elseif (array_key_exists("lte", $urlParam)) {
                $query->when(
                    $urlParam["lte"],
                    fn($query, $rank) => $query->where("rank", "<=", $rank)
                );
            }
        } else {
            $query->when(
                $urlParam ?? false,
                fn($query, $rank) => $query->where("rank", "=", $rank)
            );
        }
    }
}

/**
 * String filtering using `like` operator
 *
 * @param query  $query
 * @param string $col      refers to a collection
 * @param string $urlParam
 *
 * @return void
 */
if (!function_exists("stringFilter")) {
    function stringFilter($query, $col, $urlParam)
    {
        /* Filter by string param */
        $query->when(
            $urlParam,
            fn($query, $value) => $query->where(
                $col,
                "like",
                "%" . $value . "%"
            )
        );
    }
}

/**
 * Sorts either by default or by passed url params in both asc/desc methods
 *
 * @param query $query
 * @param Request $request
 *
 * @return void
 */
if (!function_exists("sortFilter")) {
    function sortFilter($query, $request)
    {
        // Check sort
        if ($request->query("sort_a")) {
            $sort = $request->query("sort_a");
            $sortMethod = "asc";
        } elseif ($request->query("sort_d")) {
            $sort = $request->query("sort_d");
            $sortMethod = "desc";
        }

        $sort = $sort ?? "created_at";
        $sortMethod = $sortMethod ?? "desc";

        $query->orderBy($sort, $sortMethod);
    }
}

?>
