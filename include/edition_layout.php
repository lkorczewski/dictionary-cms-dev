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

class Edition_Layout {
	
	private $output;
	private $depth;
	private $localization;
	
	//--------------------------------------------------------------------
	// constructor
	//--------------------------------------------------------------------
	
	function __construct(Localization $localization){
		$this->output = '';
		$this->depth = 0;
		$this->localization = $localization;
	}
	
	//--------------------------------------------------------------------
	// public parser
	//--------------------------------------------------------------------
	
	function parse(Entry $entry){
		
		$this->output = '';
		$this->parse_entry($entry);
		
		return $this->output;
	}
	
	//--------------------------------------------------------------------
	// entry parser
	//--------------------------------------------------------------------

	private function parse_entry(Entry $entry){
		$this->output .= '<div class="entry_container">' . "\n";
		
		$this->parse_headwords($entry);
		
		$this->output .= '<div class="content entry_content">' . "\n";
		
		$this->parse_pronunciations($entry);
		$this->parse_category_label($entry);
		$this->parse_forms($entry);
		$this->parse_translations($entry);
		$this->parse_phrases($entry);
		$this->parse_senses($entry);
		
		$this->output .= '</div>' . "\n"; // .content.entry_content
		
		$this->output .= '</div>' . "\n"; // .entry_container
	}
	
	//--------------------------------------------------------------------
	// sense nest parser
	//--------------------------------------------------------------------
	
