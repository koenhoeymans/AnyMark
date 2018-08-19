<?php

namespace AnyMark\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\Element;

class TextualList extends Pattern
{
    protected $ol_marker = "\d+[\.]";

    protected $ul_marker = "[*+-]";

    public function getRegex(): string
    {
        return
        '@
	(?<=^|^\n|\n\n|(?<on_next_line>\n))
				(?=(?<indentation>[ ]{0,4})\S)	# note different handling for indentation
												# sublists on handling match

	(?<list>(?J)

		(
			(?<ol_indentation>[ \t]*)					# indentation
			(?<ol>' . $this->ol_marker . ')			# marker
			(?<ol_space_after_marker>[ \t]+)			# space after marker
			\S.*									# text
			(										# continuation of list
				\n(?!' . $this->ul_marker . ').+	# -> on next line
				|
				\n\n\g{ol_indentation}						# -> white line
														#	+ indent
					(' . $this->ol_marker . ')?	#	+ marker (when new item, else paragraph in item)
						\g{ol_space_after_marker}			#	+ space
 				.+										#	+ text
			)*
		)

		|

		(
			(?<ul_indentation>[ \t]*)
			(?<ul>' . $this->ul_marker . ')
			(?<ul_space_after_marker>[ \t]+)
			\S.*
			(
				\n(?!' . $this->ol_marker . ').+
				|
				\n\n\g{ul_indentation}

					(' . $this->ul_marker . ')?
						\g{ul_space_after_marker}
 				.+
			)*
		)
	)

		(?=\n\n|\n$|$|\n' . $this->ol_marker . '|\n' . $this->ul_marker . ')
		@x';
    }

    public function handleMatch(
        array $match,
        Element $parent = null,
        \AnyMark\Api\Pattern $parentPattern = null
    ): ?\ElementTree\Component {
        # different handling of allowed indentation for sublist
        if (($parentPattern != $this)
            && !empty($match['on_next_line'])
        ) {
            return null;
        }
        if (($parentPattern != $this)
            && ($match['indentation'] === '    ')
        ) {
            return null;
        }

        $listType = (isset($match['ol']) && ($match['ol'] !== '')) ? 'ol' : 'ul';
        $indentation = ($listType === 'ol')
            ? $match['ol_indentation']
            : $match['ul_indentation'];

        # unindent
        $items = preg_replace(
            "@(\n|^)" . $indentation . "@",
            "\${1}",
            $match['list']
        );

        $list = $this->createElement($listType);
        $this->createListItems($items, $list);

        return $list;
    }

    private function createListItems($items, Element $list)
    {
        preg_match_all($this->getItemRegex(), $items, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $this->handleItemMatch($match, $list);
        }
    }

    public function getItemRegex()
    {
        return
        '@
			(?<=(?<para_before>\n\n)|^|\n)

			# the structure of the list item
			# ------------------------------
			(
			(?<marker_indent>[ ]{0,3})	# indentation of the list marker
			(' . $this->ol_marker . '|' . $this->ul_marker . ')		# markers
			(?<text_indent>[ ]{0,3}|\t|(?=\n))	# spaces/tabs
			)

			# the list item content
			# ---------------------
			(?<content>
			.*						# text of first line
				(						# optionally more lines
					\n							# continue on next line unindented
					(?!
						\g{marker_indent}
						(' . $this->ol_marker . '|' . $this->ul_marker . ')
					)
					.+
					|							# or indented
					\n\n\g{marker_indent}
						(?!(' . $this->ol_marker . '|' . $this->ul_marker . '))
					.+
				)*
			)
			(?=(?<para_after>\n\n|\n$)?)
			@x';
    }

    public function handleItemMatch(array $match, Element $parent)
    {
        $paragraph = (($match['para_before'] == "\n\n") || isset($match['para_after']))
            ? "\n\n" : "";
        $content = preg_replace(
            "@\n" . $match['marker_indent'] . "[ ]" . $match['text_indent'] . "@",
            "\n",
            $match['content']
        );

        $li = $this->createElement('li');

        if ($paragraph !== '' || $content !== '') {
            $li->append($this->createText($paragraph . $content . $paragraph));
        }

        $parent->append($li);
    }
}
