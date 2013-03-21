<?php

// return [
// 	"alias" =>
// 	[
// 		"inline"			=> ["manualHtml", "autoLink", "image", "strong", "emphasis",
// 								"italic", "hyperlink", "definitionTerm", "newLine"],
// 		"composite-block"	=> ["definitionDescription", "manualHtml", "textualList",
// 								"blockquote", "note"],
// 		"block"				=> ["definitionList", "horizontalRule", "composite-block",
// 								"header", "codeWithTildes", "codeIndented",
// 								"codeInline", "paragraph"]
// 	],

// 	"patterns" =>
// 	[
// 		"root"				=> ["block", "inline"],
// 		"composite-block"	=> ["block", "inline"],
// 		"inline"			=> ["inline", "codeInline"],
// 		"header"			=> ["inline"],
// 		"definitionList"	=> ["definitionTerm", "definitionDescription"],
// 		"paragraph"			=> ["inline", "codeInline"]
// 	]
// ];

return [

"patterns" => [

	"root" => [
		"header",
		"horizontalRule",
		"textualList",
		"blockquote",
		"note",
		"definitionList",
		"codeWithTildes",
		"codeIndented",
		"manualHtml",
		"paragraph",
		"autoLink",
		"image",
		"strong",
		"emphasis",
		"italic",
		"hyperlink"
	],

	"manualHtml" => [
		"header",
		"horizontalRule",
		"textualList",
		"blockquote",
		"note",
		"definitionList",
		"codeWithTildes",
		"codeIndented",
		"manualHtml",
		"paragraph",
		"autoLink",
		"image",
		"strong",
		"emphasis",
		"italic",
		"hyperlink"
	],

	"note" => [
		"manualHtml",
		"header",
		"horizontalRule",
		"textualList",
		"blockquote",
		"note",
		"definitionList",
		"codeWithTildes",
		"codeIndented",
		"paragraph",
		"newLine",
		"codeInline",
		"autoLink",
		"image",
		"strong",
		"emphasis",
		"italic",
		"hyperlink"
	],

	"paragraph" => [
		"manualHtml",
		"newLine",
		"codeInline",
		"autoLink",
		"image",
		"strong",
		"emphasis",
		"italic",
		"hyperlink"
	],

	"definitionList" => [
		"definitionTerm",
		"definitionDescription"
	],

	"definitionTerm" => [
		"manualHtml",
		"newLine",
		"codeInline",
		"autoLink",
		"image",
		"strong",
		"emphasis",
		"italic",
		"hyperlink"
	],

	"definitionDescription" => [
		"manualHtml",
		"header",
		"horizontalRule",
		"textualList",
		"blockquote",
		"note",
		"definitionList",
		"codeWithTildes",
		"codeIndented",
		"paragraph",
		"newLine",
		"codeInline",
		"autoLink",
		"image",
		"strong",
		"emphasis",
		"italic",
		"hyperlink"
	],

	"textualList" => [
		"manualHtml",
		"header",
		"horizontalRule",
		"textualList",
		"blockquote",
		"note",
		"definitionList",
		"codeWithTildes",
		"codeIndented",
		"paragraph",
		"newLine",
		"codeInline",
		"autoLink",
		"image",
		"strong",
		"emphasis",
		"italic",
		"hyperlink"
	],

	"blockquote" => [
		"manualHtml",
		"header",
		"horizontalRule",
		"textualList",
		"note",
		"definitionList",
		"codeWithTildes",
		"codeIndented",
		"blockquote",
		"paragraph",
		"newLine",
		"codeInline",
		"autoLink",
		"image",
		"strong",
		"emphasis",
		"italic",
		"hyperlink"
	],

	"hyperlink" => [
		"strong",
		"emphasis",
		"italic"
	],

	"strong" => [
		"manualHtml",
		"strong",
		"emphasis",
		"italic",
		"hyperlink"
	],

	"emphasis" => [
		"manualHtml",
		"strong",
		"emphasis",
		"italic",
		"hyperlink"
	],

	"italic" => [
		"manualHtml",
		"strong",
		"emphasis",
		"italic",
		"hyperlink"
	]
]

];