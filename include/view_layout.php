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

use Dictionary\Entry;
use Dictionary\Sense;
use Dictionary\Phrase;
use Dictionary\Form;
use Dictionary\Headword;
use Dictionary\Pronunciation;
use Dictionary\Translation;

use Dictionary\Value;

use Dictionary\Node_With_Senses;
use Dictionary\Node_With_Phrases;
use Dictionary\Node_With_Headwords;
use Dictionary\Node_With_Pronunciations;
use Dictionary\Node_With_Category_Label;
use Dictionary\Node_With_Forms;
use Dictionary\Node_With_Context;
use Dictionary\Node_With_Translations;

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
		
		// closing entry content
		$this->output .= '</div>' . "\n";
		
		// closing entry container
		$this->output .= '</div>' . "\n";
	}
	
	//--------------------------------------------------------------------
	// sense nest parser
	//--------------------------------------------------------------------
	
	private function parse_senses(Node_With_Senses $node){
		
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
	
	private function parse_sense(Sense $sense){
		
		$this->output .= '<div class="sense_container">' . "\n";
		
		// sense label
		$this->output .=
			'<div class="bar sense_label_bar">' . "\n" .
				'<div class="bar_element sense_label">' .
					$sense->get_label() .
				'</div>' . "\n" .
			'</div>' . "\n"
			;
		
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
		
		$this->output .= '<div class="phrases">' . "\n";
		while($phrase = $node->get_phrase()){
			$this->parse_phrase($phrase);
		}
		$this->output .= '</div>' . "\n";
		
	}
	
	//--------------------------------------------------------------------
	// phrase parser
	//--------------------------------------------------------------------
	
	private function parse_phrase(Phrase $phrase){
		
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
		
		$this->parse_translations($phrase);
		
		$this->output .= '</div>' . "\n"; // .phrase_content
		
		$this->output .= '</div>' . "\n"; // .phrase_container
		
	}
	
	//--------------------------------------------------------------------
	// headword nest parser
	//--------------------------------------------------------------------
	
	private function parse_headwords(Node_With_Headwords $node){
		
		$this->output .= '<div class="headwords">' . "\n";
		while($headword = $node->get_headword()){
			$this->parse_headword($headword);
		}
		$this->output .= '</div>' . "\n";
		
	}
	
	//--------------------------------------------------------------------
	// headword parser
	//--------------------------------------------------------------------
	
	private function parse_headword(Headword $headword){
		$this->parse_value('headword', $headword);
	}

	//--------------------------------------------------------------------
	// pronunciation nest parser
	//--------------------------------------------------------------------
	
	private function parse_pronunciations(Node_With_Pronunciations $node){
		
		$this->output .= '<div class="pronunciations">' . "\n";
		while($pronunciation = $node->get_pronunciation()){
			$this->parse_pronunciation($pronunciation);
		}
		$this->output .= '</div>' . "\n";
		
	}
	
	//--------------------------------------------------------------------
	// pronunciation parser
	//--------------------------------------------------------------------
	
	private function parse_pronunciation(Pronunciation $pronunciation){
		$this->output .=
			'<div class="bar pronunciation_bar">' . "\n" .
				'<div class="bar_element pronunciation">' .
					'/' . $pronunciation->get() . '/' .
				'</div>' . "\n" .
			'</div>' . "\n"
		;
	}
	
	//--------------------------------------------------------------------
	// category label parser
	//--------------------------------------------------------------------
	
	private function parse_category_label(Node_With_Category_Label $node){
		
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
	
	private function parse_forms(Node_With_Forms $node){
		
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
	
	private function parse_form(Form $form){
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
	
	private function parse_context(Node_With_Context $node){
		
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
	
	private function parse_translations(Node_With_Translations $node){
	
		$this->output .= '<div class="translations">' . "\n";
		while($translation = $node->get_translation()){
			$this->parse_translation($translation);
		}
		$this->output .= '</div>' . "\n";
		
	}
	
	//--------------------------------------------------------------------
	// translation parser
	//--------------------------------------------------------------------
	
	private function parse_translation(Translation $translation){
		$this->parse_value('translation', $translation);
	}
	
	//--------------------------------------------------------------------
	// value parser
	//--------------------------------------------------------------------
	
	private function parse_value($name, Value $value){
		$this->output .=
			'<div class="bar ' . $name .  '_bar">' . "\n" .
				'<div class="bar_element ' . $name . '">' .
					$value->get() .
				'</div>' . "\n" .
			'</div>' . "\n"
		;
	}
}

