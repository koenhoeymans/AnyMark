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
        "hyperlinkDefinition" => "\\AnyMark\\Pattern\\Patterns\\HyperlinkDefinition",
        "hyperlink" => "\\AnyMark\\Pattern\\Patterns\\Hyperlink",
        "image" => "\\AnyMark\\Pattern\\Patterns\\Image",
        "list" => "\\AnyMark\\Pattern\\Patterns\\TextualList",
        "manualHtmlBlock" => "\\AnyMark\\Pattern\\Patterns\\ManualHtmlBlock",
        "manualHtmlInline" => "\\AnyMark\\Pattern\\Patterns\\ManualHtmlInline",
        "newLine" => "\\AnyMark\\Pattern\\Patterns\\NewLine",
        "paragraph" => "\\AnyMark\\Pattern\\Patterns\\Paragraph",
        "strong" => "\\AnyMark\\Pattern\\Patterns\\Strong",
        "textualList" => "\\AnyMark\\Pattern\\Patterns\\TextualList",
    ],
    "alias" => [
        "block" => [
            "header",
            "horizontalRule",
            "textualList",
            "blockquote",
            "hyperlinkDefinition",
            "paragraph",
        ],
        "inline" => [
            "newLine",
            "autoLink",
            "image",
            "hyperlink",
            "emphasis",
            "strong",
        ],
    ],
    "tree" => [
        "root" => ["manualHtmlBlock", "block", "codeIndented"],
        "block" => ["manualHtmlBlock", "codeIndented", "manualHtmlInline", "codeInline", "inline"],
        "inline" => ["manualHtmlInline", "inline", "codeInline"],
        "textualList" => ["manualHtmlBlock", "codeIndented", "block", "manualHtmlInline", "codeInline", "inline"],
        "blockquote" => ["block"],
        "manualHtmlBlock" => ["manualHtmlBlock", "manualHtmlInline"],
        "manualHtmlInline" => ["manualHtmlInline", "inline"],
    ]
];
