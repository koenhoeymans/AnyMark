<?php

return [
	"alias" => [
		"foobar" => [
			"AnyMark\\Pattern\\Patterns\\Italic",
			"AnyMark\\Pattern\\Patterns\\Strong"
		]
	],

	"patterns" => [
		"root" => ["foobar"],
		"foobar" => ["foobar"],
		"AnyMark\\Pattern\\Patterns\\Strong" => ["AnyMark\\Pattern\\Patterns\\Emphasis"]
	]
];