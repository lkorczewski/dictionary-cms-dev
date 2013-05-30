/* configuration */
var actionPath = 'atomics'

/* misc */

function showButtons(element){
	var buttons = element.getElementsByClassName('buttons')[0]
	buttons.style.display = 'inline-block'
}

function hideButtons(element){
	var buttons = element.getElementsByClassName('buttons')[0]
	buttons.style.display = 'none'
}

/* common actions */

function editElement(elementBar, elementClass, inputClass, doOnChange, id){
	
	function cancelEditingElement(){
		input.onblur = null // hack for Chrome
		input.parentNode.removeChild(input)
		element.style.display = 'inline-block'
	}
	
	var element = elementBar.getElementsByClassName(elementClass)[0]
	
	var input = document.createElement('input')
	input.setAttribute('type', 'text')
	input.setAttribute('class', inputClass) // TO DO: finding alternative
	input.value = element.textContent
	input.onkeydown = function(event){
		if(event.keyCode == 13){
			if(input.value != element.textContent){
				input.disabled = true
				doOnChange(input.parentNode, id, input.value, cancelEditingElement)
			} else {
				cancelEditingElement()
			}
		}
		if(event.keyCode == 27){
			cancelEditingElement()
		}
	}
	input.onblur = cancelEditingElement
	
	element.style.display = 'none';
	elementBar.insertBefore(input, element.nextElementSibling) /* not working in IE<9 */
	input.focus()
}

/* atomic actions */

/* entry */

function editEntryHeadword(headwordBar, nodeId){
	editElement(
		headwordBar,
		'headword',
		'headword_input',
		updateEntryHeadword,
		nodeId
	)
}
edit_entry_headword = editEntryHeadword

