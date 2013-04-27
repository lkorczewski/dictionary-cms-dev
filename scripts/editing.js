/* misc */

function showButtons(element){
	buttons = element.getElementsByClassName('buttons')[0]
	buttons.style.display = 'inline-block'
}

function hideButtons(element){
	buttons = element.getElementsByClassName('buttons')[0]
	buttons.style.display = 'none'
	
}

/* DOM traversal */
/*
function previousElementSibling(element) {
	if ('previousElementSibling' in element)
		return element.previousElementSibling;
	do
	element= element.previousSibling;
	while (element!==null && element.nodeType!==1);
	return element;
}
*/

/* atomic actions */

/* senses */

function add_sense(entry_element, nodeId){
	make_request('atomics/add_sense.php', 'n=' + nodeId, {
		success: function(response){
			console.log('add_sense: ' + response)
			if(parseInt(response)){
				location.reload()
			}
		}
	})
}

function move_sense_up(sense_element, nodeId){
	make_request('atomics/move_sense_up.php', 'n=' + nodeId, {
		success: function(response){
			console.log('move_sense_up: ' + response)
			if(response == 'OK'){
				previous_sense_element = sense_element.previousElementSibling /* not working in IE<9 */
				sense_element.parentNode.insertBefore(sense_element, previous_sense_element)
				
				sense_label_element = sense_element.getElementsByClassName('sense_label_bar')[0].getElementsByClassName('sense_label')[0]
				previous_sense_label_element = previous_sense_element.getElementsByClassName('sense_label_bar')[0].getElementsByClassName('sense_label')[0]
				
				buffered_sense_label = sense_label_element.textContent;
				sense_label_element.textContent = previous_sense_label_element.textContent;
				previous_sense_label_element.textContent = buffered_sense_label;
			}
		}
	})
}

function move_sense_down(sense_element, nodeId){
	make_request('atomics/move_sense_down.php', 'n=' + nodeId, {
		success: function(response){
			console.log('move_sense_down: ' + response)
			if(response == 'OK'){
				next_sense_element = sense_element.nextElementSibling /* not working in IE<9 */
				sense_element.parentNode.insertBefore(next_sense_element, sense_element)
				
				sense_label_element = sense_element.getElementsByClassName('sense_label_bar')[0].getElementsByClassName('sense_label')[0]
				previous_sense_label_element = previous_sense_element.getElementsByClassName('sense_label_bar')[0].getElementsByClassName('sense_label')[0]
				
				buffered_sense_label = sense_label_element.textContent;
				sense_label_element.textContent = previous_sense_label_element.textContent;
				previous_sense_label_element.textContent = buffered_sense_label;
			}
		}
	})
}

function delete_sense(sense_element, nodeId){
	make_request('atomics/delete_sense.php', 'n=' + nodeId, {
		success: function(response){
			console.log('delete_sense: ' + response)
			if(response == 'OK'){
				sense_element.parentNode.removeChild(sense_element)
			}
		}
	})
}

/* phrases */

function addPhrase(parentElement, nodeId){
	make_request('atomics/add_phrase.php', 'n=' + nodeId, {
		success: function(response){
			console.log('add_phrase: ' + response)
			if(parseInt(response)){
				location.reload()
			}
		}
	})
}

function editPhrase(phraseBar, nodeId){
	phrase = phraseBar.getElementsByClassName('phrase')[0]
	
	phraseInput = document.createElement('input')
	phraseInput.setAttribute('type', 'text')
	phraseInput.value = phrase.textContent
	phraseInput.onkeypress = function(){
		if(event.keyCode == 13){
			if(phraseInput.value != phrase.textContent){
				phraseInput.disabled = true
				updatePhrase(phraseInput.parentNode, nodeId, phraseInput.value, function(){
					phraseInput.parentNode.removeChild(phraseInput);
					phrase.style.display = 'inline-block'
				})
			} else {
				phraseInput.parentNode.removeChild(phraseInput);
				phrase.style.display = 'inline-block'
			}
		}
	}
	
	phrase.style.display = 'none';
	phraseBar.insertBefore(phraseInput, phrase.nextElementSibling) /* not working in IE<9 */
	phraseInput.focus()
}

function updatePhrase(phraseBar, nodeId, phraseText, doOnSuccess){
	make_request('atomics/update_phrase.php', 'n=' + nodeId + '&t=' + phraseText, {
		success: function(response){
			console.log('update_phrase: ' + response)
			if(response == 'OK'){
				phrase = phraseBar.getElementsByClassName('phrase')[0]
				phrase.textContent = phraseText
				if(typeof doOnSuccess != 'undefined'){
					doOnSuccess()
				}
			}
		}
	})	
}

function movePhraseUp(phraseContainer, nodeId){
	make_request('atomics/move_phrase_up.php', 'n=' + nodeId, {
		success: function(response){
			console.log('move_phrase_up: ' + response)
			if(response == 'OK'){
				phraseContainer.parentNode.insertBefore(phraseContainer, phraseContainer.previousElementSibling) /* not working in IE<9 */
			}
		}
	})
}

