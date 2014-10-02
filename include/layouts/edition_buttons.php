<?php

namespace DCMS;

require_once 'dictionary/traits/node_interface.php';

use Dictionary\Value;
use Dictionary\Node;
use Dictionary\Node_Interface;

use Dictionary\Form;

trait Edition_Buttons {
	
	//--------------------------------------------------------------------
	// two buttons
	//--------------------------------------------------------------------
	
	private function make_two_buttons(Value $value, Node_Interface $parent_node){
		
		$output =
			'<div class="buttons">' . "\n" .
				
				'<button'.
					' class="button edit"' .
					' onclick="edit' . $value->get_camelized_name(). '(this.parentNode.parentNode, ' . $parent_node->get_node_id() . ')"' .
				'>' .
					$this->localization->get_text('edit') .
				'</button>' . "\n" .
				
				'<button' .
					' class="button delete"' .
					' onclick="delete' . $value->get_camelized_name(). '(this.parentNode.parentNode, ' . $parent_node->get_node_id() . ')"' .
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
	
	private function make_button_bar(Node_Interface $node, array $parameters){
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

