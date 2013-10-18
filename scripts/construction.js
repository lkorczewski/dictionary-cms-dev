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

function makeCategoryLabelBar(text, parentNodeId){
	var categoryLabelBar = document.createElement('div')
	categoryLabelBar.setAttribute('class', 'bar category_label_bar')
	categoryLabelBar.onmouseover = function(){ showButtons(categoryLabelBar) }
	categoryLabelBar.onmouseout = function(){ hideButtons(categoryLabelBar) }

	var categoryLabel = document.createElement('div')
	categoryLabel.setAttribute('class', 'bar_element category_label')
	categoryLabel.onclick = function(){ editCategoryLabel(categoryLabelBar, parentNodeId) }
	categoryLabel.textContent = text
	categoryLabelBar.appendChild(categoryLabel)
	
	var buttons = makeButtons({
		'delete' : function(){ deleteCategoryLabel(categoryLabelBar, parentNodeId) },
		'edit'   : function(){ editCategoryLabel(categoryLabelBar, parentNodeId) }
	})
	categoryLabelBar.appendChild(buttons)
	
	return categoryLabelBar
}

function makeContextBar(text, parentNodeId){
	var contextBar = document.createElement('div')
	contextBar.setAttribute('class', 'bar context_bar')
	contextBar.onmouseover = function(){ showButtons(contextBar) }
	contextBar.onmouseover = function(){ hideButtons(contextBar) }
	
	var context = document.createElement('div')
	context.setAttribute('class', 'bar_element context')
	context.onclick = function(){ editContext(contextBar, parentNodeId) }
	context.textContent = text
	contextBar.appendChild(context)
	
	var buttons = makeButtons({
		'delete' : function(){ deleteContext(categoryLabelBar, parentNodeId) },
		'edit'   : function(){ editContext(categoryLabelBar, parentNodeId) }
	})
	contextBar.appendChild(buttons)
	
	return contextBar
}

function makeTranslationBar(text, translationId){

	var translationBar = document.createElement('div')
	translationBar.setAttribute('class', 'bar translation_bar')
	translationBar.onmouseover = function(){ showButtons(translationBar) }
	translationBar.onmouseout = function(){ hideButtons(translationBar) }
	
	var translation = document.createElement('div')
	translation.setAttribute('class', 'bar_element translation')
	translation.onclick = function(){ editTranslation(translationBar, translationId) }
	translation.textContent = text
	translationBar.appendChild(translation)
	
	/*
	var buttons = makeButtons([
		{name: 'delete',  action: function(){ deleteTranslation(translationBar, translationId) }},
		{name: 'up',      action: function(){ moveTranslationUp(translationBar, translationId) }},
		{name: 'down',    action: function(){ moveTranslationDown(translationBar, translationId) }},
		{name: 'edit',    action: function(){ editTranslation(translationBar, translationId) }}
	])
	*/
	
	var buttons = makeButtons({
		'delete'  : function(){ deleteTranslation(translationBar, translationId) },
		'up'      : function(){ moveTranslationUp(translationBar, translationId) },
		'down'    : function(){ moveTranslationDown(translationBar, translationId) },
		'edit'    : function(){ editTranslation(translationBar, translationId) }
	})
	
	translationBar.appendChild(buttons)
	
	return translationBar
}
