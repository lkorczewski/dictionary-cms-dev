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
	
	// todo: should show real returned value!!!
	add: function(nodeContent, nodeId){
		var action =  this.pluralName + '/add/' + encodeURIComponent(nodeId)
		var that = this
		makeJsonRequest(action, '', {
			success: function(response){
				if(response.status == 'success'){
					var valueId = response[that.name + '_id']
					var values = nodeContent.getElementsByClassName(that.pluralName)[0]
					var valueBar = that.makeBar('...', valueId)
					values.appendChild(valueBar)
					that.edit(valueBar, valueId)
				}
			}
		})
	},
	
	edit: function(valueBar, valueId){
		
		function cancelEditingValue(){
			input.onblur = null // hack for Chrome
			input.remove()
			element.style.display = 'inline-block'
		}
		
		var element = valueBar.getElementsByClassName(this.name)[0]
		var that = this;
		
		var input = document.createElement('input')
		input.setAttribute('type', 'text')
		input.setAttribute('class', this.name + '_input') // TO DO: finding alternative
		input.value = element.textContent
		input.onkeydown = function(event){
			if(event.keyCode == 13){
				if(input.value != element.textContent){
					input.disabled = true
					that.update(valueBar, valueId, input.value, cancelEditingValue, cancelEditingValue)
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
	
	update: function(valueBar, valueId, newText, doOnSuccess, doOnFailure){
		
		// TODO: newText -- improve the name
		var action = this.name + '/' + encodeURIComponent(valueId) + '/update'
		var parameters =
			'v=' + encodeURIComponent(newText)
		var that = this
		makeJsonRequest(action, parameters, {
			success: function(response){
				if(response.status == 'success'){
					var valueElement = valueBar.getElementsByClassName(that.name)[0]
					
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
	
	delete: function(valueBar, valueId, doOnSuccess){
		var action = this.name + '/' + encodeURIComponent(valueId) + '/delete'
		makeJsonRequest(action, '', {
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

var MultipleValue = {
	
	__proto__: Value,
	
	moveUp: function(valueBar, valueId){
		var action = this.name + '/' + encodeURIComponent(valueId) + '/move_up'
		makeJsonRequest(action, '', {
			success: function(response){
				if(response.status == 'success'){
					valueBar.moveUp()
				}
			}
		})
	},
	
	moveDown: function(valueBar, valueId){
		var action = this.name + '/' + encodeURIComponent(valueId) + '/move_down'
		makeJsonRequest(action, '', {
			success: function(response){
				if(response.status == 'success'){
					valueBar.moveDown()
				}
			}
		})
	},
}

//------------------------------------------------------------------------------
// node
//------------------------------------------------------------------------------

var Node = {
	
	add: function(parentElementContent, nodeId){
		var action =  this.pluralName + '/add/' + encodeURIComponent(nodeId)
		var that = this
		makeJsonRequest(action, '', {
			success: function(response){
				if(response.status == 'success'){
					var nodeId = response[that.name + '_id']
					var nodes = parentElementContent.getElementsByClassName(that.pluralName)[0]
					var nodeContainer = that.makeNodeContainer(nodeId, response) // todo: parameters directly from response, not yet implemented
					nodes.appendChild(nodeContainer)
				}
			}
		})
	},
	
	moveUp: function(element, nodeId){
		var action = this.name + '/' + encodeURIComponent(nodeId) + '/move_up'
		makeJsonRequest(action, '', {
			success: function(response){
				if(response.status == 'success'){
					element.moveUp()
				}
			}
		})
	},
	
	moveDown: function(element, nodeId){
		var action = this.name + '/' + encodeURIComponent(nodeId) + '/move_down'
		makeJsonRequest(action, '', {
			success: function(response){
				if(response.status == 'success'){
					element.moveDown()
				}
			}
		})
	},
	
	delete: function(element, nodeId){
		var action = this.name + '/' + encodeURIComponent(nodeId) + '/delete'
		makeJsonRequest(action, '', {
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
	
	name:        'entry',
	pluralName:  'entries',
	
	add: function(headword){
		var action = this.pluralName + '/add'
		var parameters = 'h=' + encodeURIComponent(headword)
		makeJsonRequest(action, parameters, {
			success: function(response){
				if(response.status == 'success'){
					window.location = '?h=' + encodeURIComponent(headword) + '&m=edition'
				}
			}
		})
	},
	
	// not used
	delete: function(nodeId){
		var action = this.name + '/' + encodeURIComponent(nodeId) + '/delete'
		makeJsonRequest(action, '', {
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
	
	__proto__:  Node,
	
	name:        'sense',
	pluralName:  'senses',
	
	add: function(parentElement, nodeId){
		var action =  this.pluralName + '/add/' + encodeURIComponent(nodeId)
		makeJsonRequest(action, '', {
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
		var action = this.name + '/' + encodeURIComponent(nodeId) + '/move_up'
		makeJsonRequest(action, '', {
			success: function(response){
				if(response.status == 'success'){
					var previousSenseElement = senseElement.previousElementSibling /* not working in IE<9 */
					
					senseElement.moveUp()
					
					var senseLabelElement = senseElement
						.getElementsByClassName('sense_label_bar')[0]
						.getElementsByClassName('sense_label')[0]
					var previousSenseLabelElement = previousSenseElement
						.getElementsByClassName('sense_label_bar')[0]
						.getElementsByClassName('sense_label')[0]
					
					var bufferedSenseLabel = senseLabelElement.textContent;
					senseLabelElement.textContent = previousSenseLabelElement.textContent;
					previousSenseLabelElement.textContent = bufferedSenseLabel;
				}
			}
		})
	},
	
	moveDown: function(senseElement, nodeId){
		var action = this.name + '/' + encodeURIComponent(nodeId) + '/move_down'
		makeJsonRequest(action, '', {
			success: function(response){
				if(response.status == 'success'){
					var nextSenseElement = senseElement.nextElementSibling /* not working in IE<9 */
					
					senseElement.moveDown()
					
					var senseLabelElement = senseElement
						.getElementsByClassName('sense_label_bar')[0]
						.getElementsByClassName('sense_label')[0]
					var nextSenseLabelElement = nextSenseElement
						.getElementsByClassName('sense_label_bar')[0]
						.getElementsByClassName('sense_label')[0]
					
					var bufferedSenseLabel = senseLabelElement.textContent;
					senseLabelElement.textContent = nextSenseLabelElement.textContent;
					nextSenseLabelElement.textContent = bufferedSenseLabel;
				}
			}
		})
	},
	
//	delete: function(senseElement, nodeId){
//		Node.delete(senseElement, nodeId)
//		// todo: labels should be moved
//	},
	
}

//------------------------------------------------------------------------------
// phrases
//------------------------------------------------------------------------------

var Phrase = {
	
	__proto__: Node,
	
	name:        'phrase',
	pluralName:  'phrases',
	
	phraseValue: {
		__proto__: Value,
		
		name: 'phrase',
		
		// temporary hack
		update: function(valueBar, valueId, newText, doOnSuccess, doOnFailure){
			// TODO: newText -- improve the name
			var action = this.name
				+ '/' + encodeURIComponent(valueId)
				+ '/update'
			var parameters =
				'v=' + encodeURIComponent(newText)
			var that = this
			makeJsonRequest(action, parameters, {
				success: function(response){
					if(response.status == 'success'){
						var valueElement = valueBar.getElementsByClassName(that.name)[0]
						
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
	},
	
	// overwrite because of location.reload()
	
	add: function(parentElement, nodeId){
		var action =  this.pluralName + '/add/' + encodeURIComponent(nodeId)
		makeJsonRequest(action, '', {
			success: function(response){
				if(response.status == 'success'){
					location.reload()
				}
			}
		})
	},
	
	edit: function(phraseBar, nodeId){
		this.phraseValue.edit(phraseBar, nodeId)
	},
	
}

//------------------------------------------------------------------------------
// headwords
//------------------------------------------------------------------------------

var Headword = {
	
	__proto__:   MultipleValue,
	
	name:        'headword',
	pluralName:  'headwords',
	
	makeBar:     makeHeadwordBar,
	
}

//------------------------------------------------------------------------------
// pronunciations
//------------------------------------------------------------------------------

var Pronunciation = {
	
	__proto__:   MultipleValue,
	
	name:        'pronunciation',
	pluralName:  'pronunciations',
	
	makeBar:     makePronunciationBar,
	
}

//------------------------------------------------------------------------------
// category labels
//------------------------------------------------------------------------------

var CategoryLabel = {
	
	__proto__:   Value,
	
	name:        'category_label',
	pluralName:  'category_labels',
	
	makeBar:     makeCategoryLabelBar,
	
}

//------------------------------------------------------------------------------
// forms
//------------------------------------------------------------------------------

var Form = {
	
	__proto__: MultipleValue,
	
	name:        'form',
	pluralName:  'forms',
	
	add: function addForm(nodeContent, nodeId){
		var action =  this.pluralName + '/add/' + encodeURIComponent(nodeId)
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
		var action = 'form/' + formId + '/update'
		var parameters = 
			'l=' + encodeURIComponent(formLabel) +
			'&f=' + encodeURIComponent(formHeadword)
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
	
}

//------------------------------------------------------------------------------
// contexts
//------------------------------------------------------------------------------

var Context = {
	
	__proto__: Value,
	
	name:        'context',
	pluralName:  'contexts',
	
	makeBar:     makeContextBar,
}

//------------------------------------------------------------------------------
// translations
//------------------------------------------------------------------------------

var Translation = {
	
	__proto__:   MultipleValue,
	
	name:        'translation',
	pluralName:  'translations',
	
	makeBar:     makeTranslationBar,
	
}
