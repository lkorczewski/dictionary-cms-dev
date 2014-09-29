<?php

namespace DCMS;

require_once 'dictionary/dictionary.php';

require_once 'dictionary/element.php';
require_once 'dictionary/value.php';
require_once 'dictionary/node.php';

require_once 'dictionary/entry.php';
require_once 'dictionary/sense.php';
require_once 'dictionary/phrase.php';

require_once 'dictionary/headword.php';
require_once 'dictionary/pronunciation.php';
require_once 'dictionary/category_label.php';
require_once 'dictionary/form.php';
require_once 'dictionary/context.php';
require_once 'dictionary/translation.php';

require_once 'dictionary/traits/has_senses.php';
require_once 'dictionary/traits/has_phrases.php';

require_once 'dictionary/traits/has_headwords.php';
require_once 'dictionary/traits/has_pronunciations.php';
require_once 'dictionary/traits/has_category_label.php';
require_once 'dictionary/traits/has_forms.php';
require_once 'dictionary/traits/has_context.php';
require_once 'dictionary/traits/has_translations.php';

require_once 'include/localization.php';

require_once 'include/layouts/HTML_layout.php';
require_once 'include/layouts/edition_buttons.php';

use Dictionary\Category_Label;
use Dictionary\Context;
use Dictionary\Element;
use Dictionary\Value;
use Dictionary\Node;

use Dictionary\Entry;
use Dictionary\Sense;
use Dictionary\Phrase;
use Dictionary\Form;
use Dictionary\Headword;
use Dictionary\Pronunciation;
use Dictionary\Translation;

use Dictionary\Node_With_Senses;
use Dictionary\Node_With_Phrases;
use Dictionary\Node_With_Headwords;
use Dictionary\Node_With_Pronunciations;
use Dictionary\Node_With_Category_Label;
use Dictionary\Node_With_Forms;
use Dictionary\Node_With_Context;
use Dictionary\Node_With_Translations;

class Edition_Layout extends HTML_Layout {
	use Edition_Buttons;
	
	protected $depth = 0;
	
	//--------------------------------------------------------------------
	// entry parser
	//--------------------------------------------------------------------
	
	protected function parse_entry(Entry $entry){
		
		$this->make_container($entry, function() use($entry) {
			
			$this->parse_headwords($entry);
			
			$this->make_content($entry, function() use($entry) {
				
				$this->parse_pronunciations($entry);
				$this->parse_category_labels($entry);
				$this->parse_forms($entry);
				$this->parse_translations($entry);
				$this->parse_phrases($entry);
				$this->parse_senses($entry);
				
			});
			
		});
		
	}
	
	//--------------------------------------------------------------------
	// sense nest parser
	//--------------------------------------------------------------------
	
	protected function parse_senses(Node_With_Senses $node){
		
		$this->parse_collection($node, 'senses');
		
		// new sense
		$this->output .= $this->make_button_bar($node, [
			'css_name'  => 'sense',
			'js_name'   => 'Sense',
			'label'     => 'add sense',
		]);
		
	}
	
	//--------------------------------------------------------------------
	// sense parser
	//--------------------------------------------------------------------
	
	protected function parse_sense(Sense $sense){
		
		$this->make_container($sense, function() use($sense) {
			
			$this->make_bar($sense, function() use($sense) {
				
				$this->make_bar_element($sense, function() use($sense) {
						$this->output .= $sense->get_label();
				}, 'sense_label');
				
				$this->make_buttons(function() use($sense) {
					$this->output .=
						$this->make_move_node_up_button($sense).
						$this->make_move_node_down_button($sense).
						$this->make_delete_node_button($sense);
				});
				
			}, 'sense_label');
			
			$this->make_content($sense, function() use($sense) {
				$this->parse_category_labels($sense);
				$this->parse_forms($sense);
				$this->parse_contexts($sense);
				$this->parse_translations($sense);
				$this->parse_phrases($sense);
				$this->parse_senses($sense);
			});
			
		});
		
	}
	
	//--------------------------------------------------------------------
	// phrase nest parser
	//--------------------------------------------------------------------
	
	protected function parse_phrases(Node_With_Phrases $node){
		
		// phrases
		$this->parse_collection($node, 'phrases');
		
		// new phrase
		$this->output .= $this->make_button_bar($node, [
			'css_name'  => 'phrase',
			'js_name'   => 'Phrase',
			'label'     => 'add phrase',
		]);
		
	}
	
	//--------------------------------------------------------------------
	// phrase parser
	//--------------------------------------------------------------------
	