function updateEntryHeadword(headwordBar, nodeId, headwordText, doOnSuccess, doOnFailure){
	make_request(actionPath + '/update_entry.php', 'n=' + nodeId + '&h=' + headwordText, {
		success: function(response){
			console.log('update_translation: ' + response)
			if(response == 'OK'){
				var headword = headwordBar.getElementsByClassName('headword')[0]
				headword.textContent = headwordText
				
				if(doOnSuccess){
					doOnSuccess()
				}
				
				window.location = '?h=' + headwordText + '&m=edition' // here will be problem with
			} else {
				if(doOnFailure){
					doOnFailure()
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

function deleteEntry(nodeId){
	make_request(actionPath + '/delete_entry.php', 'n=' + nodeId, {
		success: function(response){
			console.log('delete_entry: ' + response)
			if(response == 'OK'){
				location.reload()
			}
		}
	})	
}
delete_entry = deleteEntry

/* senses */

function addSense(entryElement, nodeId){
	make_request(actionPath + '/add_sense.php', 'n=' + nodeId, {
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
	make_request(actionPath + '/move_sense_up.php', 'n=' + nodeId, {
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
	make_request(actionPath + '/move_sense_down.php', 'n=' + nodeId, {
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
	make_request(actionPath + '/delete_sense.php', 'n=' + nodeId, {
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
	make_request(actionPath + '/add_phrase.php', 'n=' + nodeId, {
		success: function(response){
			console.log('add_phrase: ' + response)
			if(parseInt(response)){
				location.reload()
			}
		}
	})
}

function editPhrase(phraseBar, nodeId){
	editElement(
		phraseBar,
		'phrase',
		'phrase_input',
		updatePhrase,
		nodeId
	)
}

function updatePhrase(phraseBar, nodeId, phraseText, doOnSuccess, doOnFailure){
	make_request(actionPath + '/update_phrase.php', 'n=' + nodeId + '&t=' + phraseText, {
		success: function(response){
			console.log('update_phrase: ' + response)
			if(response == 'OK'){
				phrase = phraseBar.getElementsByClassName('phrase')[0]
				phrase.textContent = phraseText
				
				if(doOnSuccess){
					doOnSuccess()
				}
			} else {
				if(doOnFailure){
					doOnFailure
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

function movePhraseUp(phraseContainer, nodeId){
	make_request(actionPath + '/move_phrase_up.php', 'n=' + nodeId, {
		success: function(response){
			console.log('move_phrase_up: ' + response)
			if(response == 'OK'){
				phraseContainer.parentNode.insertBefore(phraseContainer, phraseContainer.previousElementSibling) /* not working in IE<9 */
			}
		}
	})
}

function movePhraseDown(phraseContainer, nodeId){
	make_request(actionPath + '/move_phrase_down.php', 'n=' + nodeId, {
		success: function(response){
			console.log('move_phrase_down: ' + response)
			if(response == 'OK'){
				phraseContainer.parentNode.insertBefore(phraseContainer.nextElementSibling, phraseContainer) /* not working in IE<9 */
			}
		}
	})
}

function deletePhrase(phraseElement, nodeId){
	make_request(actionPath + '/delete_phrase.php', 'n=' + nodeId, {
		success: function(response){
			console.log('delete_phrase: ' + response)
			if(response == 'OK'){
				phraseElement.parentNode.removeChild(phraseElement)
			}
		}
	})
}

/* category labels */

function addCategoryLabel(nodeContent, parentNodeId){
	make_request(actionPath + '/headword_node.php', 'n=' + parentNodeId + '&a=add_category_label', {
		success: function(response){
			console.log('add_category_label: ' + response)
			if(parseInt(response) || response == 'OK'){ // to be decided
				var categoryLabels = nodeContent.getElementsByClassName('category_labels')[0]
				var categoryLabelBar = makeCategoryLabelBar('...', parentNodeId)
				categoryLabels.appendChild(categoryLabelBar)
				editCategoryLabel(categoryLabelBar, parentNodeId)
			}
		}
	})
}

function editCategoryLabel(categoryLabelBar, parentNodeId){
	editElement(
		categoryLabelBar,
		'category_label',
		'category_label_input',
		updateCategoryLabel,
		parentNodeId
	)
}

function updateCategoryLabel(categoryLabelBar, parentNodeId, categoryLabelText, doOnSuccess, doOnFailure){
	make_request(actionPath + '/category_label.php', 'n=' + parentNodeId + '&a=set&t=' + categoryLabelText, {
		success: function(response){
			console.log('update_category_label: ' + response)
			if(response == 'OK'){
				categoryLabel = categoryLabelBar.getElementsByClassName('category_label')[0]
				categoryLabel.textContent = categoryLabelText
				
				if(doOnSuccess){
					doOnSuccess()
				}
			} else {
				if(doOnFailure){
					doOnFailure
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

function setCategoryLabel(categoryLabelBar, parentNodeId, categoryLabel){
	make_request(actionPath + '/category_label.php', 'n=' + parentNodeId + '&a=set&l=' + categoryLabel, {
		success: function(response){
			console.log('set_category_label: ' + response)
			if(response == 'OK'){
				// shoud be replaced by DOM modification
				location.reload()
			}
		}
	})
}

function deleteCategoryLabel(categoryLabelBar, parentNodeId){
	make_request(actionPath + '/category_label.php', 'n=' + parentNodeId + '&a=delete', {
		success: function(response){
			console.log('delete_category_label: ' + response)
			if(response == 'OK'){
				categoryLabelBar.parentNode.removeChild(categoryLabelBar)
				// there needs to show the button adding the label
			}
		}
	})	
}

/* forms */

function addForm(nodeContent, nodeId){
	make_request(actionPath + '/add_form.php', 'n=' + nodeId, {
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
	make_request(actionPath + '/update_form.php', 'id=' + formId + '&l=' + formLabel + '&h=' + formHeadword, {
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
			} else {
				if(doOnFailure){
					doOnFailure()
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
	make_request(actionPath + '/form.php', 'id=' + formId + '&a=move_up', {
		success: function(response){
			console.log('move_form_up: ' + response)
			if(response == 'OK'){
				formBar.parentNode.insertBefore(formBar, formBar.previousElementSibling) /* not working in IE<9 */
			}
		}
	})
}

function moveFormDown(formBar, formId){
	make_request(actionPath + '/form.php', 'id=' + formId + '&a=move_down', {
		success: function(response){
			console.log('move_form_down: ' + response)
			if(response == 'OK'){
				formBar.parentNode.insertBefore(formBar.nextElementSibling, formBar) /* not working in IE<9 */
			}
		}
	})
}

function deleteForm(formBar, formId){
	make_request(actionPath + '/form.php', 'id=' + formId + '&a=delete', {
		success: function(response){
			console.log('delete_form: ' + response)
			if(response == 'OK'){
				formBar.parentNode.removeChild(formBar)
			}
		}
	})
}

/* translations */

function addTranslation(nodeContent, nodeId){
	make_request(actionPath + '/add_translation.php', 'id=' + nodeId, {
		success: function(response){
			console.log('add_translation: ' + response)
			if(parseInt(response)){
				var translationId = response
				var translations = nodeContent.getElementsByClassName('translations')[0]
				var translationBar = makeTranslationBar('...', translationId)
				translations.appendChild(translationBar)
				editTranslation(translationBar, translationId)
			}
		}
	})
}

function editTranslation(translationBar, translationId){

	editElement(
		translationBar,
		'translation',
		'translation_input',
		updateTranslation,
		translationId
	)
	
}

function updateTranslation(translationBar, translationId, translationText, doOnSuccess, doOnFailure){
	make_request(actionPath + '/update_translation.php', 'id=' + translationId + '&t=' + translationText, {
		success: function(response){
			console.log('update_translation: ' + response)
			if(response == 'OK'){
				var translation = translationBar.getElementsByClassName('translation')[0]
				translation.textContent = translationText
				
				if(doOnSuccess){
					doOnSuccess()
				}
			} else {
				if(doOnFailure){
					doOnFailure()
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

function moveTranslationUp(translationBar, translationId){
	make_request(actionPath + '/move_translation_up.php', 'id=' + translationId, {
		success: function(response){
			console.log('move_translation_up: ' + response)
			if(response == 'OK'){
				translationBar.parentNode.insertBefore(translationBar, translationBar.previousElementSibling) /* not working in IE<9 */
			}
		}
	})
}

function moveTranslationDown(translationBar, translationId){
	make_request(actionPath + '/move_translation_down.php', 'id=' + translationId, {
		success: function(response){
			console.log('move_translation_down: ' + response)
			if(response == 'OK'){
				translationBar.parentNode.insertBefore(translationBar.nextElementSibling, translationBar) /* not working in IE<9 */
			}
		}
	})
}

function deleteTranslation(translationBar, translationId){
	make_request(actionPath + '/delete_translation.php', 'id=' + translationId, {
		success: function(response){
			console.log('delete_translation: ' + response)
			if(response == 'OK'){
				translationBar.parentNode.removeChild(translationBar)
			}
		}
	})
}

