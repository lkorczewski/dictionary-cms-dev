function makeButton(name, action){
	var button = document.createElement('button')
	button.setAttribute('class', 'button ' + name)
	button.onclick = action
	button.textContent = localization.getText(name)
	return button
}

function makeButtons(buttonsArray){
	var buttons = document.createElement('div')
	buttons.setAttribute('class', 'buttons')
	
	for(var buttonElement in buttonsArray){
		buttons.appendChild(makeButton(buttonElement, buttonsArray[buttonElement]))
	}
	
	return buttons
}

function makeBar(name){
	var bar = document.createElement('div')
	bar.setAttribute('class', 'bar ' + name + '_bar')
	bar.onmouseover = function(){ showButtons(bar) }
	bar.onmouseout = function(){ hideButtons(bar) }
	return bar;
}

function makeValueBar(name, text, id){
	var elementBar = makeBar(name)
	
	var element = document.createElement('div')
	element.setAttribute('class', 'bar_element ' + name)
	element.onclick = function(){ editValue(name, elmentBar, id) }
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

function makeCategoryLabelBar(text, parentNodeId){
	var categoryLabelBar = makeBar('category_label')

	var categoryLabel = document.createElement('div')
	categoryLabel.setAttribute('class', 'bar_element category_label')
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

function makeContextBar(text, parentNodeId){
	var contextBar = makeBar('context')
	
	var context = document.createElement('div')
	context.setAttribute('class', 'bar_element context')
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

function makeHeadwordBar(headwordText, headwordId){
	return makeValueBar('headword', headwordText, headwordId)
}

function makePronunciationBar(pronunciationText, pronunciationId){
	return makeValueBar('pronunciation', pronunciationText, pronunciationId)
}

function makeFormBar(formLabel, formText, formId){
	var formBar = makeBar('form')
	
	var label = document.createElement('div')
	label.setAttribute('class', 'bar_element form_label')
	label.onclick = function(){ editForm(formBar, formId) }
	label.textContent = formLabel
	formBar.appendChild(label)
	
	var space = document.createTextNode(' ')
	formBar.appendChild(space)
	
	var form = document.createElement('div')
	form.setAttribute('class', 'bar_element form')
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

function makeTranslationBar(translationText, translationId){
	return makeValueBar('translation', translationText, translationId)
}