function movePhraseDown(phraseContainer, nodeId){
	make_request('atomics/move_phrase_down.php', 'n=' + nodeId, {
		success: function(response){
			console.log('move_phrase_down: ' + response)
			if(response == 'OK'){
				phraseContainer.parentNode.insertBefore(phraseContainer.nextElementSibling, phraseContainer) /* not working in IE<9 */
			}
		}
	})
}

function deletePhrase(phraseElement, nodeId){
	make_request('atomics/delete_phrase.php', 'n=' + nodeId, {
		success: function(response){
			console.log('delete_phrase: ' + response)
			if(response == 'OK'){
				phraseElement.parentNode.removeChild(phraseElement)
			}
		}
	})
}

/* translations */

function addTranslation(sense_element, sense_id){
	make_request('atomics/add_translation.php', 'id=' + sense_id, {
		success: function(response){
			console.log('add_translation: ' + response)
			if(parseInt(response)){
				translationId = response
				translations = sense_element.getElementsByClassName('translations')[0]
				translationBar = makeTranslationBar('...', translationId)
				translations.appendChild(translationBar)
				editTranslation(translationBar, translationId)
			}
		}
	})
}

function editTranslation(translationBar, translationId){
	translation = translationBar.getElementsByClassName('translation')[0]
	
	translationInput = document.createElement('input')
	translationInput.setAttribute('type','text')
	translationInput.value = translation.textContent
	translationInput.onkeypress = function(){
		if(event.keyCode == 13){
			if(translationInput.value != translation.textContent){
				translationInput.disabled = true
				updateTranslation(translationInput.parentNode, translationId, translationInput.value, function(){
					translationInput.parentNode.removeChild(translationInput);
					translation.style.display = 'inline-block'
				})
			} else {
				translationInput.parentNode.removeChild(translationInput);
				translation.style.display = 'inline-block'
			}
		}
	}
	
	translation.style.display = 'none';
	translationBar.insertBefore(translationInput, translation.nextElementSibling) /* not working in IE<9 */
	translationInput.focus()
}

function updateTranslation(translationBar, translationId, translationText, doOnSuccess){
	make_request('atomics/update_translation.php', 'id=' + translationId + '&t=' + translationText, {
		success: function(response){
			console.log('update_translation: ' + response)
			if(response == 'OK'){
				translation = translationBar.getElementsByClassName('translation')[0]
				translation.textContent = translationText
				
				if(typeof doOnSuccess != 'undefined'){
					doOnSuccess()
				}
			}
		}
	})
}

function moveTranslationUp(translationBar, translationId){
	make_request('atomics/move_translation_up.php', 'id=' + translationId, {
		success: function(response){
			console.log('move_translation_up: ' + response)
			if(response == 'OK'){
				translationBar.parentNode.insertBefore(translationBar, translationBar.previousElementSibling) /* not working in IE<9 */
			}
		}
	})
}

function moveTranslationDown(translationBar, translationId){
	make_request('atomics/move_translation_down.php', 'id=' + translationId, {
		success: function(response){
			console.log('move_translation_down: ' + response)
			if(response == 'OK'){
				translationBar.parentNode.insertBefore(translationBar.nextElementSibling, translationBar) /* not working in IE<9 */
			}
		}
	})
}

function deleteTranslation(translationBar, translationId){
	make_request('atomics/delete_translation.php', 'id=' + translationId, {
		success: function(response){
			console.log('delete_translation: ' + response)
			if(response == 'OK'){
				translationBar.parentNode.removeChild(translationBar)
			}
		}
	})
}

function makeTranslationBar(text, translationId){
	translationBar = document.createElement('div')
	translationBar.setAttribute('class', 'translation_bar')
	translationBar.onmouseover = function(){ showButtons(translationBar) }
	translationBar.onmouseout = function(){ hideButtons(translationBar) }
	
	translation = document.createElement('div')
	translation.setAttribute('class', 'translation')
	translation.onclick = function(){ editTranslation(translationBar, translationId) }
	translation.textContent = text
	translationBar.appendChild(translation)
	
	buttons = document.createElement('div')
	buttons.setAttribute('class', 'buttons')
	translationBar.appendChild(buttons)
	
	buttonDelete = document.createElement('button')
	buttonDelete.setAttribute('class', 'button delete')
	buttonDelete.onclick = function(){ deleteTranslation(translationBar, translationId) }
	buttonDelete.textContent = '×'
	buttons.appendChild(buttonDelete)
	
	buttonUp = document.createElement('button')
	buttonUp.setAttribute('class', 'button move_up')
	buttonUp.onclick = function(){ moveTranslationUp(translationBar, translationId) }
	buttonUp.textContent = 'do góry'
	buttons.appendChild(buttonUp)
	
	buttonDown = document.createElement('button')
	buttonDown.setAttribute('class', 'button move_down')
	buttonDown.onclick = function(){ moveTranslationDown(translationBar, translationId) }
	buttonDown.textContent = 'na dół'
	buttons.appendChild(buttonDown)
	
	buttonEdit = document.createElement('button')
	buttonEdit.setAttribute('class', 'button edit')
	buttonEdit.onclick = function(){ editTranslation(translationBar, translationId) }
	buttonEdit.textContent = 'edytuj'
	buttons.appendChild(buttonEdit)
	
	return translationBar
}
