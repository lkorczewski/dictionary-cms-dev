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
				doOnChange(elementBar, id, input.value, cancelEditingElement, cancelEditingElement)
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

function updateElement(element, action, parameters, id, newText, doOnSuccess, doOnFailure){
	make_request(action, parameters, {
		success: function(response){
			console.log(action + '?' + parameters + ': ' + response)
			if(response == 'OK'){
				element.textContent = newText
				
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

/* atomic actions */

/* entry */

function addEntry(headword){
	makeRequest(actionPath + '/add_entry.php', 'h=' + encodeURIComponent(headword), {
		success: function(response){
			console.log('add_entry: ' + response)
			if(response == 'OK'){
				location.reload()
			}
		}
	})
}

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
	action = actionPath + '/update_entry.php'
	parameter =
		'n=' + encodeURIComponent(nodeId) +
		'&h=' + encodeURIComponent(headwordText)
	make_request(action, parameters, {
		success: function(response){
			console.log('update_translation: ' + response)
			if(response == 'OK'){
				var headword = headwordBar.getElementsByClassName('headword')[0]
				headword.textContent = headwordText
				
				if(doOnSuccess){
					doOnSuccess()
				}
				
				window.location = '?h=' + encodeURIComponent(headwordText) + '&m=edition' // here will be problem with
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
	make_request(actionPath + '/delete_entry.php', 'n=' + encodeURIComponent(nodeId), {
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
	make_request(actionPath + '/add_sense.php', 'n=' + encodeURIComponent(nodeId), {
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
	action = actionPath + '/sense.php'
	parameters =
		'n=' + encodeURIComponent(nodeId) +
		'&a=move_up'
	make_request(action, parameters, {
		success: function(response){
			console.log('move_sense_up: ' + response)
			if(response == 'OK'){
				var previousSenseElement = senseElement.previousElementSibling /* not working in IE<9 */
				
				senseElement.moveUp()
				
				var senseLabelElement = senseElement.getElementsByClassName('sense_label_bar')[0].getElementsByClassName('sense_label')[0]
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
	action = actionPath + '/sense.php'
	parameters =
		'n=' + encodeURIComponent(nodeId) +
		'&a=move_down'
	make_request(action, parameters, {
		success: function(response){
			console.log('move_sense_down: ' + response)
			if(response == 'OK'){
				var nextSenseElement = senseElement.nextElementSibling /* not working in IE<9 */
				
				senseElement.moveDown()
				
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
	action = actionPath + '/sense.php'
	parameters =
		'n=' + encodeURIComponent(nodeId) +
		'&a=delete'
	make_request(action, parameters, {
		success: function(response){
			console.log('delete_sense: ' + response)
			if(response == 'OK'){
				senseElement.delete()
			}
		}
	})
}
delete_sense = deleteSense

/* phrases */

function addPhrase(parentElement, nodeId){
	action = actionPath + '/add_phrase.php'; 
	parameters =
		'n=' + encodeURIComponent(nodeId)
	make_request(action, parameters, {
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
	action = actionPath + '/update_phrase.php'
	parameters =
		'n=' + encodeURIComponent(nodeId) +
		'&t=' + encodeURIComponent(phraseText)
	make_request(action, parameters, {
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
	action = actionPath + '/phrase.php' 
	parameters =
		'n=' + encodeURIComponent(nodeId) +
		'&a=move_up'
	make_request(action, parameters, {
		success: function(response){
			console.log('move_phrase_up: ' + response)
			if(response == 'OK'){
				phraseContainer.moveUp()
			}
		}
	})
}

function movePhraseDown(phraseContainer, nodeId){
	action = actionPath + '/phrase.php'
	parameters =
		'n=' + encodeURIComponent(nodeId) +
		'&a=move_down'
	make_request(action, parameters, {
		success: function(response){
			console.log('move_phrase_down: ' + response)
			if(response == 'OK'){
				phraseContainer.moveDown()
			}
		}
	})
}

function deletePhrase(phraseContainer, nodeId){
	action = actionPath + '/phrase.php'
	parameters =
		'n=' + encodeURICOmponent(nodId) +
		'&a=delete'
	make_request(action, parameters, {
		success: function(response){
			console.log('delete_phrase: ' + response)
			if(response == 'OK'){
				phraseContainer.delete()
			}
		}
	})
}

//==============================================================================
// headwords
//==============================================================================

function addHeadword(parentElement, parentNodeId){
	action = actionPath + '/headword_node.php'
	parameters =
		'n=' + encodeURIComponent(parentNodeId) +
		'&a=add_headword'
	make_request(action, parameters, {
		success: function(response){
			console.log('add_headword: ' + response)
			if(parseInt(response)){
				location.reload()
			}
		}
	})
}

function editHeadword(headwordBar, parentNodeId){
	editElement(
		headwordBar,
		'headword',
		'headword_input',
		updateHeadword,
		parentNodeId
	)
}

function updateHeadword(headwordBar, headwordId, headwordText, doOnSuccess, doOnFailure){
	action = actionPath + '/headword.php'
	parameters =
		'id=' + encodeURIComponent(headwordId) +
		'&a=update' +
		'&t=' + encodeURIComponent(headwordText)
	make_request(action, parameters, {
		success: function(response){
			console.log('update_headword: ' + response)
			if(response == 'OK'){
				var headword = headwordBar.getElementsByClassName('headword')[0]
				headword.textContent = headwordText
				
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

function moveHeadwordUp(headwordBar, headwordId){
	action = actionPath + '/headword.php'
	parameters =
		'id=' + encodeURIComponent(headwordId) +
		'&a=move_up'
	make_request(action, parameters, {
		success: function(response){
			console.log('move_headword_up: ' + response)
			if(response == 'OK'){
				headwordBar.moveUp();
			}
		}
	})
}

function moveHeadowrdDown(headwordBar, headwordId){
	action = actionPath + '/headword.php'
	parameters =
		'id=' + encodeURIComponent(headwordId) +
		'&a=move_down'
	make_request(action, parameters, {
		success: function(response){
			console.log('move_headword_down: ' + response)
			if(response == 'OK'){
				headwordBar.moveDown()
			}
		}
	})
}

function deleteHeadword(headwordBar, headwordId){
	action = actionPath + '/headword.php'
	parameters =
		'id=' + encodeURIComponent(headwordId) +
		'&a=delete'
	make_request(action, parameters, {
		success: function(response){
			console.log('delete_headword: ' + response)
			if(response == 'OK'){
				headwordBar.parentNode.removeChild(headwordBar)
			}
		}
	})
}

/* category labels */

function addCategoryLabel(nodeContent, parentNodeId){
	make_request(actionPath + '/headword_node.php', 'n=' + encodeURIComponent(parentNodeId) + '&a=add_category_label', {
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
	action = actionPath + '/category_label.php'
	parameters =
		'n=' + encodeURIComponent(parentNodeId) +
		'&a=set' +
		'&t=' + encodeURIComponent(categoryLabelText)
	make_request(action, parameters, {
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

/*
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
*/

function deleteCategoryLabel(categoryLabelBar, parentNodeId){
	make_request(actionPath + '/category_label.php', 'n=' + encodeURIComponent(parentNodeId) + '&a=delete', {
		success: function(response){
			console.log('delete_category_label: ' + response)
			if(response == 'OK'){
				categoryLabelBar.delete()
				// there needs to show the button adding the label
			}
		}
	})
}

/* forms */

function addForm(nodeContent, nodeId){
	make_request(actionPath + '/add_form.php', 'n=' + encodeURIComponent(nodeId), {
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
	label.style.display = 'none'
	form.style.display = 'none'
	formBar.insertBefore(labelInput, label.nextElementSibling) /* not working in IE<9 */
	formBar.insertBefore(formInput, form.nextElementSibling) /* not working in IE<9 */
	switch(focus){
		case 'label': labelInput.focus(); break
		default: formInput.focus(); break
	}
}

function updateForm(formBar, formId, formLabel, formHeadword, doOnSuccess, doOnFailure){
	action = actionPath + '/form.php'
	parameters = 
		'id=' + formId +
		'a=update' +
		'&l=' + encodeURIComponent(formLabel) +
		'&t=' + encodeURIComponent(formHeadword)
	make_request(action, parameters,{
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
	action = actionPath + '/form.php'
	parameters =
		'id=' + encodeURIComponent(formId) +
		'&a=move_up'
	make_request(action, parameters, {
		success: function(response){
			console.log('move_form_up: ' + response)
			if(response == 'OK'){
				formBar.moveUp()
			}
		}
	})
}

function moveFormDown(formBar, formId){
	action = actionPath + '/form.php'
	parameters =
		'id=' + encodeURIComponent(formId) +
		'&a=move_up'
	make_request(action, parameters, {
		success: function(response){
			console.log('move_form_down: ' + response)
			if(response == 'OK'){
				formBar.moveDown()
			}
		}
	})
}

function deleteForm(formBar, formId){
	action = actionPath + '/form.php'
	parameters =
		'id=' + encodeURIComponent(formId) +
		'&a=delete'
	make_request(action, parameters, {
		success: function(response){
			console.log('delete_form: ' + response)
			if(response == 'OK'){
				formBar.delete()
			}
		}
	})
}

/* contexts */

function addContext(nodeContent, parentNodeId){
	action = actionPath + '/sense.php'
	parameters =
		'n=' + encodeURIComponent(parentNodeId) +
		'&a=add_context'
	make_request(action, parameters, {
		success: function(response){
			console.log('add_context: ' + response)
			if(parseInt(response) || response == 'OK'){ // to be decided
				/*
				location.reload()
				*/
				var contexts = nodeContent.getElementsByClassName('contexts')[0]
				var contextBar = makeContextBar('...', parentNodeId)
				contexts.appendChild(contextBar)
				editContext(contextBar, parentNodeId)
			}
		}
	})
}

function editContext(contextBar, parentNodeId){
	editElement(
		contextBar,
		'context',
		'context_input',
		updateContext,
		parentNodeId
	)
}

function updateContext(contextBar, parentNodeId, contextText, doOnSuccess, doOnFailure){
	action = actionPath + '/context.php'
	parameters =
		'n=' + encodeURIComponent(parentNodeId) +
		'&a=set' +
		'&t=' + encodeURIComponent(contextText)
	make_request(action, parameters, {
		success: function(response){
			console.log('update_context: ' + response)
			if(response == 'OK'){
				context = contextBar.getElementsByClassName('context')[0]
				context.textContent = contextText
				
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

/*
function setContext(contextBar, parentNodeId, context){
	make_request(actionPath + '/sense.php', 'n=' + parentNodeId + '&a=set&l=' + encodeURIComponent(categoryLabel), {
		success: function(response){
			console.log('set_category_label: ' + response)
			if(response == 'OK'){
				// shoud be replaced by DOM modification
				location.reload()
			}
		}
	})
}
*/

function deleteContext(contextBar, parentNodeId){
	action = actionPath + '/context.php'
	parameters =
		'n=' + parentNodeId +
		'&a=delete'
	make_request(action, parameters, {
		success: function(response){
			console.log('delete_category_label: ' + response)
			if(response == 'OK'){
				contextBar.parentNode.removeChild(contextBar)
				// there needs to show the button adding the label
			}
		}
	})	
}

/* translations */

function addTranslation(nodeContent, nodeId){
	action = actionPath + '/add_translation.php'
	parameters =
		'id=' + encodeURIComponent(nodeId)
	make_request(action, parameters, {
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
	action = actionPath + '/translation.php'
	parameters =
		'id=' + encodeURIComponent(translationId) +
		'&a=update' +
		'&t=' + encodeURIComponent(translationText)
	make_request(action, parameters, {
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
	action = actionPath + '/translation.php'
	parameters =
		'id=' + encodeURIComponent(translationId) +
		'&a=move_up'
	make_request(action, parameters, {
		success: function(response){
			console.log('move_translation_up: ' + response)
			if(response == 'OK'){
				translationBar.moveUp()
			}
		}
	})
}

function moveTranslationDown(translationBar, translationId){
	action = actionPath + '/translation.php'
	parameters =
		'id=' + encodeURIComponent(translationId) +
		'&a=move_down'
	make_request(action, parameters, {
		success: function(response){
			console.log('move_translation_down: ' + response)
			if(response == 'OK'){
				translationBar.moveDown()
			}
		}
	})
}

function deleteTranslation(translationBar, translationId){
	action = actionPath + '/translation.php'
	parameters =
		'id=' + encodeURIComponent(translationId) +
		'&a=delete'
	make_request(action, parameters, {
		success: function(response){
			console.log('delete_translation: ' + response)
			if(response == 'OK'){
				translationBar.delete()
			}
		}
	})
}
