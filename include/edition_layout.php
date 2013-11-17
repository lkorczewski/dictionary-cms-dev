<?php

namespace DCMS;

require_once 'dictionary/dictionary.php';

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

class Edition_Layout{
	
	private $output;
	private $depth;
	private $localization;
	
	//--------------------------------------------------------------------
	// constructor
	//--------------------------------------------------------------------
	
	function __construct($localization){
		$this->output = '';
		$this->depth = 0;
		$this->localization = $localization;
	}
	
	//--------------------------------------------------------------------
	// public parser
	//--------------------------------------------------------------------

	function parse(\Dictionary\Entry $entry){
		
		$this->output = '';
		$this->parse_entry($entry);
		
		return $this->output;
	}
	
	//--------------------------------------------------------------------
	// entry parser
	//--------------------------------------------------------------------

	private function parse_entry(\Dictionary\Entry $entry){
		$this->output .= '<div class="entry_container">' . "\n";
		
		// headwords
		$this->parse_headwords($entry);
		
		$this->output .= '<div class="content entry_content">' . "\n";

		// pronunciations
		$this->parse_pronunciations($entry);
		
		// category label
		$this->parse_category_label($entry);
		
		// forms
		$this->parse_forms($entry);
		
		// translations
		$this->parse_translations($entry);
		
		// phrases
		$this->parse_phrases($entry);
		
		// senses
		$this->parse_senses($entry);
		
		// closing entry content
		$this->output .= '</div>' . "\n";
		
		// closing entry container
		$this->output .= '</div>' . "\n";
	}
	
	//--------------------------------------------------------------------
	// sense nest parser
	//--------------------------------------------------------------------
	
	private function parse_senses(/*Node*/ $node){
		
		// senses
		$this->output .= '<div class="senses">' . "\n";
		while($sense = $node->get_sense()){
			$this->parse_sense($sense);
		}
		$this->output .= '</div>' . "\n";
		
		// new sense
		$this->output .=
			'<div class="button_bar sense_button_bar">' .
				'<button class="button add_sense" onclick="addSense(this.parentNode.parentNode, ' .
				$node->get_node_id() .
				')">' .
					$this->localization->get_text('add sense') .
				'</button>' .
			'</div>' . "\n";
		
	}
	
	//--------------------------------------------------------------------
	// sense parser
	//--------------------------------------------------------------------
	
	private function parse_sense(\Dictionary\Sense $sense){
		
		$this->output .= '<div class="sense_container">' . "\n";
		
		// sense 
		$this->output .=
			'<div class="bar sense_label_bar" onmouseover="showButtons(this)" onmouseout="hideButtons(this)">' . "\n" .
				'<div class="bar_element sense_label">' .
				$sense->get_label() .
				'</div>' . "\n" .
				'<div class="buttons">' . "\n" .
					
					'<button class="button move_up" onclick="moveSenseUp(this.parentNode.parentNode.parentNode, ' .
					$sense->get_node_id() .
					')">' .
						$this->localization->get_text('up') .
					'</button>' . "\n" .
					
					'<button class="button move_down" onclick="moveSenseDown(this.parentNode.parentNode.parentNode, ' .
					$sense->get_node_id() .
					')">' .
						$this->localization->get_text('down') .
					'</button>' . "\n" .

					'<button class="button delete" onclick="deleteSense(this.parentNode.parentNode.parentNode, ' .
					$sense->get_node_id() .
					')">' .
						$this->localization->get_text('delete') .
					'</button>' . "\n" .
					
				'</div>' . "\n" .
			'</div>' . "\n";
		
		$this->output .= '<div class="content sense_content">' . "\n";
		
		// category label
		$this->parse_category_label($sense);
		
		// forms
		$this->parse_forms($sense);
		
		// context
		$this->parse_context($sense);

		// translations
		$this->parse_translations($sense);
		
		// phrases
		$this->parse_phrases($sense);
		
		// subsenses
		$this->parse_senses($sense);

		// closing sense content
		$this->output .= '</div>' . "\n";
		
		// closing sense container
		$this->output .= '</div>' . "\n";
	}
	
	//--------------------------------------------------------------------
	// phrase nest parser
	//--------------------------------------------------------------------
	