	private function parse_senses(Node_With_Senses $node){
		
		// senses
		$this->output .= '<div class="senses">' . "\n";
		foreach($node->get_senses() as $sense){
			$this->parse_sense($sense);
		}
		$this->output .= '</div>' . "\n";
		
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
	
	private function parse_sense(Sense $sense){
		
		$this->output .= '<div class="sense_container">' . "\n";
		
		// sense 
		$this->output .=
			'<div class="bar sense_label_bar" onmouseover="showButtons(this)" onmouseout="hideButtons(this)">' . "\n" .
				
				'<div class="bar_element sense_label">' .
					$sense->get_label() .
				'</div>' . "\n" .
			
				'<div class="buttons">' . "\n" .
					$this->make_move_node_up_button($sense).
					$this->make_move_node_down_button($sense).
					$this->make_delete_node_button($sense).
				'</div>' . "\n" .
				
			'</div>' . "\n";
		
		$this->output .= '<div class="content sense_content">' . "\n";
		
		$this->parse_category_label($sense);
		$this->parse_forms($sense);
		$this->parse_context($sense);
		$this->parse_translations($sense);
		$this->parse_phrases($sense);
		$this->parse_senses($sense);
		
		$this->output .= '</div>' . "\n"; // .content.sense_content
		
		$this->output .= '</div>' . "\n"; // .sense_container
	}
	
	//--------------------------------------------------------------------
	// phrase nest parser
	//--------------------------------------------------------------------
	
	private function parse_phrases(Node_With_Phrases $node){
		
		// phrases
		$this->output .= '<div class="phrases">' . "\n";
		foreach($node->get_phrases() as $phrase){
			$this->parse_phrase($phrase);
		}
		$this->output .= '</div>' . "\n";
		
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
	
	private function parse_phrase(Phrase $phrase){
		
		$this->output .= '<div class="phrase_container">' . "\n";
		
		// phrase
		$this->output .=
			'<div class="bar phrase_bar" onmouseover="showButtons(this)" onmouseout="hideButtons(this)">' . "\n" .
				
				$this->make_bar_element($phrase).
				
				'<div class="buttons">' . "\n" .
					
					'<button class="button edit" onclick="editPhrase(this.parentNode.parentNode, ' .
						$phrase->get_node_id() .
					')">' .
						$this->localization->get_text('edit') .
					'</buton>' . "\n" .
					
					$this->make_move_node_up_button($phrase).
					$this->make_move_node_down_button($phrase).
					$this->make_delete_node_button($phrase).
					
				'</div>' . "\n" .
				
			'</div>' . "\n";
		
		$this->output .= '<div class="content phrase_content">' . "\n";
		
		$this->parse_translations($phrase);
		
		$this->output .= '</div>' . "\n"; // .content.phrase_content
		
		$this->output .= '</div>' . "\n"; // .phrase_container
		
	}
	
	//--------------------------------------------------------------------
	// headword nest parser
	//--------------------------------------------------------------------
	
	private function parse_headwords(Node_With_Headwords $node){
		
		$this->output .= '<div class="headwords">' . "\n";
		foreach($node->get_headwords() as $headword){
			$this->parse_headword($headword);
		}
		$this->output .= '</div>' . "\n";
		
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
	
	private function parse_headword(Headword $headword){
		$this->output .= $this->make_value_bar($headword);
	}
	
	//--------------------------------------------------------------------
	// pronunciation nest parser
	//--------------------------------------------------------------------
	
	private function parse_pronunciations(Node_With_Pronunciations $node){
		
		$this->output .= '<div class="pronunciations">' . "\n";
		foreach($node->get_pronunciations() as $pronunciation){
			$this->parse_pronunciation($pronunciation);
		}
		$this->output .= '</div>' . "\n";
		
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
	
	private function parse_pronunciation(Pronunciation $pronunciation){
		$this->output .= $this->make_value_bar($pronunciation);
	}
	
	//--------------------------------------------------------------------
	// category label parser
	//--------------------------------------------------------------------
	
	private function parse_category_label(Node_With_Category_Label $node){
		
		$category_label = $node->get_category_label();
		
		$this->output .=
			'<div class="category_labels">' . "\n";
		
		if($category_label){
			$this->output .=
				'<div class="bar category_label_bar" onmouseover="showButtons(this)" onmouseout="hideButtons(this)">' . "\n" .
					$this->make_bar_element($category_label).
					$this->make_two_buttons($node) .
				'</div>' . "\n";
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
	// form nest parser
	//--------------------------------------------------------------------
	
	private function parse_forms(Node_With_Forms $node){
		
		// forms
		$this->output .= '<div class="forms">' . "\n";
		foreach($node->get_forms() as $form){
			$this->parse_form($form);
		}
		$this->output .= '</div>' . "\n";
		
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
	
	private function parse_form(Form $form){
		$this->output .=
			'<div class="bar form_bar" onmouseover="showButtons(this)" onmouseout="hideButtons(this)">' . "\n" .
				
				'<div class="bar_element form_label" onclick="editForm(this.parentNode, ' .
				$form->get_id() .
				', \'label\')">' .
					$form->get_label() .
				'</div>' . "\n" .
				
				$this->make_bar_element($form).
				
				'<div class="buttons">' . "\n" .
					
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
					]).
					
				'</div>' . "\n" .
			'</div>' . "\n";
	}
	
	//--------------------------------------------------------------------
	// context parser
	//--------------------------------------------------------------------
	
	private function parse_context(Node_With_Context $node){
		
		$context = $node->get_context();
		
		$this->output .=
			'<div class="contexts">' . "\n";
		
		if($context){
			$this->output .=
				'<div class="bar context_bar" onmouseover="showButtons(this)" onmouseout="hideButtons(this)">' .
					$this->make_bar_element($context).
					$this->make_two_buttons($node) .
				'</div>' . "\n";
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
	// translation nest parser
	//--------------------------------------------------------------------
	
	private function parse_translations(Node_With_Translations $node){
		
		// translations
		$this->output .= '<div class="translations">' . "\n";
		foreach($node->get_translations() as $translation){
			$this->parse_translation($translation);
		}
		$this->output .= '</div>' . "\n";
		
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
	
	private function parse_translation(Translation $translation){
		$this->output .= $this->make_value_bar($translation);
	}
	
	//--------------------------------------------------------------------
	// generic value bar
	//--------------------------------------------------------------------
	
	private function make_value_bar(Value $value, $buttons = null){

		if($buttons === null){
			$buttons = $this->make_four_buttons($value);
		}

		$output =
			'<div' .
				' class="bar ' . $value->get_snakized_name() . '_bar"' .
				' onmouseover="showButtons(this)"' .
				' onmouseout="hideButtons(this)"' .
			'>' . "\n" .
				$this->make_bar_element($value) .
				$buttons .
			'</div>' . "\n";
		
		return $output;
	}
	
	//--------------------------------------------------------------------
	// generic bar element
	//--------------------------------------------------------------------
	// should be value, but extended because of phrase
	
	private function make_bar_element(Element $element){
		$output =
			'<div' .
				' class="bar_element ' . $element->get_snakized_name() . '"'.
				' onclick="edit' . $element->get_camelized_name() . '(this.parentNode, ' . $element->get_id() . ')"' .
			'>' .
				$element->get() .
			'</div>' . "\n";
		
		return $output;
	}
	
	//--------------------------------------------------------------------
	// two buttons
	//--------------------------------------------------------------------
	
	private function make_two_buttons(Node $parent_node){
		
		$output =
			'<div class="buttons">' . "\n" .
				
				'<button'.
					' class="button edit"' .
					' onclick="edit' . $parent_node->get_camelized_name(). '(this.parentNode.parentNode, ' . $parent_node->get_node_id() . ')"' .
				'>' .
					$this->localization->get_text('edit') .
				'</button>' . "\n" .
				
				'<button' .
					' class="button delete"' .
					' onclick="delete' . $parent_node->get_camelized_name(). '(this.parentNode.parentNode, ' . $parent_node->get_node_id() . ')"' .
				'>' .
					$this->localization->get_text('delete') .
				'</button>' . "\n" .
				
			'</div>' . "\n";
		
		return $output;
	}
	
	//--------------------------------------------------------------------
	// four buttons
	//--------------------------------------------------------------------
	
	private function make_four_buttons(Value $value){
		
		$output =
			'<div class="buttons">' . "\n" .
				
				$this->make_value_button($value, [
					'class'     => 'edit',
					'function'  => 'edit' . $value->get_camelized_name(),
					'label'     => 'edit',
				]) .
				
				$this->make_value_button($value, [
					'class'     => 'move_up',
					'function'  => 'move' . $value->get_camelized_name() . 'Up',
					'label'     => 'up',
				]) .
				
				$this->make_value_button($value, [
					'class'     => 'move_down',
					'function'  => 'move' . $value->get_camelized_name() . 'Down',
					'label'     => 'down',
				]) .
				
				$this->make_value_button($value, [
					'class'     => 'delete',
					'function'  => 'delete' . $value->get_camelized_name(),
					'label'     => 'delete',
				]) .
				
			'</div>' . "\n";
		
		return $output;
	}
	
	//--------------------------------------------------------------------
	// generic buttons
	//--------------------------------------------------------------------
	
	private function make_value_button(Value $value, array $parameters){
		$output = 
			'<button' .
				' class="button ' . $parameters['class'] . '"' .
				' onclick="' . $parameters['function']. '(this.parentNode.parentNode, ' . $value->get_id() . ')"' .
			'>' .
				$this->localization->get_text($parameters['label']) .
			'</button>' . "\n";
		
		return $output;
	}
	
	private function make_form_button(Form $value, array $parameters){
		$output =
			'<button' .
				' class="button ' . $parameters['class'] . '"' .
				' onclick="' . $parameters['function']. '(this.parentNode.parentNode, ' . $value->get_id() . ')"' .
			'>' .
				$this->localization->get_text($parameters['label']) .
			'</button>' . "\n";
			
		return $output;
	}
	
	private function make_node_button(Node $node, array $parameters){
		$output =
			'<button' .
				' class="button ' . $parameters['class'] . '"' .
				' onclick="' . $parameters['function']. '(this.parentNode.parentNode.parentNode, ' . $node->get_node_id() . ')"' .
			'>' .
				$this->localization->get_text($parameters['label']) .
			'</button>' . "\n";
		
		return $output;
	}
	
	//--------------------------------------------------------------------
	// buttons
	//--------------------------------------------------------------------
	
	private function make_move_node_up_button(Node $node){
		$output =
			$this->make_node_button($node, [
				'class'     => 'move_up',
				'function'  => 'move' . $node->get_camelized_name() . 'Up',
				'label'     => 'up',
			]);
		
		return $output;
	}
	
	private function make_move_node_down_button(Node $node){
		$output =
			$this->make_node_button($node, [
				'class'     => 'move_down',
				'function'  => 'move' . $node->get_camelized_name() . 'Down',
				'label'     => 'down',
			]);
		
		return $output;
	}
	
	private function make_delete_node_button(Node $node){
		$output = 
			$this->make_node_button($node, [
				'class'     => 'delete',
				'function'  => 'delete' . $node->get_camelized_name(),
				'label'     => 'delete',
			]);
		
		return $output;
	}
	
	//--------------------------------------------------------------------
	// generic button bar
	//--------------------------------------------------------------------
	
	private function make_button_bar(Node $node, array $parameters){
		$output =
			'<div class="button_bar ' . $parameters['css_name'] . '_button_bar">' .
			
			'<button'.
				' class="button add_' . $parameters['css_name'] . '"' .
				' onclick="add' . $parameters['js_name'] . '(this.parentNode.parentNode, ' . $node->get_node_id() . ')"' .
			'>' .
				$this->localization->get_text($parameters['label']) .
			'</button>' .
			
			'</div>' . "\n";
			
		return $output;
	}
}

