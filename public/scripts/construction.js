function makeButton(name, action){
	var button = document.createElement('button')
	button.className = 'button ' + name
	button.onclick = action
	button.textContent = localization.getText(name)
	return button
}

function makeButton2(name, label, action){
	var button = document.createElement('button')
	button.className = 'button ' + name
	button.onclick = action
	button.textContent = localization.getText(label)
	return button
}

function makeButtons(buttonsArray){
	var buttons = document.createElement('div')
	buttons.className = 'buttons'
	
	for(var buttonLabel in buttonsArray){
		buttons.appendChild(makeButton(buttonLabel, buttonsArray[buttonLabel]))
	}
	
	return buttons
}

function makeBar(name){
	var bar = document.createElement('div')
	bar.className = 'bar ' + name + '_bar'
	bar.onmouseover = function(){ showButtons(bar) }
	bar.onmouseout = function(){ hideButtons(bar) }
	return bar;
}

function makeValueBar(value, text, id, makeButtons){
	var elementBar = makeBar(value.name)
	
	var element = document.createElement('div')
	element.className = 'bar_element ' + value.name
	element.onclick = function(){ value.edit(elementBar, value.update, id) }
	element.textContent = text
	elementBar.appendChild(element)
	
	elementBar.appendChild(makeButtons(elementBar))
	
	return elementBar
}

function makeSingleValueBar(value, text, id){
	return makeValueBar(value, text, id, function(elementBar){ return makeButtons({
		'edit'    : function(){ Value.edit(elementBar, value.update, id) },
		'delete'  : function(){ Value.delete(elementBar, id) }
	})})
}

function makeMultipleValueBar(value, text, id){
	return makeValueBar(value, text, id, function(elementBar){ return makeButtons({
		'edit'    : function(){ value.edit(elementBar, value.update, id) },
		'up'      : function(){ value.moveUp(elementBar, id) },
		'down'    : function(){ value.moveDown(elementBar, id) },
		'delete'  : function(){ value.delete(elementBar, id) }
	})})
}

//----------------------------------------------------------------------------
// node components
//----------------------------------------------------------------------------

function makeNest(name){
	var nest = document.createElement('div')
	nest.className = name
	return nest
}

function makeButtonBar(name, buttons){
	var buttonBar = document.createElement('div')
	buttonBar.className = 'button_bar ' + name + '_button_bar'
	
	var button
	for(var buttonIndex in buttons){
		button = buttons[buttonIndex]
		buttonBar.appendChild(makeButton2(
			button.name,
			button.label,
			button.action
		))
	}
	
	return buttonBar
}

function appendCategoryLabels(nodeContent, nodeId){
	nodeContent.appendChild(makeNest('category_labels'))
	nodeContent.appendChild(makeButtonBar(
		'category_label',
		[{
			name   : 'add_category_label',
			label  : 'add category label',
			action : function(){ CategoryLabel.add(nodeContent, nodeId) }
		}]
	))
}

function appendForms(nodeContent, nodeId){
	nodeContent.appendChild(makeNest('forms'))
	nodeContent.appendChild(makeButtonBar(
		'form',
		[{
			name   : 'add_form',
			label  : 'add form',
			action : function(){ Form.add(nodeContent, nodeId) }
		}]
	))
}

function appendContexts(nodeContent, nodeId){
	nodeContent.appendChild(makeNest('contexts'))
	nodeContent.appendChild(makeButtonBar(
		'context',
		[{
			name   : 'add_context',
			label  : 'add context',
			action : function(){ Context.add(nodeContent, nodeId) }
		}]
	))
}

function appendTranslations(nodeContent, nodeId){
	nodeContent.appendChild(makeNest('translations'))
	nodeContent.appendChild(makeButtonBar(
		'translation',
		[{
			name   : 'add_translation',
			label  : 'add translation',
			action : function(){ Translation.add(nodeContent, nodeId) }
		}]
	))
}

function appendPhrases(nodeContent, nodeId){
	nodeContent.appendChild(makeNest('phrases'))
	nodeContent.appendChild(makeButtonBar(
		'phrase',
		[{
			name   : 'add_phrase',
			label  : 'add phrase',
			action : function(){ Phrase.add(nodeContent, nodeId) }
		}]
	))
}

