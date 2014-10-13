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

//------------------------------------------------------------------------------
// value
//------------------------------------------------------------------------------

var Value = {
	
	edit: function(mapper, valueBar, id){
		
		function cancelEditingValue(){
			input.onblur = null // hack for Chrome
			input.remove()
			element.style.display = 'inline-block'
		}
		
		var element = valueBar.getElementsByClassName(mapper.name)[0]
		
		var input = document.createElement('input')
		input.setAttribute('type', 'text')
		input.setAttribute('class', mapper.name + '_input') // TO DO: finding alternative
		input.value = element.textContent
		input.onkeydown = function(event){
			if(event.keyCode == 13){
				if(input.value != element.textContent){
					input.disabled = true
					mapper.update(valueBar, id, input.value, cancelEditingValue, cancelEditingValue)
				} else {
					cancelEditingValue()
				}
			}
			if(event.keyCode == 27){
				cancelEditingValue()
			}
		}
		input.onblur = cancelEditingValue
		
		element.style.display = 'none';
		valueBar.insertBefore(input, element.nextElementSibling) /* not working in IE<9 */
		input.focus()
	},
	
	update: function(mapper, valueBar, valueId, newText, doOnSuccess, doOnFailure){
		// TODO: newText -- improve the name
		var action = actionPath + '/' + mapper.name + '.php'
		var parameters =
			'id=' + encodeURIComponent(valueId) +
			'&a=update' +
			'&t=' + newText
		makeJsonRequest(action, parameters, {
			success: function(response){
				if(response.status == 'success'){
					var valueElement = valueBar.getElementsByClassName(mapper.name)[0]
					
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
	},
	
	moveUp: function(mapper, valueBar, valueId){
		var action = mapper + '/' + mapper.name + '.php'
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
	},
	
	moveDown: function(mapper, valueBar, valueId){
		var action = actionPath + '/' + mapper.name + '.php'
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
	},
	
	delete: function(mapper, valueBar, valueId, doOnSuccess){
		var action = actionPath + '/' + mapper.name + '.php'
		var parameters =
			'id=' + encodeURIComponent(valueId) +
			'&a=delete'
		makeJsonRequest(action, parameters, {
			success: function(response){
				if(response.status == 'success'){
					valueBar.remove()
					
					if(doOnSuccess){
						doOnSuccess()
					}
				}
			}
		})
	},
	
}

//------------------------------------------------------------------------------
// node
//------------------------------------------------------------------------------

var Node = {
	
	moveUp: function(mapper, element, nodeId){
		var action = actionPath + '/' + mapper.name + '.php'
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
	},
	
	moveDown: function(mapper, element, nodeId){
		var action = actionPath + '/' + mapper.name + '.php'
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
	},
	
	delete: function(mapper, element, nodeId){
		var action = actionPath + '/' + mapper.name + '.php'
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
	},
	
}

//==============================================================================
// atomic actions
//==============================================================================

//------------------------------------------------------------------------------
// entry
//------------------------------------------------------------------------------

var Entry = {
	
	add: function addEntry(headword){
		makeJsonRequest(actionPath + '/add_entry.php', 'h=' + encodeURIComponent(headword), {
			success: function(response){
				if(response.status == 'success'){
					window.location = '?h=' + encodeURIComponent(headword) + '&m=edition'
				}
			}
		})
	},
	
	/*
	editHeadword: function(headwordBar, nodeId){
		Value.edit(
			'headword',
			headwordBar,
			this.updateHeadword,
			nodeId
		)
	},
	
	updateHeadword: function(headwordBar, nodeId, headwordText, doOnSuccess, doOnFailure){
		var action = actionPath + '/update_entry.php'
		var parameter =
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
	},
	*/
	
	delete: function(nodeId){
		makeJsonRequest(actionPath + '/delete_entry.php', 'n=' + encodeURIComponent(nodeId), {
			success: function(response){
				if(response.status == 'success'){
					location.reload()
				}
			}
		})	
	},
	
}

//------------------------------------------------------------------------------
// senses
//------------------------------------------------------------------------------

var Sense = {
	
	name: 'sense',
	
	add: function(parentElement, nodeId){
		var action = actionPath + '/Node,php'
		var parameters =
			'n=' + encodeURIComponent(nodeId) +
			'&a=add_sense'
		makeJsonRequest(action, parameters, {
			success: function(response){
				if(response.status == 'success'){
					var senses = parentElement.getElementsByClassName('senses')[0]
					var senseContainer = makeSenseContainer(response.node_id, response.label)
					senses.appendChild(senseContainer)
				}
			}
		})
	},
	
	moveUp: function(senseElement, nodeId){
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
					var previousSenseLabelElement = previousSenseElement.getElementsByClassName('sense_label_bar')[0].getElementsByClassName('sense_label')[0]
					
					var bufferedSenseLabel = senseLabelElement.textContent;
					senseLabelElement.textContent = previousSenseLabelElement.textContent;
					previousSenseLabelElement.textContent = bufferedSenseLabel;
				}
			}
		})
	},
	
	moveDown: function(senseElement, nodeId){
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
					var nextSenseLabelElement = nextSenseElement.getElementsByClassName('sense_label_bar')[0].getElementsByClassName('sense_label')[0]
					
					var bufferedSenseLabel = senseLabelElement.textContent;
					senseLabelElement.textContent = nextSenseLabelElement.textContent;
					nextSenseLabelElement.textContent = bufferedSenseLabel;
				}
			}
		})
	},
	
	delete: function(senseElement, nodeId){
		Node.delete(this, senseElement, nodeId)
		// todo: labels should be moved
	},
	
}

