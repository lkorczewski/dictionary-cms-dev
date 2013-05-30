<?php

require_once 'dictionary/dictionary.php';

require_once 'dictionary/entry.php';
require_once 'dictionary/sense.php';
require_once 'dictionary/phrase.php';

require_once 'dictionary/form.php';
require_once 'dictionary/translation.php';

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
		
		$this->output .=
			'<div class="headword_bar" onmouseover="showButtons(this)" onmouseout="hideButtons(this)">' .
			'<div class="headword" onclick="edit_entry_headword(this.parentNode, ' .
			$entry->get_node_id() .
			')">' .
			$entry->get_headword() .
			'</div>' . "\n" .
			'<div class="buttons">' .
					
					'<button class="button delete" onclick="delete_entry(' .
					$entry->get_node_id() .
					')">' .
					$this->localization->get_text('delete') .
					'</button>' . "\n" .
					
					'<button class="button edit" onclick="edit_entry_headword(this.parentNode.parentNode, ' .
					$entry->get_node_id() .
					')">' .
					$this->localization->get_text('edit') .
					'</button>' . "\n" .
					
			'</div>' . "\n" .
			'</div>' . "\n";
			
		
		$this->output .= '<div class="entry_content">' . "\n";
		
		// category label
		$this->parse_category_label($entry);

		// forms
		$this->parse_forms($entry);
		
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
		while($sense = $node->get_sense()){
			$this->parse_sense($sense);
		}
		
		// new sense
		$this->output .=
			'<div><button class="button add_sense" onclick="add_sense(this.parentNode.parentNode, ' .
			$node->get_node_id() .
			')">' .
			$this->localization->get_text('add sense') .
			'</button></div>' . "\n";
		
	}
	
	//--------------------------------------------------------------------
	// sense parser
	//--------------------------------------------------------------------
	
	private function parse_sense(Sense $sense){
		
		$this->output .= '<div class="sense_container">' . "\n";
		
		// sense 
		$this->output .=
			'<div class="sense_label_bar" onmouseover="showButtons(this)" onmouseout="hideButtons(this)">' . "\n" .
				'<div class="sense_label">' .
				$sense->get_label() .
				'</div>' . "\n" .
				'<div class="buttons">' . "\n" .
					
					'<button class="button delete" onclick="delete_sense(this.parentNode.parentNode.parentNode, ' .
					$sense->get_node_id() .
					')">' .
					$this->localization->get_text('delete') .
					'</button>' . "\n" .
					
					'<button class="button move_up" onclick="move_sense_up(this.parentNode.parentNode.parentNode, ' .
					$sense->get_node_id() .
					')">' .
					$this->localization->get_text('up') .
					'</button>' . "\n" .
					
					'<button class="button move_down" onclick="move_sense_down(this.parentNode.parentNode.parentNode, ' .
					$sense->get_node_id() .
					')">' .
					$this->localization->get_text('down') .
					'</button>' . "\n" .
					
				'</div>' . "\n" .
			'</div>' . "\n"
			;
		
		$this->output .= '<div class="sense_content">' . "\n";
		
		// category label
		$this->parse_category_label($sense);
		
		// forms
		$this->parse_forms($sense);
		
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
	
	private function parse_phrases(/*Node*/ $node){
		
		// phrases
		$this->output .= '<div class="phrases">' . "\n";
		while($phrase = $node->get_phrase()){
			$this->parse_phrase($phrase);
		}
		$this->output .= '</div>' . "\n";
		
		// new phrase
		$this->output .=
			'<div><button class="button add_phrase" onclick="addPhrase(this.parentNode.parentNode, ' .
			$node->get_node_id() .
			')">' .
			$this->localization->get_text('add phrase') .
			'</button></div>' . "\n";
		
	}
	
	//--------------------------------------------------------------------
	// phrase parser
	//--------------------------------------------------------------------
	
	private function parse_phrase(Phrase $phrase){
		
		$this->output .= '<div class="phrase_container">' . "\n";
		
		// phrase
		$this->output .=
			'<div class="phrase_bar" onmouseover="showButtons(this)" onmouseout="hideButtons(this)">' . "\n" .
				'<div class="phrase" onclick="editPhrase(this.parentNode, ' .
				$phrase->get_node_id() .
				')">' .
				$phrase->get() .
				'</div>' . "\n" .
				'<div class="buttons">' . "\n" .
					
					'<button class="button delete" onclick="deletePhrase(this.parentNode.parentNode.parentNode, ' .
					$phrase->get_node_id() .
					')">' .
					$this->localization->get_text('delete') .
					'</button>' . "\n" .
					
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
					
					'<button class="button edit" onclick="editPhrase(this.parentNode.parentNode, ' .
					$phrase->get_node_id() .
					')">' .
					$this->localization->get_text('edit') .
					'</buton>' . "\n" .
					
				'</div>' . "\n" .
			'</div>' . "\n"
			;
		
		$this->output .= '<div class="phrase_content">' . "\n";
		
		// translations
		$this->parse_translations($phrase);
		
		// closing phrase content
		$this->output .= '</div>' . "\n";
		
		// closing phrase container
		$this->output .= '</div>' . "\n";
		
	}
	
	//--------------------------------------------------------------------
	// category label parser
	//--------------------------------------------------------------------
	
	private function parse_category_label(Headword_Node $node){
		
		$category_label = $node->get_category_label();
		
		$this->output .=
			'<div class="category_labels">' . "\n";
		
		if($category_label){
			$this->output .=
				'<div class="category_label_bar" onmouseover="showButtons(this)" onmouseout="hideButtons(this)">' . "\n" .
					'<div class="category_label" onclick="editCategoryLabel(this.parentNode, ' .
					$node->get_node_id() .
					')">' .
					$category_label->get() .
					'</div>' . "\n" .
					'<div class="buttons">' . "\n" .
						
						'<button class="button delete" onclick="deleteCategoryLabel(this.parentNode.parentNode, ' .
						$node->get_node_id() .
						')">' .
						$this->localization->get_text('delete') .
						'</button>' . "\n" .
						
						'<button class="button edit" onclick="editCategoryLabel(this.parentNode.parentNode, ' .
						$node->get_node_id() .
						')">' .
						$this->localization->get_text('edit') .
						'</button>' . "\n" .
						
					'</div>' . "\n" .
				'</div>' . "\n";
		}
		
		$this->output .=
			'</div>' . "\n";
		
		if(!$category_label){
		
			$this->output .=
				'<div>' . "\n" .
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
	
	private function parse_forms(Headword_Node $node){
		
		// forms
		$this->output .= '<div class="forms">' . "\n";
		while($form = $node->get_form()){
			$this->parse_form($form);
		}
		$this->output .= '</div>' . "\n";
		
		// new form
		$this->output .=
			'<div><button class="button add_form" onclick="addForm(this.parentNode.parentNode, ' .
			$node->get_node_id() .
			')">' .
			$this->localization->get_text('add form') .
			'</button></div>' . "\n";
		
	}
	
	//--------------------------------------------------------------------
	// form parser
	//--------------------------------------------------------------------
	
	private function parse_form(Form $form){
		$this->output .=
			'<div class="form_bar" onmouseover="showButtons(this)" onmouseout="hideButtons(this)">' . "\n" .
				'<div class="form_label" onclick="editForm(this.parentNode, ' .
				$form->get_id() .
				', \'label\')">' .
				$form->get_label() .
				'</div>' . "\n" .
				'<div class="form" onclick="editForm(this.parentNode, ' .
				$form->get_id() .
				')">' .
				$form->get_form() .
				'</div>' . "\n" .
				'<div class="buttons">' . "\n" .
				
					'<button class="button delete" onclick="deleteForm(this.parentNode.parentNode, ' .
					$form->get_id() .
					')">' .
					$this->localization->get_text('delete') .
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
				
				'</div>' . "\n" .
			'</div>' . "\n"
			;
	}

	//--------------------------------------------------------------------
	// translation nest parser
	//--------------------------------------------------------------------
	
	private function parse_translations(Node $node){
	
		// translations
		$this->output .= '<div class="translations">' . "\n";
		while($translation = $node->get_translation()){
			$this->parse_translation($translation);
		}
		$this->output .= '</div>' . "\n";
		
		// new translation
		$this->output .=
			'<div><button class="button add_translation" onclick="addTranslation(this.parentNode.parentNode, ' .
			$node->get_node_id() .
			')">' .
			$this->localization->get_text('add translation') .
			'</button></div>' . "\n";
		
	}
	
	//--------------------------------------------------------------------
	// translation parser
	//--------------------------------------------------------------------
	
	private function parse_translation(Translation $translation){
		$this->output .=
			'<div class="translation_bar" onmouseover="showButtons(this)" onmouseout="hideButtons(this)">' . "\n" .
				'<div class="translation" onclick="editTranslation(this.parentNode, ' .
				$translation->get_id() .
				')">' .
				$translation->get_text()
				. '</div>' . "\n" .
				'<div class="buttons">' . "\n" .
				
					'<button class="button delete" onclick="deleteTranslation(this.parentNode.parentNode, ' .
					$translation->get_id() .
					')">' .
					$this->localization->get_text('delete') .
					'</button>' . "\n" .
					
					'<button class="button move_up" onclick="moveTranslationUp(this.parentNode.parentNode, ' .
					$translation->get_id() .
					')">' .
					$this->localization->get_text('up') .
					'</button>' . "\n" .
					
					'<button class="button move_down" onclick="moveTranslationDown(this.parentNode.parentNode, ' .
					$translation->get_id() .
					')">' .
					$this->localization->get_text('down') .
					'</buton>' . "\n" .
					
					'<button class="button edit" onclick="editTranslation(this.parentNode.parentNode, ' .
					$translation->get_id() .
					')">' .
					$this->localization->get_text('edit') .
					'</buton>' . "\n" .
				
				'</div>' . "\n" .
			'</div>' . "\n"
			;
	}
}

?>