function appendSenses(nodeContent, nodeId){
	nodeContent.appendChild(makeNest('senses'))
	nodeContent.appendChild(makeButtonBar(
		'sense',
		[{
			name   : 'add_sense',
			label  : 'add sense',
			action : function(){ Sense.add(nodeContent, nodeId) }
		}]
	))
}

//----------------------------------------------------------------------------
// nodes
//----------------------------------------------------------------------------

function makeSenseLabelBar(nodeId, senseLabel, senseContent){
	var senseLabelBar = makeBar('sense_label')
	
	var senseLabelElement = document.createElement('div')
	senseLabelElement.className = 'bar_element sense_label'
	senseLabelElement.textContent = senseLabel
	senseLabelBar.appendChild(senseLabelElement)
	
	var buttons = makeButtons({
		'up'     : function(){ Sense.moveUp(senseContent, nodeId) },
		'down'   : function(){ Sense.moveDown(senseContent, nodeId) },
		'delete' : function(){ Sense.delete(senseContent, nodeId) }
	})
	senseLabelBar.appendChild(buttons)
	
	return senseLabelBar;
}

function makeSenseContent(nodeId){
	var senseContent = document.createElement('div')
	senseContent.className = 'content sense_content'
	
	appendCategoryLabels(senseContent, nodeId)
	appendForms(senseContent, nodeId)
	appendContexts(senseContent, nodeId)
	appendTranslations(senseContent, nodeId)
	appendPhrases(senseContent, nodeId)
	appendSenses(senseContent, nodeId)
	
	return senseContent;
}

function makeSenseContainer(nodeId, senseLabel){
	var senseContainer = document.createElement('div')
	senseContainer.className = 'sense_container'
	
	senseContainer.appendChild(makeSenseLabelBar(nodeId, senseLabel, senseContainer))
	senseContainer.appendChild(makeSenseContent(nodeId))
	
	return senseContainer;
}

//----------------------------------------------------------------------------

function makeHeadwordBar(headwordText, headwordId){
	return makeMultipleValueBar(Headword, headwordText, headwordId)
}

function makePronunciationBar(pronunciationText, pronunciationId){
	return makeMultipleValueBar(Pronunciation, pronunciationText, pronunciationId)
}

function makeCategoryLabelBar(text, parentNodeId){
	var categoryLabelBar = makeBar('category_label')
	
	var categoryLabel = document.createElement('div')
	categoryLabel.className = 'bar_element category_label'
	categoryLabel.onclick = function(){ CategoryLabel.edit(categoryLabelBar, parentNodeId) }
	categoryLabel.textContent = text
	categoryLabelBar.appendChild(categoryLabel)
	
	var buttons = makeButtons({
		'edit'   : function(){ CategoryLabel.edit(categoryLabelBar, parentNodeId) },
		'delete' : function(){ CategoryLabel.delete(categoryLabelBar, parentNodeId) }
	})
	categoryLabelBar.appendChild(buttons)
	
	return categoryLabelBar
}

function makeFormBar(formLabel, formText, formId){
	var formBar = makeBar('form')
	
	var label = document.createElement('div')
	label.className = 'bar_element form_label'
	label.onclick = function(){ Form.edit(formBar, formId) }
	label.textContent = formLabel
	formBar.appendChild(label)
	
	var space = document.createTextNode(' ')
	formBar.appendChild(space)
	
	var form = document.createElement('div')
	form.className = 'bar_element form'
	form.onclick = function(){ form.edit(formBar, formId) }
	form.textContent = formText
	formBar.appendChild(form)
	
	var buttons = makeButtons({
		'edit'   : function() { Form.edit(formBar, formId) },
		'up'     : function() { Form.moveUp(formBar, formId) },
		'down'   : function() { Form.moveDown(formBar, formId) },
		'delete' : function() { Form.delete(formBar, formId) },
	})
	formBar.appendChild(buttons)
	
	return formBar
}

function makeContextBar(contextText, contextId){
	return makeSingleValueBar(Context, contextText, contextId)
}

function makeTranslationBar(translationText, translationId){
	return makeMultipleValueBar(Translation, translationText, translationId)
}