//------------------------------------------------------------------------------
// phrases
//------------------------------------------------------------------------------

var Phrase = {
	
	name: 'phrase',
	
	add: function(parentElement, nodeId){
		var action = actionPath + '/node.php'; 
		var parameters =
			'n=' + encodeURIComponent(nodeId) +
			'&a=add_phrase'
		makeJsonRequest(action, parameters, {
			success: function(response){
				if(response.status == 'success'){
					location.reload()
				}
			}
		})
	},
	
	edit: function(phraseBar, nodeId){
		Value.edit(this, phraseBar, nodeId)
	},
	
	update: function(phraseBar, nodeId, phraseText, doOnSuccess, doOnFailure){
		var action = actionPath + '/phrase.php'
		var parameters =
			'n=' + encodeURIComponent(nodeId) +
			'&a=update' +
			'&t=' + encodeURIComponent(phraseText)
		makeJsonRequest(action, parameters, {
			success: function(response){
				if(response.status == 'success'){
					var phrase = phraseBar.getElementsByClassName('phrase')[0]
					phrase.textContent = phraseText
					
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
	},
	
	moveUp: function(phraseContainer, nodeId){
		Node.moveUp(this, phraseContainer, nodeId)
	},
	
	moveDown: function(phraseContainer, nodeId){
		Node.moveDown(this, phraseContainer, nodeId)
	},
	
	delete: function(phraseContainer, nodeId){
		Node.delete(this, phraseContainer, nodeId)
	},
	
}

//------------------------------------------------------------------------------
// headwords
//------------------------------------------------------------------------------

var Headword = {
	
	add: function(parentElement, parentNodeId){
		var action = actionPath + '/node.php'
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
	},
	
	edit: function(headwordBar, parentNodeId){
		Value.edit(this, headwordBar, parentNodeId)
	},
	
	update: function(headwordBar, headwordId, headwordText, doOnSuccess, doOnFailure){
		Value.update(this, headwordBar, headwordId, headwordText, doOnSuccess, doOnFailure)
	},
	
	moveUp: function(headwordBar, headwordId){
		Value.moveUp(this, headwordBar, headwordId)
	},
	
	moveDown: function(headwordBar, headwordId){
		Value.moveDown(this, headwordBar, headwordId)
	},
	
	delete: function(headwordBar, headwordId){
		Value.delete(this, headwordBar, headwordId)
	},
	
}

//------------------------------------------------------------------------------
// pronunciations
//------------------------------------------------------------------------------

var Pronunciation = {
	
	name: 'pronunciation',
	
	add: function(nodeContent, parentNodeId){
		var action = actionPath + '/node.php'
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
					this.edit(pronunciationBar, pronunciationId)
				}
			}
		})
	},
	
	edit: function(pronunciationBar, parentNodeId){
		Value.edit(this, pronunciationBar, parentNodeId)
	},
	
	update: function(pronunciationBar, pronunciationId, pronunciationText, doOnSuccess, doOnFailure){
		Value.update(this, pronunciationBar, pronunciationId, pronunciationText, doOnSuccess, doOnFailure)
	},
	
	moveUp: function(pronunciationBar, pronunciationId){
		Value.moveUp(this, pronunciationBar, pronunciationId)
	},
	
	moveDown: function(pronunciationBar, pronunciationId){
		Value.moveDown(this, pronunciationBar, pronunciationId)
	},
	
	delete: function(pronunciationBar, pronunciationId){
		Value.delete(this, pronunciationBar, pronunciationId)
	},
	
}

//------------------------------------------------------------------------------
// category labels
//------------------------------------------------------------------------------

