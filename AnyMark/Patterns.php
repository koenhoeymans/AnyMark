<?php

return [
	"implementations" => [
		"autoLink" => "\\AnyMark\\Pattern\\Patterns\\AutoLink",
		"blockquote" => "\\AnyMark\\Pattern\\Patterns\\Blockquote",
		"codeIndented" => "\\AnyMark\\Pattern\\Patterns\\CodeIndented",
		"codeInline" => "\\AnyMark\\Pattern\\Patterns\\CodeInline",
		"emphasis" => "\\AnyMark\\Pattern\\Patterns\\Emphasis",
		"header" => "\\AnyMark\\Pattern\\Patterns\\Header",
		"horizontalRule" => "\\AnyMark\\Pattern\\Patterns\\HorizontalRule",
		"hyperlink" => "\\AnyMark\\Pattern\\Patterns\\Hyperlink",
		"image" => "\\AnyMark\\Pattern\\Patterns\\Image",
		"italic" => "\\AnyMark\\Pattern\\Patterns\\Italic",
		"list" => "\\AnyMark\\Pattern\\Patterns\\TextualList",
		"manualHtml" => "\\AnyMark\\Pattern\\Patterns\\ManualHtml",
		"newLine" => "\\AnyMark\\Pattern\\Patterns\\NewLine",
		"paragraph" => "\\AnyMark\\Pattern\\Patterns\\Paragraph",
		"strong" => "\\AnyMark\\Pattern\\Patterns\\Strong",
		"textualList" => "\\AnyMark\\Pattern\\Patterns\\TextualList"
	],
	"alias" => [
		"block" => [
			"header",
			"horizontalRule",
			"textualList",
			"blockquote",
			"manualHtml",
			"paragraph"
		],
		"inline" => [
			"manualHtml",
			"newLine",
			"autoLink",
			"image",
			"strong",
			"emphasis",
			"italic",
			"hyperlink"
		]
	],
	"tree" => [
		"root" => ["block", "codeIndented"],
		"block" => ["inline", "codeIndented", "codeInline"],
		"inline" => ["inline", "codeInline"],
		"textualList" => ["block"],
		"blockquote" => ["block"],
		"manualHtml" => ["block"]
	]
];