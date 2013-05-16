/* misc */

function showButtons(element){
	var buttons = element.getElementsByClassName('buttons')[0]
	buttons.style.display = 'inline-block'
}

function hideButtons(element){
	var buttons = element.getElementsByClassName('buttons')[0]
	buttons.style.display = 'none'
}

/* atomic actions */

/* senses */

function addSense(entryElement, nodeId){
	make_request('atomics/add_sense.php', 'n=' + nodeId, {
		success: function(response){
			console.log('add_sense: ' + response)
			if(parseInt(response)){
				location.reload()
			}
		}
	})
}
add_sense = addSense

function moveSenseUp(senseElement, nodeId){
	make_request('atomics/move_sense_up.php', 'n=' + nodeId, {
		success: function(response){
			console.log('move_sense_up: ' + response)
			if(response == 'OK'){
				previousSenseElement = senseElement.previousElementSibling /* not working in IE<9 */
				senseElement.parentNode.insertBefore(senseElement, previousSenseElement)
				
				senseLabelElement = senseElement.getElementsByClassName('sense_label_bar')[0].getElementsByClassName('sense_label')[0]
				previousSenseLabelElement = previousSenseElement.getElementsByClassName('sense_label_bar')[0].getElementsByClassName('sense_label')[0]
				
				bufferedSenseLabel = senseLabelElement.textContent;
				senseLabelElement.textContent = previousSenseLabelElement.textContent;
				previousSenseLabelElement.textContent = bufferedSenseLabel;
			}
		}
	})
}
move_sense_up = moveSenseUp

function moveSenseDown(senseElement, nodeId){
	make_request('atomics/move_sense_down.php', 'n=' + nodeId, {
		success: function(response){
			console.log('move_sense_down: ' + response)
			if(response == 'OK'){
				var nextSenseElement = senseElement.nextElementSibling /* not working in IE<9 */
				senseElement.parentNode.insertBefore(nextSenseElement, senseElement)
				
				var senseLabelElement = senseElement.getElementsByClassName('sense_label_bar')[0].getElementsByClassName('sense_label')[0]
				nextSenseLabelElement = nextSenseElement.getElementsByClassName('sense_label_bar')[0].getElementsByClassName('sense_label')[0]
				
				var bufferedSenseLabel = senseLabelElement.textContent;
				senseLabelElement.textContent = nextSenseLabelElement.textContent;
				nextSenseLabelElement.textContent = bufferedSenseLabel;
			}
		}
	})
}
move_sense_down = moveSenseDown

function deleteSense(senseElement, nodeId){
	make_request('atomics/delete_sense.php', 'n=' + nodeId, {
		success: function(response){
			console.log('delete_sense: ' + response)
			if(response == 'OK'){
				senseElement.parentNode.removeChild(senseElement)
			}
		}
	})
}
delete_sense = deleteSense

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
	
	function cancelEditingPhrase(){
		phraseInput.onblur = null // hack for Chrome
		phraseInput.parentNode.removeChild(phraseInput)
		phrase.style.display = 'inline-block'
	}
	
	var phrase = phraseBar.getElementsByClassName('phrase')[0]
	
	var phraseInput = document.createElement('input')
	phraseInput.setAttribute('type', 'text')
	phraseInput.value = phrase.textContent
	phraseInput.onkeydown = function(event){
		if(event.keyCode == 13){
			if(phraseInput.value != phrase.textContent){
				phraseInput.disabled = true
				updatePhrase(phraseInput.parentNode, nodeId, phraseInput.value, cancelEditingPhrase)
			} else {
				cancelEditingPhrase()
			}
		}
		if(event.keyCode == 27){
			cancelEditingPhrase()
		}
	}
	phraseInput.onblur = cancelEditingPhrase
	
	phrase.style.display = 'none';
	phraseBar.insertBefore(phraseInput, phrase.nextElementSibling) /* not working in IE<9 */
	phraseInput.focus()
}

