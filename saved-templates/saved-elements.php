<?php
$elements = array();

$elements[] = array(
  'layout_name' => 'Confidence Quotes',
  'content' => <<<CONTENT
[fusion_quotes color_progress_bar="#e91e63" color_quote_text="#ffffff" color_quote_title="#fff4af" bg_pattern="pattern10"][fusion_quote title="Golda Meir"]

Trust yourself. Create the kind of self that you will be happy to live with all your life.

[/fusion_quote][fusion_quote title="Theodore Roosevelt"]

Each time we face our fear, we gain strength, courage and confidence in the doing.

[/fusion_quote][fusion_quote title="Peter McIntyre"]

Confidence comes not from always being right, but from not fearing to be wrong.

[/fusion_quote][/fusion_quotes]
CONTENT
);

$elements[] = array(
  'layout_name' => 'Technology Quotes',
  'content' => <<<CONTENT
[fusion_quotes color_progress_bar="#a31366" color_quote_text="#002f51" color_quote_title="#3e5921" bg_pattern="pattern8"][fusion_quote title="Thomas Sowell"]

The march of science and technology does not imply growing intellectual complexity in the lives of most people. It often means the opposite.

[/fusion_quote][fusion_quote title="Carl Sagan"]

We live in a society exquisitely dependent on science and technology, in which hardly anyone knows anything about science and technology.

[/fusion_quote][fusion_quote title="Bill Gates"]

Technology is just a tool. In terms of getting the kids working together and motivating them, the teacher is the most important.

[/fusion_quote][fusion_quote title="William Gibson"]

It's impossible to move, to live, to operate at any level without leaving traces, bits, seemingly meaningless fragments of personal information.

[/fusion_quote][fusion_quote title="Freeman Dyson"]

Technology is a gift of God. After the gift of life it is perhaps the greatest of God's gifts. It is the mother of civilizations, of arts and of sciences.

[/fusion_quote][/fusion_quotes]
CONTENT
);


if ( function_exists( 'fusion_builder_create_layout' ) ) {
  foreach ($elements as $key => $element ) {
    fusion_builder_create_layout( 'fusion_element', $element['layout_name'], $element['content'], '', 'element_category', 'elements' );
  }
}