	protected function parse_phrase(Phrase $phrase){
		
		$this->make_container($phrase, function() use($phrase) {
			
			$this->make_bar($phrase, function() use($phrase) {
			
				$this->make_editable_bar_element($phrase, function() use($phrase) {
					$this->output .= $phrase->get();
				});
				
				$this->make_buttons(function() use($phrase) {
					
					$this->output .=
						'<button class="button edit" onclick="editPhrase(this.parentNode.parentNode, ' .
							$phrase->get_node_id() .
						')">' .
							$this->localization->get_text('edit') .
						'</button>' . "\n" .
						$this->make_move_node_up_button($phrase).
						$this->make_move_node_down_button($phrase).
						$this->make_delete_node_button($phrase);
				});
				
			});
			
			$this->make_content($phrase, function() use($phrase) {
				$this->parse_translations($phrase);
			});
			
		});
		
	}
	
	//--------------------------------------------------------------------
	// headword nest parser
	//--------------------------------------------------------------------
	
	protected function parse_headwords(Node_With_Headwords $node){
		
		$this->parse_collection($node, 'headwords');
		
		// new headword
		$this->output .= $this->make_button_bar($node, [
			'css_name'  => 'headword',
			'js_name'   => 'Headword',
			'label'     => 'add headword',
		]);
		
	}
	
	//--------------------------------------------------------------------
	// headword parser
	//--------------------------------------------------------------------
	
	protected function parse_headword(Headword $headword){
		$this->make_value_bar($headword);
	}
	
	//--------------------------------------------------------------------
	// pronunciation nest parser
	//--------------------------------------------------------------------
	
	protected function parse_pronunciations(Node_With_Pronunciations $node){
		
		$this->parse_collection($node, 'pronunciations');
		
		// new pronunciation
		$this->output .= $this->make_button_bar($node, [
			'css_name'  => 'pronunciation',
			'js_name'   => 'Pronunciation',
			'label'     => 'add pronunciation',
		]);
	}
	
	//--------------------------------------------------------------------
	// pronunciation parser
	//--------------------------------------------------------------------
	
	protected function parse_pronunciation(Pronunciation $pronunciation){
		$this->make_value_bar($pronunciation);
	}
	
	//--------------------------------------------------------------------
	// category label nest parser
	//--------------------------------------------------------------------
	
	protected function parse_category_labels(Node_With_Category_Label $node){
		
		$category_label = $node->get_category_label();
		
		$this->output .=
			'<div class="category_labels">' . "\n";
		
		if($category_label){
			$this->parse_category_label($category_label, $node);
		}
		
		$this->output .=
			'</div>' . "\n";
		
		if(!$category_label){
			$this->output .= $this->make_button_bar($node, [
				'css_name'  => 'category_label',
				'js_name'   => 'CategoryLabel',
				'label'     => 'add category label',
			]);
		}
		
	}
	
	//--------------------------------------------------------------------
	// category label parser
	//--------------------------------------------------------------------
	
	protected function parse_category_label(Category_Label $category_label, Node_With_Category_Label $parent_node){
		$this->make_value_bar($category_label, $this->make_two_buttons($category_label, $parent_node));
	}
	
	//--------------------------------------------------------------------
	// form nest parser
	//--------------------------------------------------------------------
	
	protected function parse_forms(Node_With_Forms $node){
		
		$this->parse_collection($node, 'forms');
		
		// new form
		$this->output .= $this->make_button_bar($node, [
			'css_name'  => 'form',
			'js_name'   => 'Form',
			'label'     => 'add form',
		]);
		
	}
	
	//--------------------------------------------------------------------
	// form parser
	//--------------------------------------------------------------------
	
	protected function parse_form(Form $form){
		
		$this->make_bar($form, function() use($form) {
			
			// should be rewritten on JS level
			$this->output .=
				'<div class="bar_element form_label" onclick="editForm(this.parentNode, ' .
				$form->get_id() .
				', \'label\')">' .
					$form->get_label() .
				'</div>' . "\n";
			
			$this->make_editable_bar_element($form, function() use($form) {
				$form->get();
			});
			
			$this->make_buttons(function() use($form){
				
				$this->output .=
					$this->make_form_button($form, [
						'class'     => 'edit',
						'function'  => 'editForm',
						'label'     => 'edit',
					]).
					
					$this->make_form_button($form, [
						'class'     => 'move_up',
						'function'  => 'moveFormUp',
						'label'     => 'up',
					]).
					
					$this->make_form_button($form, [
						'class'     => 'move_down',
						'function'  => 'moveFormDown',
						'label'     => 'down',
					]).
					
					$this->make_form_button($form, [
						'class'     => 'delete',
						'function'  => 'deleteForm',
						'label'     => 'delete',
					]);
				
			});
			
		});
		
	}
	
