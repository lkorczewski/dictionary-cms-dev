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

function editElement(elementClass, elementBar, doOnChange, id){
	
	function cancelEditingElement(){
		input.onblur = null // hack for Chrome
		input.parentNode.removeChild(input)
		element.style.display = 'inline-block'
	}
	
	var element = elementBar.getElementsByClassName(elementClass)[0]
	
	var input = document.createElement('input')
	input.setAttribute('type', 'text')
	input.setAttribute('class', elementClass + '_input') // TO DO: finding alternative
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

function updateValue(valueName, valueBar, valueId, newText, doOnSuccess, doOnFailure){
	// TODO: newText -- improve the name
	var action = actionPath + '/' + valueName + '.php'
	var parameters =
		'id=' + encodeURIComponent(valueId) +
		'&a=update' +
		'&t=' + newText
	makeJsonRequest(action, parameters, {
		success: function(response){
			if(response.status == 'success'){
				valueElement = valueBar.getElementsByClassName(valueName)[0]
				
				if(response.value == undefined){
					valueElement.textContent = newText
				} else {
					valueElement.textContent = response.value
				}
				
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

// move node up
function moveNodeUp(name, element, nodeId){
	var action = actionPath + '/' + name + '.php'
	var parameters =
		'n=' + encodeURIComponent(nodeId) +
		'&a=move_up'
	makeJsonRequest(action, parameters, {
		success: function(response){
			if(response.status == 'success'){
				element.moveUp()
			}
		}
	})
}

// move node down
function moveNodeDown(name, element, nodeId){
	var action = actionPath + '/' + name + '.php'
	var parameters =
		'n=' + encodeURIComponent(nodeId) +
		'&a=move_down'
	makeJsonRequest(action, parameters, {
		success: function(response){
			if(response.status == 'success'){
				element.moveDown()
			}
		}
	})
}

// delete node
function deleteNode(name, element, nodeId){
	var action = actionPath + '/' + name + '.php'
	var parameters =
		'n=' + encodeURIComponent(nodeId) +
		'&a=delete'
	makeJsonRequest(action, parameters, {
		success: function(response){
			if(response.status == 'success'){
				element.remove()
			}
		}
	})
}

// move value up
function moveValueUp(valueName, valueBar, valueId){
	var action = actionPath + '/' + valueName + '.php'
	var parameters =
		'id=' + encodeURIComponent(valueId) +
		'&a=move_up'
	makeJsonRequest(action, parameters, {
		success: function(response){
			if(response.status == 'success'){
				valueBar.moveUp()
			}
		}
	})
}

// move value down
function moveValueDown(valueName, valueBar, valueId){
	var action = actionPath + '/' + valueName + '.php'
	var parameters =
		'id=' + encodeURIComponent(valueId) +
		'&a=move_down'
	makeJsonRequest(action, parameters, {
		success: function(response){
			if(response.status == 'success'){
				valueBar.moveDown()
			}
		}
	})
}

// delete value
function deleteValue(valueName, valueBar, valueId){
	var action = actionPath + '/' + valueName + '.php'
	var parameters =
		'id=' + encodeURIComponent(valueId) +
		'&a=delete'
	makeJsonRequest(action, parameters, {
		success: function(response){
			if(response.status == 'success'){
				valueBar.remove()
			}
		}
	})
}

//==============================================================================
// atomic actions
//==============================================================================

//------------------------------------------------------------------------------
// entry
//------------------------------------------------------------------------------

function addEntry(headword){
	makeJsonRequest(actionPath + '/add_entry.php', 'h=' + encodeURIComponent(headword), {
		success: function(response){
			if(response.status == 'success'){
				window.location = '?h=' + encodeURIComponent(headword) + '&m=edition'
			}
		}
	})
}

function editEntryHeadword(headwordBar, nodeId){
	editElement(
		'headword',
		headwordBar,
		updateEntryHeadword,
		nodeId
	)
}

function updateEntryHeadword(headwordBar, nodeId, headwordText, doOnSuccess, doOnFailure){
	action = actionPath + '/update_entry.php'
	parameter =
		'n=' + encodeURIComponent(nodeId) +
		'&h=' + encodeURIComponent(headwordText)
	makeJsonRequest(action, parameters, {
		success: function(response){
			if(response.status == 'success'){
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
	makeJsonRequest(actionPath + '/delete_entry.php', 'n=' + encodeURIComponent(nodeId), {
		success: function(response){
			if(response.status == 'success'){
				location.reload()
			}
		}
	})	
}

//------------------------------------------------------------------------------
// senses
//------------------------------------------------------------------------------

function addSense(parentElement, nodeId){
	makeJsonRequest(actionPath + '/add_sense.php', 'n=' + encodeURIComponent(nodeId), {
		success: function(response){
			if(response.status == 'success'){
				var senses = parentElement.getElementsByClassName('senses')[0]
				var senseContainer = makeSenseContainer(response.node_id, response.label)
				senses.appendChild(senseContainer)
			}
		}
	})
}

function moveSenseUp(senseElement, nodeId){
	var action = actionPath + '/sense.php'
	var parameters =
		'n=' + encodeURIComponent(nodeId) +
		'&a=move_up'
	makeJsonRequest(action, parameters, {
		success: function(response){
			if(response.status == 'success'){
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

function moveSenseDown(senseElement, nodeId){
	var action = actionPath + '/sense.php'
	var parameters =
		'n=' + encodeURIComponent(nodeId) +
		'&a=move_down'
	makeJsonRequest(action, parameters, {
		success: function(response){
			if(response.status == 'success'){
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

function deleteSense(senseElement, nodeId){
	deleteNode('sense', senseElement, nodeId)
}

//------------------------------------------------------------------------------
// phrases
//------------------------------------------------------------------------------

function addPhrase(parentElement, nodeId){
	var action = actionPath + '/add_phrase.php'; 
	var parameters =
		'n=' + encodeURIComponent(nodeId)
	makeJsonRequest(action, parameters, {
		success: function(response){
			if(response.status == 'success'){
				location.reload()
			}
		}
	})
}

function editPhrase(phraseBar, nodeId){
	editElement(
		'phrase',
		phraseBar,
		updatePhrase,
		nodeId
	)
}

function updatePhrase(phraseBar, nodeId, phraseText, doOnSuccess, doOnFailure){
	var action = actionPath + '/phrase.php'
	var parameters =
		'n=' + encodeURIComponent(nodeId) +
		'&a=update' +
		'&t=' + encodeURIComponent(phraseText)
	makeJsonRequest(action, parameters, {
		success: function(response){
			if(response.status == 'success'){
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
	moveNodeUp('phrase', phraseContainer, nodeId)
}

function movePhraseDown(phraseContainer, nodeId){
	moveNodeDown('phrase', phraseContainer, nodeId)
}

function deletePhrase(phraseContainer, nodeId){
	deleteNode('phrase', phraseContainer, nodeId)
}

//------------------------------------------------------------------------------
// headwords
//------------------------------------------------------------------------------

function addHeadword(parentElement, parentNodeId){
	var action = actionPath + '/headword_node.php'
	var parameters =
		'n=' + encodeURIComponent(parentNodeId) +
		'&a=add_headword'
	makeJsonRequest(action, parameters, {
		success: function(response){
			if(response.status == 'success'){
				location.reload()
			}
		}
	})
}

function editHeadword(headwordBar, parentNodeId){
	editElement('headword', headwordBar, updateHeadword, parentNodeId)
}

function updateHeadword(headwordBar, headwordId, headwordText, doOnSuccess, doOnFailure){
	updateValue('headword', headwordBar, headwordId, headwordText, doOnSuccess, doOnFailure)
}

function moveHeadwordUp(headwordBar, headwordId){
	moveValueUp('headword', headwordBar, headwordId)
}

function moveHeadwordDown(headwordBar, headwordId){
	moveValueDown('headword', headwordBar, headwordId)
}

function deleteHeadword(headwordBar, headwordId){
	deleteValue('headword', headwordBar, headwordId)
}

//------------------------------------------------------------------------------
// pronunciations
//------------------------------------------------------------------------------

function addPronunciation(nodeContent, parentNodeId){
	var action = actionPath + '/headword_node.php'
	var parameters =
		'n=' + encodeURIComponent(parentNodeId) +
		'&a=add_pronunciation'
	makeJsonRequest(action, parameters, {
		success: function(response){
			if(response.status == 'success'){
				var pronunciationId = response.pronunciation_id
				var pronunciations = nodeContent.getElementsByClassName('pronunciations')[0]
				var pronunciationBar = makePronunciationBar('...', pronunciationId)
				pronunciations.appendChild(pronunciationBar)
				editPronunciation(pronunciationBar, pronunciationId)
			}
		}
	})
}

function editPronunciation(pronunciationBar, parentNodeId){
	editElement('pronunciation', pronunciationBar, updatePronunciation, parentNodeId)
}

function updatePronunciation(pronunciationBar, pronunciationId, pronunciationText, doOnSuccess, doOnFailure){
	updateValue('pronunciation', pronunciationBar, pronunciationId, pronunciationText, doOnSuccess, doOnFailure)
}

function movePronunciationUp(pronunciationBar, pronunciationId){
	moveValueUp('pronunciation', pronunciationBar, pronunciationId)
}

function movePronunciationDown(pronunciationBar, pronunciationId){
	moveValueDown('pronunciation', pronunciationBar, pronunciationId)
}

function deletePronunciation(pronunciationBar, pronunciationId){
	deleteValue('pronunciation', pronunciationBar, pronunciationId)
}

//------------------------------------------------------------------------------
// category labels
//------------------------------------------------------------------------------


function addCategoryLabel(nodeContent, parentNodeId){
	var action = actionPath + '/headword_node.php'
	var parameters =
		'n=' + encodeURIComponent(parentNodeId) +
		'&a=add_category_label'
	makeJsonRequest(action, parameters, {
		success: function(response){
			if(response.status == 'success'){
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
		'category_label',
		categoryLabelBar,
		updateCategoryLabel,
		parentNodeId
	)
}

function updateCategoryLabel(categoryLabelBar, parentNodeId, categoryLabelText, doOnSuccess, doOnFailure){
	var action = actionPath + '/category_label.php'
	var parameters =
		'n=' + encodeURIComponent(parentNodeId) +
		'&a=set' +
		'&t=' + encodeURIComponent(categoryLabelText)
	makeJsonRequest(action, parameters, {
		success: function(response){
			if(response.status == 'success'){
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

function deleteCategoryLabel(categoryLabelBar, parentNodeId){
	makeJsonRequest(actionPath + '/category_label.php', 'n=' + encodeURIComponent(parentNodeId) + '&a=delete', {
		success: function(response){
			if(response.status == 'success'){
				categoryLabelBar.remove()
				// there needs to show the button adding the label
			}
		}
	})
}

//------------------------------------------------------------------------------
// forms
//------------------------------------------------------------------------------

function addForm(nodeContent, nodeId){
	makeJsonRequest(actionPath + '/add_form.php', 'n=' + encodeURIComponent(nodeId), {
		success: function(response){
			if(response.status == 'success'){
				formId = response.form_id
				forms = nodeContent.getElementsByClassName('forms')[0]
				formBar = makeFormBar('...', '...', formId)
				forms.appendChild(formBar)
				editForm(formBar, formId)
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
	var action = actionPath + '/form.php'
	var parameters = 
		'id=' + formId +
		'&a=update' +
		'&l=' + encodeURIComponent(formLabel) +
		'&t=' + encodeURIComponent(formHeadword)
	makeJsonRequest(action, parameters, {
		success: function(response){
			if(response.status == 'success'){
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
	moveValueUp('form', formBar, formId)
}

function moveFormDown(formBar, formId){
	moveValueDown('form', formBar, formId)
}

function deleteForm(formBar, formId){
	deleteValue('form', formBar, formId)
}

//------------------------------------------------------------------------------
// contexts
//------------------------------------------------------------------------------

function addContext(nodeContent, parentNodeId){
	var action = actionPath + '/sense.php'
	var parameters =
		'n=' + encodeURIComponent(parentNodeId) +
		'&a=add_context'
	makeJsonRequest(action, parameters, {
		success: function(response){
			if(response.status == 'success'){
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
		'context',
		contextBar,
		updateContext,
		parentNodeId
	)
}

function updateContext(contextBar, parentNodeId, contextText, doOnSuccess, doOnFailure){
	var action = actionPath + '/context.php'
	var parameters =
		'n=' + encodeURIComponent(parentNodeId) +
		'&a=set' +
		'&t=' + encodeURIComponent(contextText)
	makeJsonRequest(action, parameters, {
		success: function(response){
			if(response.status == 'success'){
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

function deleteContext(contextBar, parentNodeId){
	var action = actionPath + '/context.php'
	var parameters =
		'n=' + parentNodeId +
		'&a=delete'
	makeJsonRequest(action, parameters, {
		success: function(response){
			if(response.status == 'success'){
				contextBar.remove()
				// there needs to show up the button adding the label
			}
		}
	})	
}

/* translations */

function addTranslation(nodeContent, nodeId){
	var action = actionPath + '/add_translation.php'
	var parameters =
		'id=' + encodeURIComponent(nodeId)
	makeJsonRequest(action, parameters, {
		success: function(response){
			if(response.status == 'success'){
				var translationId = response.translation_id
				var translations = nodeContent.getElementsByClassName('translations')[0]
				var translationBar = makeTranslationBar('...', translationId)
				translations.appendChild(translationBar)
				editTranslation(translationBar, translationId)
			}
		}
	})
}

function editTranslation(translationBar, translationId){
	editElement('translation', translationBar, updateTranslation, translationId)
}

function updateTranslation(translationBar, translationId, translationText, doOnSuccess, doOnFailure){
	updateValue('translation', translationBar, translationId, translationText, doOnSuccess, doOnFailure)
}

function moveTranslationUp(translationBar, translationId){
	moveValueUp('translation', translationBar, translationId)
}

function moveTranslationDown(translationBar, translationId){
	moveValueDown('translation', translationBar, translationId)
}

function deleteTranslation(translationBar, translationId){
	deleteValue('translation', translationBar, translationId)
}