	private function parse_phrases(\Dictionary\Node_With_Phrases $node){
		
		// phrases
		$this->output .= '<div class="phrases">' . "\n";
		while($phrase = $node->get_phrase()){
			$this->parse_phrase($phrase);
		}
		$this->output .= '</div>' . "\n";
		
		// new phrase
		$this->output .=
			'<div class="button_bar phrase_button_bar">' .
				'<button class="button add_phrase" onclick="addPhrase(this.parentNode.parentNode, ' .
					$node->get_node_id() .
				')">' .
					$this->localization->get_text('add phrase') .
				'</button>' .
			'</div>' . "\n";
		
	}
	
	//--------------------------------------------------------------------
	// phrase parser
	//--------------------------------------------------------------------
	
	private function parse_phrase(\Dictionary\Phrase $phrase){
		
		$this->output .= '<div class="phrase_container">' . "\n";
		
		// phrase
		$this->output .=
			'<div class="bar phrase_bar" onmouseover="showButtons(this)" onmouseout="hideButtons(this)">' . "\n" .
				'<div class="bar_element phrase" onclick="editPhrase(this.parentNode, ' .
				$phrase->get_node_id() .
				')">' .
					$phrase->get() .
				'</div>' . "\n" .
				'<div class="buttons">' . "\n" .

					'<button class="button edit" onclick="editPhrase(this.parentNode.parentNode, ' .
					$phrase->get_node_id() .
					')">' .
						$this->localization->get_text('edit') .
					'</buton>' . "\n" .
					
					'<button class="button move_up" onclick="movePhraseUp(this.parentNode.parentNode.parentNode, ' .
					$phrase->get_node_id() .
					')">' .
						$this->localization->get_text('up') .
					'</button>' . "\n" .
					
					'<button class="button move_down" onclick="movePhraseDown(this.parentNode.parentNode.parentNode, ' .
					$phrase->get_node_id() .
					')">' .
						$this->localization->get_text('down') .
					'</button>' . "\n" .
					
					'<button class="button delete" onclick="deletePhrase(this.parentNode.parentNode.parentNode, ' .
					$phrase->get_node_id() .
					')">' .
						$this->localization->get_text('delete') .
					'</button>' . "\n" .
					
				'</div>' . "\n" .
			'</div>' . "\n";
		
		$this->output .= '<div class="content phrase_content">' . "\n";
		
		// translations
		$this->parse_translations($phrase);
		
		// closing phrase content
		$this->output .= '</div>' . "\n";
		
		// closing phrase container
		$this->output .= '</div>' . "\n";
		
	}
	
	//--------------------------------------------------------------------
	// headword nest parser
	//--------------------------------------------------------------------
	
	private function parse_headwords(\Dictionary\Node_With_Headwords $node){
		
		$this->output .= '<div class="headwords">' . "\n";
		while($headword = $node->get_headword()){
			$this->parse_headword($headword);
		}
		$this->output .= '</div>' . "\n";
		
		// new phrase
		$this->output .=
			'<div class="button_bar headword_button_bar">' .
				'<button class="button add_phrase" onclick="addHeadword(this.parentNode.parentNode, ' .
				$node->get_node_id() .
				')">' .
					$this->localization->get_text('add headword') .
				'</button>' .
			'</div>' . "\n";
		
	}
	
	//--------------------------------------------------------------------
	// headword parser
	//--------------------------------------------------------------------
	
	private function parse_headword(\Dictionary\Headword $headword){
		$this->output .=
			'<div class="bar headword_bar" onmouseover="showButtons(this)" onmouseout="hideButtons(this)">' . "\n" .
				'<div class="bar_element headword" onclick="editHeadword(this.parentNode, ' .
				$headword->get_id().
				')">' .
					$headword->get() .
				'</div>' . "\n" .
				$this->get_four_buttons($headword, array('js_name' => 'Headword')) .				
			'</div>' . "\n";
	}
	
	//--------------------------------------------------------------------
	// pronunciation nest parser
	//--------------------------------------------------------------------
	
	private function parse_pronunciations(\Dictionary\Node_With_Pronunciations $node){
		
		$this->output .= '<div class="pronunciations">' . "\n";
		while($pronunciation = $node->get_pronunciation()){
			$this->parse_pronunciation($pronunciation);
		}
		$this->output .= '</div>' . "\n";
		
		// new phrase
		$this->output .=
			'<div class="button_bar pronunciation_button_bar">' .
				'<button class="button add_phrase" onclick="addPronunciation(this.parentNode.parentNode, ' .
				$node->get_node_id() .
				')">' .
					$this->localization->get_text('add pronunciation') .
				'</button>' .
			'</div>' . "\n";
		
	}

