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

use Dictionary\Element;
use Dictionary\Entry;
use Dictionary\Node;
use Dictionary\Node_Interface;
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
		
		$this->make_container($entry, function() use($entry){
			$this->parse_headwords($entry);
			$this->make_content($entry, function() use($entry){
				$this->parse_pronunciations($entry);
				$this->parse_category_label($entry);
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
	
	private function parse_senses(Node_With_Senses $node){
		$this->parse_collection($node, 'senses');
	}
	
	//--------------------------------------------------------------------
	// sense parser
	//--------------------------------------------------------------------
	
	private function parse_sense(Sense $sense){
		
		$this->make_container($sense, function() use($sense){
			
			$this->make_bar($sense, function() use($sense) {
				return $sense->get_label();
			});
			
			$this->make_content($sense, function() use($sense){ 
				$this->parse_category_label($sense);
				$this->parse_forms($sense);
				$this->parse_context($sense);
				$this->parse_translations($sense);
				$this->parse_phrases($sense);
				$this->parse_senses($sense);
			});
		});
		
	}
	
	//--------------------------------------------------------------------
	// phrase nest parser
	//--------------------------------------------------------------------
	
	private function parse_phrases(Node_With_Phrases $node){
		$this->parse_collection($node, 'phrases');
	}
	
	//--------------------------------------------------------------------
	// phrase parser
	//--------------------------------------------------------------------
	
	private function parse_phrase(Phrase $phrase){
		
		$this->make_container($phrase, function() use($phrase){
			
			$this->make_bar($phrase, function() use($phrase) {
				return $phrase->get();
			});
			
			$this->make_content($phrase, function() use($phrase){
				$this->parse_translations($phrase);
			});
			
		});
		
	}
	
	//--------------------------------------------------------------------
	// headword nest parser
	//--------------------------------------------------------------------
	
	private function parse_headwords(Node_With_Headwords $node){
		$this->parse_collection($node, 'headwords');
	}
	
	//--------------------------------------------------------------------
	// headword parser
	//--------------------------------------------------------------------
	
	private function parse_headword(Headword $headword){
		$this->parse_value($headword);
	}

	//--------------------------------------------------------------------
	// pronunciation nest parser
	//--------------------------------------------------------------------
	
	private function parse_pronunciations(Node_With_Pronunciations $node){
		$this->parse_collection($node, 'pronunciations');
	}
	
	//--------------------------------------------------------------------
	// pronunciation parser
	//--------------------------------------------------------------------
	
	private function parse_pronunciation(Pronunciation $pronunciation){
		$this->parse_value($pronunciation);
	}
	
	//--------------------------------------------------------------------
	// category label parser
	//--------------------------------------------------------------------
	
	private function parse_category_label(Node_With_Category_Label $node){
		
		if($category_label = $node->get_category_label()){
			$this->parse_value($category_label);
		}
		
	}
	
	//--------------------------------------------------------------------
	// form nest parser
	//--------------------------------------------------------------------
	
	private function parse_forms(Node_With_Forms $node){
		$this->parse_collection($node, 'forms');
	}
	
	//--------------------------------------------------------------------
	// form parser
	//--------------------------------------------------------------------
	
	private function parse_form(Form $form){
		$this->make_bar($form, function() use($form){
			
			$this->output .=
				'<div class="bar_element form_label">' .
					$form->get_label() .
				'</div>' . "\n" .
				
				$this->make_bar_element($form, function() use($form){
					return $form->get_form();
				}) .
				
			'</div>' . "\n";
		});
	}
	
	//--------------------------------------------------------------------
	// context parser
	//--------------------------------------------------------------------
	
	private function parse_context(Node_With_Context $node){
		$this->parse_single_value($node, 'context');
	}
	
	//--------------------------------------------------------------------
	// translation nest parser
	//--------------------------------------------------------------------
	
	private function parse_translations(Node_With_Translations $node){
		$this->parse_collection($node, 'translations');
	}
	
	//--------------------------------------------------------------------
	// translation parser
	//--------------------------------------------------------------------
	
	private function parse_translation(Translation $translation){
		$this->parse_value($translation);
	}
	
	//--------------------------------------------------------------------
	// collection parser
	//--------------------------------------------------------------------
	// ugly
	
	private function parse_collection(Node_Interface $node, $name){
		
		// todo: better singular/plural handling
		$collection_name  = $name;
		$element_name     = rtrim($name, 's');
		
		$this->output .= '<div class="' . $collection_name . '">' . "\n";
		
		foreach($node->{'get_'.$collection_name}() as $element){
			$this->{'parse_'.$element_name}($element);
		}
		
		$this->output .= '</div>' . "\n";
		
	}
	
	//--------------------------------------------------------------------
	// single value parser
	//--------------------------------------------------------------------
	// ugly
	
	private function parse_single_value(Node_Interface $node, $name){
		
		// todo: better singular/plural handling
		$plural_name    = $name . 's';
		$singular_name  = $name;
		
		$this->output .= '<div class="' . $plural_name . '">' . "\n";
		
		if($value = $node->{'get_' . $singular_name}()){
			$this->parse_value($value);
		}
		
		$this->output .= '</div>' . "\n";
	}
	
	//--------------------------------------------------------------------
	// value parser
	//--------------------------------------------------------------------
	
	private function parse_value(Value $value){
		$this->make_bar($value, function() use($value) {
			return $value->get();
		});
	}
	
	//====================================================================
	
	private function make_container(Node $node, callable $content_function){
		$this->output .= '<div class="container ' . $node->get_snakized_name() . '_container">' . "\n";
		$content_function();
		$this->output .= '</div>' . "\n";
	}
	
	private function make_content(Node $node, callable $content_function){
		$this->output .= '<div class="content ' . $node->get_snakized_name() . '_content">' . "\n";
		$content_function();
		$this->output .= '</div>' . "\n";
	}
	
	private function make_bar(Element $element, callable $content_function){
		$this->output .=
			'<div class="bar ' . $element->get_snakized_name() .  '_bar">' . "\n" .
				$this->make_bar_element($element, $content_function) .
			'</div>' . "\n";
	}
	
	private function make_bar_element(Element $element, callable $content_function){
		$output =
			'<div class="bar_element ' . $element->get_snakized_name() . '">' .
				$content_function() .
			'</div>' . "\n";
		
		return $output;
	}
	
}

