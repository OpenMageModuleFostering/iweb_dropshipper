    var productLinksController = Class.create();

    productLinksController.prototype = {
        initialize : function(fieldId, products, grid) {
            this.saveField = $(fieldId);
            this.saveFieldId = fieldId;
            this.products    = $H(products);
            this.grid        = grid;
            this.tabIndex    = 1000;
            this.grid.rowClickCallback = this.rowClick.bind(this);
            this.grid.initRowCallback = this.rowInit.bind(this);
            this.grid.checkboxCheckCallback = this.registerProduct.bind(this);
            this.grid.rows.each(this.eachRow.bind(this));
            this.saveField.value = this.serializeObject(this.products);
            this.grid.reloadParams = {'products[]':this.products.keys()};
            
        },
        eachRow : function(row) {
            this.rowInit(this.grid, row);
        },
        registerProduct : function(grid, element, checked) {
            
            if(checked){
                //For Position    
                if(element.inputElements) {
                    this.products.set(element.value, {});
                    for(var i = 0; i < element.inputElements.length; i++) {
                        element.inputElements[i].disabled = false;
                        this.products.get(element.value)[element.inputElements[i].name] = element.inputElements[i].value;
                    }
                }
                //For Comments    
                if(element.textareaElements) {
                    this.products.set(element.value, {});
                    for(var i = 0; i < element.textareaElements.length; i++) {
                        element.textareaElements[i].disabled = false;
                        this.products.get(element.value)[element.textareaElements[i].name] = element.textareaElements[i].value;
                    }
                }
            }
            else{
                //For Position    
                if(element.inputElements){
                    for(var i = 0; i < element.inputElements.length; i++) {
                        element.inputElements[i].disabled = true;
                    }
                }
                //For Comments
                if(element.textareaElements){
                    for(var i = 0; i < element.textareaElements.length; i++) {
                        element.textareaElements[i].disabled = true;
                    }
                }
                this.products.unset(element.value);
            }
            this.saveField.value = this.serializeObject(this.products);
            this.grid.reloadParams = {'products[]':this.products.keys()};
        },
        serializeObject : function(hash) {
            var clone = hash.clone();
            clone.each(function(pair) {
                clone.set(pair.key, encode_base64(Object.toQueryString(pair.value)));
            });
            return clone.toQueryString();
        },
        rowClick : function(grid, event) {
            var trElement = Event.findElement(event, 'tr');
            var isInput   = Event.element(event).tagName == 'INPUT';
            var isTextarea   = Event.element(event).tagName == 'TEXTAREA';
            
            if(trElement){
                var checkbox = Element.select(trElement, 'input');
                if(checkbox[0]){
                    var checked = isInput || isTextarea ? checkbox[0].checked : !checkbox[0].checked;
                            
                    this.grid.setCheckboxChecked(checkbox[0], checked);
                }
            }
        },
        inputChange : function(event) {
            var element = Event.element(event);
            if(element && element.checkboxElement && element.checkboxElement.checked){
                this.products.get(element.checkboxElement.value)[element.name] = element.value;
                this.saveField.value = this.serializeObject(this.products);
            }
        },
        textareaChange : function(event) {
            var element = Event.element(event);
            if(element && element.checkboxElement && element.checkboxElement.checked){
                this.products.get(element.checkboxElement.value)[element.name] = element.value;
                this.saveField.value = this.serializeObject(this.products);
            }
        },
        rowInit : function(grid, row) {
            var checkbox = $(row).select('.checkbox')[0];
            var inputs = $(row).select('.input-text');
             var textareas = $(row).select('.input-textarea'); 
            
            if(checkbox) {
                //For Position
                checkbox.inputElements = inputs;
                for(var i = 0; i < inputs.length; i++) {
                    inputs[i].checkboxElement = checkbox;
                    if(this.products.get(checkbox.value) && this.products.get(checkbox.value)[inputs[i].name]) {
                        inputs[i].value = this.products.get(checkbox.value)[inputs[i].name];
                    }
                    inputs[i].disabled = !checkbox.checked;
                    inputs[i].tabIndex = this.tabIndex++;
                    Event.observe(inputs[i],'keyup', this.inputChange.bind(this));
                    Event.observe(inputs[i],'change', this.inputChange.bind(this));
                }
                // For Comments
                checkbox.textareaElements = textareas;
                for(var i = 0; i < textareas.length; i++) {
                    textareas[i].checkboxElement = checkbox;
                    if(this.products.get(checkbox.value) && this.products.get(checkbox.value)[textareas[i].name]) {
                        textareas[i].value = this.products.get(checkbox.value)[textareas[i].name];
                    }
                    textareas[i].disabled = !checkbox.checked;
                    textareas[i].tabIndex = this.tabIndex++;
                    Event.observe(textareas[i],'keyup', this.textareaChange.bind(this));
                    Event.observe(textareas[i],'change', this.textareaChange.bind(this));
                }
            }
        }
    };
