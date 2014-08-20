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

function makeValueBar(name, text, id){
	var elementBar = makeBar(name)
	
	var element = document.createElement('div')
	element.className = 'bar_element ' + name
	element.onclick = function(){ editValue(name, elementBar, id) }
	element.textContent = text
	elementBar.appendChild(element)
	
	var buttons = makeButtons({
		'edit'    : function(){ editValue(name, elementBar, id) },
		'up'      : function(){ moveValueUp(name, elementBar, id) },
		'down'    : function(){ moveValueDown(name, elementBar, id) },
		'delete'  : function(){ deleteValue(name, elementBar, id) }
	})
	
	elementBar.appendChild(buttons)
	
	return elementBar
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
			action : function(){ addCategoryLabel(nodeContent, nodeId) }
		}]
	))
}

function appendForms(nodeContent, node){
	nodeContent.appendChild(makeNest('forms'))
	nodeContent.appendChild(makeButtonBar(
		'form',
		[{
			name   : 'add_form',
			label  : 'add form',
			action : function(){ addForms(nodeContent, nodeId) }
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
			action : function(){ addContext(nodeContent, nodeId) }
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
			action : function(){ addTranslation(nodeContent, nodeId) }
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
			action : function(){ addPhrases(nodeContent, nodeId) }
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
			action : function(){ addSense(nodeContent, nodeId) }
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
		'up'     : function(){ moveSenseUp(senseContent, nodeId) },
		'down'   : function(){ moveSenseDown(senseContent, nodeId) },
		'delete' : function(){ deleteSense(senseContent, nodeId) }
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
	return makeValueBar('headword', headwordText, headwordId)
}

function makePronunciationBar(pronunciationText, pronunciationId){
	return makeValueBar('pronunciation', pronunciationText, pronunciationId)
}

function makeCategoryLabelBar(text, parentNodeId){
	var categoryLabelBar = makeBar('category_label')

	var categoryLabel = document.createElement('div')
	categoryLabel.className = 'bar_element category_label'
	categoryLabel.onclick = function(){ editCategoryLabel(categoryLabelBar, parentNodeId) }
	categoryLabel.textContent = text
	categoryLabelBar.appendChild(categoryLabel)
	
	var buttons = makeButtons({
		'edit'   : function(){ editCategoryLabel(categoryLabelBar, parentNodeId) },
		'delete' : function(){ deleteCategoryLabel(categoryLabelBar, parentNodeId) }
	})
	categoryLabelBar.appendChild(buttons)
	
	return categoryLabelBar
}

function makeFormBar(formLabel, formText, formId){
	var formBar = makeBar('form')
	
	var label = document.createElement('div')
	label.className = 'bar_element form_label'
	label.onclick = function(){ editForm(formBar, formId) }
	label.textContent = formLabel
	formBar.appendChild(label)
	
	var space = document.createTextNode(' ')
	formBar.appendChild(space)
	
	var form = document.createElement('div')
	form.className = 'bar_element form'
	form.onclick = function(){ editForm(formBar, formId) }
	form.textContent = formText
	formBar.appendChild(form)
	
	var buttons = makeButtons({
		'edit'   : function() { editForm(formBar, formId)},
		'up'     : function() { moveFormUp(formBar, formId)},
		'down'   : function() { moveFormDown(formBar, formId)},
		'delete' : function() { deleteForm(formBar, formId)},
	})
	formBar.appendChild(buttons)
	
	return formBar
}

function makeContextBar(text, parentNodeId){
	var contextBar = makeBar('context')
	
	var context = document.createElement('div')
	context.className = 'bar_element context'
	context.onclick = function(){ editContext(contextBar, parentNodeId) }
	context.textContent = text
	contextBar.appendChild(context)
	
	var buttons = makeButtons({
		'edit'   : function(){ editContext(categoryLabelBar, parentNodeId) },
		'delete' : function(){ deleteContext(categoryLabelBar, parentNodeId) }

	})
	contextBar.appendChild(buttons)
	
	return contextBar
}

function makeTranslationBar(translationText, translationId){
	return makeValueBar('translation', translationText, translationId)
}
