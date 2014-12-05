<?php

namespace Controllers;

// todo: rename to plural to conform to the standard or merge plurals with singulars

class Entry extends Abstracts\JSON_Controller {
	
	function find($headword){
		
		/** @var \Dictionary\Dictionary $dictionary */
		$dictionary = $this->services('dictionary');
		
		$entries = $headword ? $dictionary->get_entries_by_headword($headword) : [];
		
		return [
			'headword' => $headword,
			'entries'  => $entries,
		];
	}
	
	// sprawdzić, czy jest używane
	function delete($node_id){
		
		/** @var \Dictionary\MySQL_Entry $entry_access */
		$entry_access = $this->services->get('data')->access('entry');
		
		$result = $entry_access->delete($node_id);
		
		$this->handle_query_result($result);
	}
}