	//--------------------------------------------------------------------
	// pronunciation parser
	//--------------------------------------------------------------------
	
	private function parse_pronunciation(\Dictionary\Pronunciation $pronunciation){
		$this->output .=
			'<div class="bar pronunciation_bar" onmouseover="showButtons(this)" onmouseout="hideButtons(this)">' . "\n" .
				'<div class="bar_element pronunciation" onclick="editPronunciation(this.parentNode, ' .
				$pronunciation->get_id().
				')">' .
					$pronunciation->get() .
				'</div>' . "\n" .
				$this->get_four_buttons($pronunciation, array('js_name' => 'Pronunciation')) .
				
			'</div>' . "\n";
	}
	
	//--------------------------------------------------------------------
	// category label parser
	//--------------------------------------------------------------------
	
	private function parse_category_label(\Dictionary\Node_With_Category_Label $node){
		
		$category_label = $node->get_category_label();
		
		$this->output .=
			'<div class="category_labels">' . "\n";
		
		if($category_label){
			$this->output .=
				'<div class="bar category_label_bar" onmouseover="showButtons(this)" onmouseout="hideButtons(this)">' . "\n" .
					'<div class="bar_element category_label" onclick="editCategoryLabel(this.parentNode, ' .
					$node->get_node_id() .
					')">' .
						$category_label->get() .
					'</div>' . "\n" .
					$this->get_two_buttons($node, array('js_name' => 'CategoryLabel')) .
				'</div>' . "\n";
		}
		
		$this->output .=
			'</div>' . "\n";
		
		if(!$category_label){
		
			$this->output .=
				'<div class="button_bar category_label_button_bar">' . "\n" .
					'<button class="button add_category_label" onclick="addCategoryLabel(this.parentNode.parentNode, ' . 
					$node->get_node_id() .
					')">' .
						$this->localization->get_text('add category label') .
					'</button>' . "\n" .
				'</div>' . "\n";
			
		}
		
	}

	//--------------------------------------------------------------------
	// form nest parser
	//--------------------------------------------------------------------
	
	private function parse_forms(\Dictionary\Headword_Node $node){
		
		// forms
		$this->output .= '<div class="forms">' . "\n";
		while($form = $node->get_form()){
			$this->parse_form($form);
		}
		$this->output .= '</div>' . "\n";
		
		// new form
		$this->output .=
			'<div class="button_bar form_button_bar">' .
				'<button class="button add_form" onclick="addForm(this.parentNode.parentNode, ' .
				$node->get_node_id() .
				')">' .
					$this->localization->get_text('add form') .
				'</button>' .
			'</div>' . "\n";
		
	}
	
	//--------------------------------------------------------------------
	// form parser
	//--------------------------------------------------------------------
	
	private function parse_form(\Dictionary\Form $form){
		$this->output .=
			'<div class="bar form_bar" onmouseover="showButtons(this)" onmouseout="hideButtons(this)">' . "\n" .
				'<div class="bar_element form_label" onclick="editForm(this.parentNode, ' .
				$form->get_id() .
				', \'label\')">' .
					$form->get_label() .
				'</div>' . "\n" .
				'<div class="bar_element form" onclick="editForm(this.parentNode, ' .
				$form->get_id() .
				')">' .
					$form->get_form() .
				'</div>' . "\n" .
				'<div class="buttons">' . "\n" .
					
					'<button class="button edit" onclick="editForm(this.parentNode.parentNode, ' .
					$form->get_id() .
					')">' .
						$this->localization->get_text('edit') .
					'</button>' . "\n" .
					
					'<button class="button move_up" onclick="moveFormUp(this.parentNode.parentNode, ' .
					$form->get_id() .
					')">' .
						$this->localization->get_text('up') .
					'</button>' . "\n" .
					
					'<button class="button move_down" onclick="moveFormDown(this.parentNode.parentNode, ' .
					$form->get_id() .
					')">' .
						$this->localization->get_text('down') .
					'</button>' . "\n" .

					'<button class="button delete" onclick="deleteForm(this.parentNode.parentNode, ' .
					$form->get_id() .
					')">' .
						$this->localization->get_text('delete') .
					'</button>' . "\n" .
					
				'</div>' . "\n" .
			'</div>' . "\n";
	}
	
	//--------------------------------------------------------------------
	// context parser
	//--------------------------------------------------------------------
	
