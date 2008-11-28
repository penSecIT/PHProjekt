dojo.provide("phpr.Administration.Form");

dojo.declare("phpr.Administration.Form", phpr.Default.Form, {

    setUrl:function() {
        this._url = phpr.webpath+"index.php/" + phpr.module + "/index/jsonDetail/moduleName/" + phpr.submodule;
    },
    
    initData:function() {
    },
    
    setPermissions:function (data) {
        this._writePermissions  = true;
        this._deletePermissions = false;
        this._accessPermissions = true;
    },
    
    addBasicFields:function() {
    },
    
    addAccessTab:function(data) {
    },
    
    addModuleTabs:function(data) {
    },
    
    useCache:function() {
        return false;
    },
        
    submitForm: function() {
        // summary:
        //    This function is responsible for submitting the formdata
        // description:
        //    This function sends the form data as json data to the server
        //    and call the reload routine
        if (!this.prepareSubmission()) {
            return false;
        }

        phpr.send({
            url:       phpr.webpath + 'index.php/' + phpr.module + '/index/jsonSave/moduleName/' + phpr.submodule,
            content:   this.sendData,
            onSuccess: dojo.hitch(this, function(data) {
               new phpr.handleResponse('serverFeedback', data);
               if (!this.id) {
                   this.id = data['id'];
               }
               if (data.type =='success') {
                    this.publish("updateCacheData");
                    this.publish("reload");
                }
            })
        });
    }   
});
