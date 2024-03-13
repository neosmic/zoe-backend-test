<?php

namespace App\Services;


class RequestSecurityPrice
{
    public static function data(string $slug): array
    {
        $data = [
            'mutual_funds' => [
                [
                    "symbol" => "APPL",
                    "price" => 89.99,
                    "last_price_datetime" => "2023-09-29T17:31:18-04:00"
                ],
                [
                    "symbol" => "TSLA",
                    "price" => 12.78,
                    "last_price_datetime" => "2023-08-18T17:32:11-04:00"
                ]
            ]
        ];

        return $data[$slug];
    }
}
