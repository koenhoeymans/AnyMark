<?php

return [
    "implementations" => [
        "emphasis" => "\\AnyMark\\Pattern\\Patterns\\Emphasis",
        "strong" => "\\AnyMark\\Pattern\\Patterns\\Strong",
    ],
    "alias" => [
        "foo" => ["strong", "emphasis"],
    ],
    "tree" => [
        "root" => ["emphasis", "foo"],
        "emphasis" => ["strong"],
        "foo" => ["foo", "emphasis"],
    ]
];
