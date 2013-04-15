<?php

return [
	"implementations" =>
	[
		"italic" => "\\AnyMark\\Pattern\\Patterns\\Italic",
		"strong" => "\\AnyMark\\Pattern\\Patterns\\Strong"
	],
	"alias" =>
	[
		"foo" => ["strong"]
	],
	"tree" =>
	[
		"root" => ["italic", "foo"],
		"italic" => ["strong"],
		"foo" => ["foo", "italic"]
	]
];