var CategoryLabel = {
	
	name: 'category_label',
	
	add: function(nodeContent, parentNodeId){
		var action = actionPath + '/node.php'
		var parameters =
			'n=' + encodeURIComponent(parentNodeId) +
			'&a=add_category_label'
		makeJsonRequest(action, parameters, {
			success: function(response){
				if(response.status == 'success'){
					var categoryLabels = nodeContent.getElementsByClassName('category_labels')[0]
					var categoryLabelBar = makeCategoryLabelBar('...', parentNodeId)
					categoryLabels.appendChild(categoryLabelBar)
					this.edit(categoryLabelBar, parentNodeId)
				}
			}
		})
	},
	
	edit: function(categoryLabelBar, parentNodeId){
		Value.edit(this, categoryLabelBar, parentNodeId)
	},
	
	update: function(categoryLabelBar, parentNodeId, categoryLabelText, doOnSuccess, doOnFailure){
		var action = actionPath + '/category_label.php'
		var parameters =
			'n=' + encodeURIComponent(parentNodeId) +
			'&a=set' +
			'&t=' + encodeURIComponent(categoryLabelText)
		makeJsonRequest(action, parameters, {
			success: function(response){
				if(response.status == 'success'){
					var categoryLabel = categoryLabelBar.getElementsByClassName('category_label')[0]
					categoryLabel.textContent = categoryLabelText
					
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
	},
	
	delete: function(categoryLabelBar, parentNodeId){
		makeJsonRequest(actionPath + '/category_label.php', 'n=' + encodeURIComponent(parentNodeId) + '&a=delete', {
			success: function(response){
				if(response.status == 'success'){
					categoryLabelBar.remove()
					// there needs to show the button adding the label
				}
			}
		})
	},
	
}

//------------------------------------------------------------------------------
// forms
//------------------------------------------------------------------------------

var Form = {
	
	name: 'form',
	
	add: function addForm(nodeContent, nodeId){
		var action = actionPath + '/add_form.php'
		var parameters =
			'n=' + encodeURIComponent(nodeId)
		
		makeJsonRequest(action, parameters, {
			success: function(response){
				if(response.status == 'success'){
					var formId = response.form_id
					var forms = nodeContent.getElementsByClassName('forms')[0]
					var formBar = makeFormBar('...', '...', formId)
					forms.appendChild(formBar)
					this.edit(formBar, formId)
				}
			}
		})
	},
	
	edit: function(formBar, formId, focus){
		
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
					Form.update(formInput.parentNode, formId, labelInput.value, formInput.value, cancelEditingForm)
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
	},
	
	update: function(formBar, formId, formLabel, formHeadword, doOnSuccess, doOnFailure){
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
	},
	
	moveUp: function(formBar, formId){
		Value.moveUp(this, formBar, formId)
	},
	
	moveDown: function(formBar, formId){
		Value.moveDown(this, formBar, formId)
	},
	
	delete: function(formBar, formId){
		Value.delete(this, formBar, formId)
	},
	
}

//------------------------------------------------------------------------------
// contexts
//------------------------------------------------------------------------------

var Context = {
	
	name: 'context',
	
	add: function(nodeContent, parentNodeId){
		var action = actionPath + '/node.php'
		var parameters =
			'n=' + encodeURIComponent(parentNodeId) +
			'&a=set_context'
		makeJsonRequest(action, parameters, {
			success: function(response){
				console.log(response)
				if(response.status == 'success'){
					var contextId = response.context_id
					console.log(contextId)
					var contexts = nodeContent.getElementsByClassName('contexts')[0]
					var contextBar = makeContextBar('...', contextId)
					contexts.appendChild(contextBar)
					this.edit(contextBar, parentNodeId)
				}
			}
		})
	},
	
	edit: function(contextBar, contextId){
		Value.edit(this, contextBar, contextId)
	},
	
	update: function(contextBar, contextId, contextText, doOnSuccess, doOnFailure){
		var action = actionPath + '/context.php'
		var parameters =
			'id=' + encodeURIComponent(contextId) +
			'&a=update' +
			'&t=' + encodeURIComponent(contextText)
		makeJsonRequest(action, parameters, {
			success: function(response){
				if(response.status == 'success'){
					var context = contextBar.getElementsByClassName('context')[0]
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
	},
	
	delete: function(contextBar, contextId){
		Value.delete('context', contextBar, contextId/*, function(){
			var contexts = contextBar.parentNode
			var contextButtonBar = contexts.getElementsByClassName('context_button_bar')[0]
			contextButtonBar.remove()
		}*/)
	},
	
}

//------------------------------------------------------------------------------
// translations
//------------------------------------------------------------------------------

var Translation = {
	
	name: 'translation',
	
	add: function(nodeContent, nodeId){
		var action = actionPath + '/node.php'
		var parameters =
			'n=' + encodeURIComponent(nodeId) +
			'&a=add_translation'
		makeJsonRequest(action, parameters, {
			success: function(response){
				if(response.status == 'success'){
					var translationId = response.translation_id
					var translations = nodeContent.getElementsByClassName('translations')[0]
					var translationBar = makeTranslationBar('...', translationId)
					translations.appendChild(translationBar)
					this.edit(translationBar, translationId)
				}
			}
		})
	},
	
	edit: function(translationBar, translationId){
		Value.edit(this, translationBar, translationId)
	},
	
	update: function(translationBar, translationId, translationText, doOnSuccess, doOnFailure){
		Value.update(this, translationBar, translationId, translationText, doOnSuccess, doOnFailure)
	},
	
	moveUp: function(translationBar, translationId){
		Value.moveUp(this, translationBar, translationId)
	},
	
	moveDown: function(translationBar, translationId){
		Value.moveDown(this, translationBar, translationId)
	},
	
	delete: function(translationBar, translationId){
		Value.delete(this, translationBar, translationId)
	},
	
}