function updatePhrase(phraseBar, nodeId, phraseText, doOnSuccess, doOnFailure){
	make_request('atomics/update_phrase.php', 'n=' + nodeId + '&t=' + phraseText, {
		success: function(response){
			console.log('update_phrase: ' + response)
			if(response == 'OK'){
				phrase = phraseBar.getElementsByClassName('phrase')[0]
				phrase.textContent = phraseText
				
				if(doOnSuccess){
					doOnSuccess()
				}
			} else {
				doOnFailure
			}
		},
		failure: function(){
			if(doOnFailure){
				doOnFailure()
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

/* forms */

function addForm(nodeElement, nodeId){
	make_request('atomics/add_form.php', 'n=' + nodeId, {
		success: function(response){
			console.log('add_form: ' + response)
			/*
			if(parseInt(response)){
				formId = response
				forms = sense_element.getElementsByClassName('forms')[0]
				formBar = makeFormBar('...', formId)
				forms.appendChild(formBar)
				editForm(formBar, formId)
			}
			*/
			if(parseInt(response)){
				location.reload()
			}
		}
	})
}

function editForm(formBar, formId, focus){
	
	function cancelEditingForm(){
		labelInput.onblur = null // hack for Chrome
		formInput.onblur = null // hack for Chrome
		
		labelInput.parentNode.removeChild(labelInput)
		formInput.parentNode.removeChild(formInput)
		
		label.style.display = 'inline-block'
		form.style.display = 'inline-block'
	}
	
	function pressKey(event){
		if(event.keyCode == 13){
			if(formInput.value != form.textContent || labelInput.value != label.textContent){
				labelInput.disabled = true
				formInput.disabled = true
				updateForm(formInput.parentNode, formId, labelInput.value, formInput.value, cancelEditingForm)
			} else {
				cancelEditingForm()
			}
		}
		if(event.keyCode == 27){
			cancelEditingForm()
		}
	}
	
	// constructing label input
	
	var label = formBar.getElementsByClassName('form_label')[0]
	
	var labelInput = document.createElement('input')
	labelInput.setAttribute('type','text')
	labelInput.value = label.textContent
	labelInput.onkeydown = pressKey
	/*labelInput.onblur = cancelEditingForm*/
	
	// constructing form input
	
	var form = formBar.getElementsByClassName('form')[0]
	
	var formInput = document.createElement('input')
	formInput.setAttribute('type','text')
	formInput.value = form.textContent
	formInput.onkeydown = pressKey
	/*formInput.onblur = cancelEditingForm*/
	
	// displaying
	label.style.display = 'none';
	form.style.display = 'none';
	formBar.insertBefore(labelInput, label.nextElementSibling) /* not working in IE<9 */
	formBar.insertBefore(formInput, form.nextElementSibling) /* not working in IE<9 */
	switch(focus){
		case 'label': labelInput.focus(); break
		default: formInput.focus(); break
	}
}

function updateForm(formBar, formId, formLabel, formHeadword, doOnSuccess, doOnFailure){
	make_request('atomics/update_form.php', 'id=' + formId + '&l=' + formLabel + '&h=' + formHeadword, {
		success: function(response){
			console.log('update_form: ' + response)
			if(response == 'OK'){
				var labelElement = formBar.getElementsByClassName('form_label')[0]
				labelElement.textContent = formLabel
				
				var formElement = formBar.getElementsByClassName('form')[0]
				formElement.textContent = formHeadword
				
				if(doOnSuccess){
					doOnSuccess()
				}
			}
		},
		failure: function(){
			if(doOnFailure){
				doOnFailure()
			}
		}
	})
}

function moveFormUp(formBar, formId){
	make_request('atomics/form.php', 'id=' + formId + '&a=move_up', {
		success: function(response){
			console.log('move_form_up: ' + response)
			if(response == 'OK'){
				formBar.parentNode.insertBefore(formBar, formBar.previousElementSibling) /* not working in IE<9 */
			}
		}
	})
}

function moveFormDown(formBar, formId){
	make_request('atomics/form.php', 'id=' + formId + '&a=move_down', {
		success: function(response){
			console.log('move_form_down: ' + response)
			if(response == 'OK'){
				formBar.parentNode.insertBefore(formBar.nextElementSibling, formBar) /* not working in IE<9 */
			}
		}
	})
}

function deleteForm(formBar, formId){
	make_request('atomics/form.php', 'id=' + formId + '&a=delete', {
		success: function(response){
			console.log('delete_form: ' + response)
			if(response == 'OK'){
				formBar.parentNode.removeChild(formBar)
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
				var translationId = response
				var translations = sense_element.getElementsByClassName('translations')[0]
				var translationBar = makeTranslationBar('...', translationId)
				translations.appendChild(translationBar)
				editTranslation(translationBar, translationId)
			}
		}
	})
}

function editTranslation(translationBar, translationId){
	
	function cancelEditingTranslation(){
		translationInput.onblur = null // hack for Chrome
		translationInput.parentNode.removeChild(translationInput)
		translation.style.display = 'inline-block'
	}
	
	var translation = translationBar.getElementsByClassName('translation')[0]
	
	var translationInput = document.createElement('input')
	translationInput.setAttribute('type','text')
	translationInput.value = translation.textContent
	translationInput.onkeydown = function(event){
		if(event.keyCode == 13){
			if(translationInput.value != translation.textContent){
				translationInput.disabled = true
				updateTranslation(translationInput.parentNode, translationId, translationInput.value, cancelEditingTranslation)
			} else {
				cancelEditingTranslation()
			}
		}
		if(event.keyCode == 27){
			cancelEditingTranslation()
		}
	}
	translationInput.onblur = cancelEditingTranslation
	
	translation.style.display = 'none';
	translationBar.insertBefore(translationInput, translation.nextElementSibling) /* not working in IE<9 */
	translationInput.focus()
	
}

function updateTranslation(translationBar, translationId, translationText, doOnSuccess){
	make_request('atomics/update_translation.php', 'id=' + translationId + '&t=' + translationText, {
		success: function(response){
			console.log('update_translation: ' + response)
			if(response == 'OK'){
				var translation = translationBar.getElementsByClassName('translation')[0]
				translation.textContent = translationText
				
				if(doOnSuccess){
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
	var translationBar = document.createElement('div')
	translationBar.setAttribute('class', 'translation_bar')
	translationBar.onmouseover = function(){ showButtons(translationBar) }
	translationBar.onmouseout = function(){ hideButtons(translationBar) }
	
	var translation = document.createElement('div')
	translation.setAttribute('class', 'translation')
	translation.onclick = function(){ editTranslation(translationBar, translationId) }
	translation.textContent = text
	translationBar.appendChild(translation)
	
	var buttons = document.createElement('div')
	buttons.setAttribute('class', 'buttons')
	translationBar.appendChild(buttons)
	
	var buttonDelete = document.createElement('button')
	buttonDelete.setAttribute('class', 'button delete')
	buttonDelete.onclick = function(){ deleteTranslation(translationBar, translationId) }
	buttonDelete.textContent = '×'
	buttons.appendChild(buttonDelete)
	
	var buttonUp = document.createElement('button')
	buttonUp.setAttribute('class', 'button move_up')
	buttonUp.onclick = function(){ moveTranslationUp(translationBar, translationId) }
	buttonUp.textContent = 'do góry'
	buttons.appendChild(buttonUp)
	
	var buttonDown = document.createElement('button')
	buttonDown.setAttribute('class', 'button move_down')
	buttonDown.onclick = function(){ moveTranslationDown(translationBar, translationId) }
	buttonDown.textContent = 'na dół'
	buttons.appendChild(buttonDown)
	
	var buttonEdit = document.createElement('button')
	buttonEdit.setAttribute('class', 'button edit')
	buttonEdit.onclick = function(){ editTranslation(translationBar, translationId) }
	buttonEdit.textContent = 'edytuj'
	buttons.appendChild(buttonEdit)
	
	return translationBar
}