	//--------------------------------------------------------------------
	// context nest parser
	//--------------------------------------------------------------------
	
	protected function parse_contexts(Node_With_Context $node){
		
		$context = $node->get_context();
		
		$this->output .=
			'<div class="contexts">' . "\n";
		
		if($context){
			$this->parse_context($context, $node);
		}
		
		$this->output .=
			'</div>' . "\n";
		
		if(!$context){
			$this->output .= $this->make_button_bar($node, [
				'css_name'  => 'context',
				'js_name'   => 'Context',
				'label'     => 'add context',
			]);
		}
		
	}
	
	//--------------------------------------------------------------------
	// context parser
	//--------------------------------------------------------------------
	
	protected function parse_context(Context $context, Node_With_Context $parent_node){
		$this->make_value_bar($context, $this->make_two_buttons($context, $parent_node));
	}
	
	//--------------------------------------------------------------------
	// translation nest parser
	//--------------------------------------------------------------------
	
	protected function parse_translations(Node_With_Translations $node){
		
		$this->parse_collection($node, 'translations');
		
		// new translation
		$this->output .= $this->make_button_bar($node, [
			'css_name'  => 'translation',
			'js_name'   => 'Translation',
			'label'     => 'add translation',
		]);
		
	}
	
	//--------------------------------------------------------------------
	// translation parser
	//--------------------------------------------------------------------
	
	protected function parse_translation(Translation $translation){
		$this->make_value_bar($translation);
	}
	
	//====================================================================
	// HTML templates
	//====================================================================
	
	//--------------------------------------------------------------------
	// container
	//--------------------------------------------------------------------
	
	protected function make_container(Node $node, callable $content_function){
		$this->output .= '<div class="container ' . $node->get_snakized_name() . '_container">' . "\n";
		$content_function();
		$this->output .= '</div>' . "\n";
	}
	
	//--------------------------------------------------------------------
	// content
	//--------------------------------------------------------------------
	
	protected function make_content(Node $node, callable $content_function){
		$this->output .= '<div class="content ' . $node->get_snakized_name() . '_content">' . "\n";
		$content_function();
		$this->output .= '</div>' . "\n";
	}
	
	//--------------------------------------------------------------------
	// value bar
	//--------------------------------------------------------------------
	
	protected function make_value_bar(Value $value, $buttons = null){
		if($buttons === null){
			$buttons = $this->make_four_buttons($value);
		}
		
		$this->make_bar($value, function() use($value, $buttons) {
			$this->make_editable_bar_element($value, function() use($value){
				$this->output .= $value->get();
			});
			
			$this->output .= $buttons;
		});
	}
	
	//--------------------------------------------------------------------
	// bar
	//--------------------------------------------------------------------
	
	protected function make_bar(Element $element, callable $content_function, $class_name = null){
		if(!$class_name){
			$class_name = $element->get_snakized_name();
		}
		
		$this->output .=
			'<div' .
			' class="bar ' . $class_name . '_bar"' .
			' onmouseover="showButtons(this)"' .
			' onmouseout="hideButtons(this)"' .
			'>' . "\n";
		$content_function();
		$this->output .= '</div>' . "\n";
	}
	
	//--------------------------------------------------------------------
	// editable bar element
	//--------------------------------------------------------------------
	
	protected function make_editable_bar_element(Element $element, callable $content_function, $class_name = null){
		if(!$class_name){
			$class_name = $element->get_snakized_name();
		}
		
		$this->output .=
			'<div' .
			' class="bar_element ' . $class_name . '"'.
			' onclick="edit' . $element->get_camelized_name() . '(this.parentNode, ' . $element->get_id() . ')"' .
			'>';
		$content_function();
		$this->output .= '</div>' . "\n";
	}
	
	//--------------------------------------------------------------------
	// bar element
	//--------------------------------------------------------------------
	
	protected function make_bar_element(Element $element, callable $content_function, $class_name = null){
		if(!$class_name){
			$class_name = $element->get_snakized_name();
		}
		
		$this->output .=
			'<div' .
			' class="bar_element ' . $class_name . '"'.
			'>';
		$content_function();
		$this->output .= '</div>' . "\n";
	}
	
	//--------------------------------------------------------------------
	
	protected function make_buttons(callable $content_function){
		$this->output .= '<div class="buttons">' . "\n";
		$content_function();
		$this->output .= '</div>' . "\n";
	}
	
}

