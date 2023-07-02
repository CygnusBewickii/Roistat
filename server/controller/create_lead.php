<?php

include __DIR__ . '/../bootstrap.php';

$data = [ 
    [
        "price" => (int)$_POST["price"],
        "_embedded" => [
            "contacts" => [
                [
                    "first_name" => $_POST["name"],
                    "custom_fields_values" => [
                        [
                            "field_code" => "EMAIL",
                            "values" => [
                                [
                                    "value" => $_POST["email"]
                                ]
                            ],
                        ],
                        [
                            "field_code" => "PHONE",
                            "values" => [
                                [
                                    "value" => $_POST["phone"]
                                ]
                            ],
                        ]
                    ],
                ]
            ]
        ]
    ]
];

$apiClient->leads->createLead($data);