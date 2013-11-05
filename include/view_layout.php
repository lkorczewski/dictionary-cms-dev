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
require_once 'dictionary/traits/has_translations.php';

require_once 'include/localization.php';

class View_Layout{
	
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
		
		//pronunciations
		$this->parse_pronunciations($entry);
		
		$this->output .= '<div class="content entry_content">' . "\n";
		
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
		
	}
	
	//--------------------------------------------------------------------
	// sense parser
	//--------------------------------------------------------------------
	
	private function parse_sense(\Dictionary\Sense $sense){
		
		$this->output .= '<div class="sense_container">' . "\n";
		
		// sense 
		$this->output .=
			'<div class="bar sense_label_bar">' . "\n" .
				'<div class="bar_element sense_label">' .
					$sense->get_label() .
				'</div>' . "\n" .
			'</div>' . "\n"
			;
		
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
		
	}
	
	//--------------------------------------------------------------------
	// phrase parser
	//--------------------------------------------------------------------
	
	private function parse_phrase(\Dictionary\Phrase $phrase){
		
		$this->output .= '<div class="phrase_container">' . "\n";
		
		// phrase
		$this->output .=
			'<div class="bar phrase_bar">' . "\n" .
				'<div class="bar_element phrase">' .
					$phrase->get() .
				'</div>' . "\n" .
			'</div>' . "\n"
			;
		
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
		
	}
	
	//--------------------------------------------------------------------
	// headword parser
	//--------------------------------------------------------------------
	
	private function parse_headword(\Dictionary\Headword $headword){
		$this->output .=
			'<div class="headword_bar">' . "\n" .
				'<div class="headword">' .
					$headword->get() .
				'</div>' . "\n" .
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
		
	}
	
	//--------------------------------------------------------------------
	// pronunciation parser
	//--------------------------------------------------------------------
	
	private function parse_pronunciation(\Dictionary\Pronunciation $pronunciation){
		$this->output .=
			'<div class="pronunciation_bar">' . "\n" .
				'<div class="pronunciation">' .
					'/' . $pronunciation->get() . '/' .
				'</div>' . "\n" .
			'</div>' . "\n";
	}
	
	//--------------------------------------------------------------------
	// category label parser
	//--------------------------------------------------------------------
	
	private function parse_category_label(\Dictionary\Node_With_Category_Label $node){
		
		if($category_label = $node->get_category_label()){
			$this->output .=
				'<div class="bar category_label_bar">' . "\n" .
					'<div class="bar_element category_label">' .
						$category_label->get() .
					'</div>' . "\n" .
				'</div>' . "\n";
		}
		
	}
	
	//--------------------------------------------------------------------
	// form nest parser
	//--------------------------------------------------------------------
	
	private function parse_forms(\Dictionary\Node_With_Forms $node){
		
		// forms
		$this->output .= '<div class="forms">' . "\n";
		while($form = $node->get_form()){
			$this->parse_form($form);
		}
		$this->output .= '</div>' . "\n";
		
	}
	
	//--------------------------------------------------------------------
	// form parser
	//--------------------------------------------------------------------
	
	private function parse_form(\Dictionary\Form $form){
		$this->output .=
			'<div class="bar form_bar">' . "\n" .
				'<div class="bar_element form_label">' .
					$form->get_label() .
				'</div>' . "\n" .
				'<div class="bar_element form">' .
					$form->get_form() .
				'</div>' . "\n" .
			'</div>' . "\n"
			;
	}

	//--------------------------------------------------------------------
	// context parser
	//--------------------------------------------------------------------
	
	private function parse_context(\Dictionary\Sense $node){
		
		$context = $node->get_context();
		
		$this->output .=
			'<div class="contexts">' . "\n";
		
		if($context){
			$this->output .=
				'<div class="bar context_bar">' .
					'<div class="bar_element context">' .
						$context->get() .
					'</div>' .
				'</div>' . "\n";
		}
		
		$this->output .=
			'</div>' . "\n";
		
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
		
	}
	
	//--------------------------------------------------------------------
	// translation parser
	//--------------------------------------------------------------------
	
	private function parse_translation(\Dictionary\Translation $translation){
		$this->output .=
			'<div class="bar translation_bar">' . "\n" .
				'<div class="bar_element translation">' .
					$translation->get_text()
				. '</div>' . "\n" .
			'</div>' . "\n"
			;
	}
}

?>
