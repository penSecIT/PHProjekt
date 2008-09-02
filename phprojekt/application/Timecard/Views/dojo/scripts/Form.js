dojo.provide("phpr.Timecard.Form");

dojo.declare("phpr.Timecard.Form",phpr.Default.Form, {
    // summary:
    //    This class is responsible for rendering the Form of a Timecard module
    // description:
    //    The Detail View of the timecard is rendered
    projecth:0,
    projectm:0,
    date:null,

	constructor: function(/*Object*/main, /*Object*/paramsIn){
         // summary:
        //    render the form on construction
        // description:
        //    this function receives the form data from the server and renders the corresponding form

        this.date = new Date()

        range =[{"id"
                :"1","name":"Invisible Root"},{"id":"2","name":"....Intern"},{"id":"3","name":"....Extern"},{"id":"5"
                ,"name":"....Default"}]
        var options=new Array();
		var j=0;
		for (j in range){
			options.push(range[j]);
			j++;
		}
        var params = {
            values:options,
            date: this.date,
            tcProjecthValue: 0,
            tcProjectmValue:0,
            timecardtimeRecordingForText: phpr.nls.timecardtimeRecordingFor,
            timecardDateText: phpr.nls.timecardDate,
            timecardWorkingTimesText: phpr.nls.timecardWorkingTimes,
            timecardStartText: phpr.nls.timecardStart,
            timecardEndText: phpr.nls.timecardEnd,
            timecardProjectTimesText: phpr.nls.timecardProjectTimes,
            timecardProjectText: phpr.nls.timecardProject,
            timecardNotesText: phpr.nls.timecardNotes,
            timecardTimesText: phpr.nls.timecardTimes,
            timecardHText: phpr.nls.timecardH,
            timecardMText: phpr.nls.timecardM,
            timecardSavedTimesText: phpr.nls.timecardSavedTimes,
            saveText: phpr.nls.save,
        };
        if (dojo.isObject(paramsIn)) {
            dojo.mixin(params, paramsIn);
        }
        params.dateForm = dojo.date.locale.format(params.date, {formatLength:'full',selector:'date', locale:this.lang});
        this.render(["phpr.Timecard.template", "form.html"], dojo.byId("tcBookings"),params);
        dojo.addOnLoad(dojo.hitch(this, "onLoaded"));
    },
    onLoaded: function(){
             dojo.connect(dijit.byId("date"), "onChange", function(date){
                 console.debug("date im onchange",date);
                 dojo.publish("Timecard.changeDate",[date]);
             });
             dojo.connect(dijit.byId("tcSubmitButton"),        "onClick", dojo.hitch(this, "submitTcBookingForm"));
             dojo.connect(dijit.byId("tcProjectSubmitButton"), "onClick", dojo.hitch(this, "submitTcProjectBookingForm"));
        },
   submitTcBookingForm: function(){
        // summary:
        //    This function is responsible for submitting the formdata
        // description:
        //    This function sends the form data as json data to the server and publishes
        //    and call the reload routine
		this.sendData = dijit.byId('tcBookingForm').getValues();
		phpr.send({
			url:       phpr.webpath + 'index.php/' + phpr.module + '/index/jsonSave/',
			content:   this.sendData,
            onSuccess: this.publish("reload",[{date: this.sendData.date}])
         });

    },
    submitTcProjectBookingForm: function(){
        // summary:
        //    This function is responsible for submitting the formdata
        // description:
        //    This function sends the form data as json data to the server and publishes
        //    and call the reload routine
		this.sendData = dijit.byId('tcBookingForm').getValues();
		phpr.send({
			url:       phpr.webpath + 'index.php/Timeproj/index/jsonSave/',
			content:   this.sendData,
            onSuccess: this.publish("reload",[{date: this.sendData.date}])
         });

    }
});