	private function parse_context(\Dictionary\Node_With_Context $node){
		
		$context = $node->get_context();
		
		$this->output .=
			'<div class="contexts">' . "\n";
		
		if($context){
			$this->output .=
				'<div class="bar context_bar" onmouseover="showButtons(this)" onmouseout="hideButtons(this)">' .
					'<div class="bar_element context" onclick="editContext(this.parentNode, ' .
					$node->get_node_id() .
					')">' .
						$context->get() .
					'</div>' .
					$this->get_two_buttons($node, array('js_name' => 'Context')) .
				'</div>' . "\n";
		}
		
		$this->output .=
			'</div>' . "\n";
		
		if(!$context){
			
			$this->output .=
				'<div class="button_bar context_button_bar">' . "\n" .
					'<button class="button add_context" onclick="addContext(this.parentNode.parentNode, ' . 
					$node->get_node_id() .
					')">' .
						$this->localization->get_text('add context') .
					'</button>' . "\n" .
				'</div>' . "\n";
			
		}
		
	}

	//--------------------------------------------------------------------
	// translation nest parser
	//--------------------------------------------------------------------
	
	private function parse_translations(\Dictionary\Node_With_Translations $node){
	
		// translations
		$this->output .= '<div class="translations">' . "\n";
		while($translation = $node->get_translation()){
			$this->parse_translation($translation);
		}
		$this->output .= '</div>' . "\n";
		
		// new translation
		$this->output .=
			'<div class="button_bar translation_button_bar">' . "\n" .
				'<button class="button add_translation" onclick="addTranslation(this.parentNode.parentNode, ' .
				$node->get_node_id() .
				')">' .
					$this->localization->get_text('add translation') .
				'</button>' . "\n" .
			'</div>' . "\n";
		
	}
	
	//--------------------------------------------------------------------
	// translation parser
	//--------------------------------------------------------------------
	
	private function parse_translation(\Dictionary\Translation $translation){
		$this->output .=
			'<div class="bar translation_bar" onmouseover="showButtons(this)" onmouseout="hideButtons(this)">' . "\n" .
				'<div class="bar_element translation" onclick="editTranslation(this.parentNode, ' .
				$translation->get_id() .
				')">' .
					$translation->get() .
				'</div>' . "\n" .
				$this->get_four_buttons($translation, array('js_name' => 'Translation')) .
			'</div>' . "\n";
	}
	
	//--------------------------------------------------------------------
	// two buttons
	//--------------------------------------------------------------------
	
	private function get_two_buttons(\Dictionary\Node $parent_node, $parameters){
		
		$output =
			'<div class="buttons">' . "\n" .
				
				'<button class="button edit" onclick="edit' . $parameters['js_name']. '(this.parentNode.parentNode, ' .
				$parent_node->get_node_id() .
				')">' .
					$this->localization->get_text('edit') .
				'</button>' . "\n" .
				
				'<button class="button delete" onclick="delete' . $parameters['js_name']. '(this.parentNode.parentNode, ' .
				$parent_node->get_node_id() .
				')">' .
					$this->localization->get_text('delete') .
				'</button>' . "\n" .
				
			'</div>' . "\n";
		
		return $output;
	}

	//--------------------------------------------------------------------
	// four buttons
	//--------------------------------------------------------------------
	
	private function get_four_buttons(\Dictionary\Value $value, $parameters){
		
		$output =
			'<div class="buttons">' . "\n" .
				
				'<button class="button edit" onclick="edit' . $parameters['js_name']. '(this.parentNode.parentNode, ' .
				$value->get_id() .
				')">' .
					$this->localization->get_text('edit') .
				'</button>' . "\n" .
				
				'<button class="button move_up" onclick="move' . $parameters['js_name']. 'Up(this.parentNode.parentNode, ' .
				$value->get_id() .
				')">' .
					$this->localization->get_text('up') .
				'</button>' . "\n" .
				
				'<button class="button move_down" onclick="move' . $parameters['js_name']. 'Down(this.parentNode.parentNode, ' .
				$value->get_id() .
				')">' .
					$this->localization->get_text('down') .
				'</buton>' . "\n" .
				
				'<button class="button delete" onclick="delete' . $parameters['js_name']. '(this.parentNode.parentNode, ' .
				$value->get_id() .
				')">' .
					$this->localization->get_text('delete') .
				'</button>' . "\n" .
				
			'</div>' . "\n";
		
		return $output;
	}
}

?>
