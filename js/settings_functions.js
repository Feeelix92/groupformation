$(document).ready(function() {
    
    // hide validation error alerts and show them if needed
    // if css attribute "display:none" and show on validation error, they will not displayed properly
    $(".errors p").hide();
    // TODO wenn JS und nonJS fehlerfrei funktioniert, die folgende Zeile einkommentieren
//    $("#non-js-content").hide();
    $("#js-content").show();
    
    //$("#fitem_id_szenario").hide();
//    $("#fitem_id_knowledge").hide();
//    $("#fitem_id_knowledgelines").hide();
    
//    var topicCounter = 3; //counts topics to make group numbers
//    
//    var preknwCounter = 3;
    
    var stringOfPreknowledge = "";

    var stringOfTopics = "";
    
    //$('#fitem_id_knowledge').insertBefore('.knowledgeWrapper');
    
    
//    $('#js_id_knowledge').keyup(function () {
//    	$('#prkRow0_span').html($('#js_id_knowledge').val());
//    
//    });
    
    //if knowledge gets checked
//    $('#id_js_knowledge').click(function(){
//    	if ($('#id_knowledge').prop('checked')){
//    		$('#id_knowledge').prop('checked',false);
//    	}else{
//    		$('#id_knowledge').prop('checked', true);
//    	}
//    	$(".js_knowledgeWrapper").toggle();
//    });
//    
//    //if topics gets checked
//    $('#id_js_topics').click(function(){
//    	if ($('#id_topics').prop('checked')){
//    		$('#id_topics').prop('checked',false);
//    	}else{
//    		alert("false Dialog");
//    		$('#id_topics').prop('checked', true);
//    	}
//    	$(".js_topicsWrapper").toggle();
//    });
//    
//    //if knowledge was checked last time
//    if ($('#id_knowledge').prop('checked')){
//    	$('#id_js_knowledge').prop('checked',true);
//    	$('#id_knowledge').prop('checked',true);
//    	$(".js_knowledgeWrapper").toggle();
//    }
//    
//    //if topics was checked last time
//    if ($('#id_topics').prop('checked')){
//    	$('#id_js_topics').prop('checked',true);
//    	$('#id_topics').prop('checked',true);
//    	$(".js_topicsWrapper").toggle();
//    }
//    
//    $('#js_evaluationmethod').val($('#id_evaluationmethod').val());
//    $('#group_size').val($('#id_maxmembers').val());
//    $('#numb_of_groups').val($('#id_maxgroups').val());
//    
//    // change of evaluationmethod in js changes non-js field
//    $('#js_evaluationmethod').change(function() {
//    	$('#id_evaluationmethod').val( this.value);
//    });
//    
//    // change of numb_of_groups in js changes non-js field
//    $('#numb_of_groups').keyup(function() {
//    	$('#id_maxgroups').val(parseInt(this.value));
//    });
//    $('#numb_of_groups').change(function() {
//    	$('#id_maxgroups').val(this.value);
//    });
//    
//    // change of group_size in js changes non-js field
//    $('#group_size').keyup(function() {
//    	$('#id_maxmembers').val(parseInt(this.value));
//    });
//    $('#group_size').change(function() {
//    	$('#id_maxmembers').val(this.value);
//    });

    
    
///////////////////////////////////////////////////////////////////////////////////////////////       
///////////////////////////////////////////////////////////////////////////////////////////////      
///////////////////////////////////////////////////////////////////////////////////////////////    
    
    
  // Load Settings  
    
  //if knowledge was checked before
    if ($('#id_knowledge').prop('checked')){
        $('#id_js_knowledge').prop('checked',true);
        $('#id_knowledge').prop('checked',true);
        $(".js_knowledgeWrapper").toggle();

        //get the value of Moodle nativ field #id_knowledgelines, parse it and create dynamic input fields
        var lines = $('textarea[name=knowledgelines]').val().split('\n');
        $wrapper = $('#prk').find('.multi_fields');
        $cat = 'prk';
        $.each(lines, function(){
            addInput($wrapper, $cat, this);
        });
        for( var i = 0, l = 3; i < l; i++){
            //remove the first 3 dynamic fields which been created by default
            removeInput($wrapper, $cat, i);
        }
    }
  
  //if topics was checked before
    if ($('#id_topics').prop('checked')){
        $('#id_js_topics').prop('checked',true);
        $('#id_topics').prop('checked',true);
        $(".js_topicsWrapper").toggle();

        //get the value of Moodle nativ field #id_topiclines, parse it and create dynamic input fields 
        var lines = $('textarea[name=topiclines]').val().split('\n');
        $wrapper = $('#tpc').find('.multi_fields');
        $cat = 'tpc';
        $.each(lines, function(){
            addInput($wrapper, $cat, this);
        });
        for( var i = 0, l = 3; i < l; i++){
        //remove the first 3 dynamic fields which been created by default
            removeInput($wrapper, $cat, i);
        }
        var activeEllID = 'group_size';
        var activeElVal = $('#id_maxmembers').val();
        var nonActiveElVal = getTopicsNumb();

        adjustGropOptions(activeEllID, activeElVal, nonActiveElVal);
        $("#group_opt_numb").attr('disabled', 'disabled');
    }else{
    	if($('input[name=groupoption]:checked').val() == '0'){
            var activeEllID = 'group_size';
            var activeElVal = $('#id_maxmembers').val();
            var nonActiveElVal = $('#id_maxgroups').val();

            adjustGropOptions(activeEllID, activeElVal, nonActiveElVal);
        }else{
            var activeEllID = 'numb_of_groups';
            var activeElVal = $('#id_maxgroups').val();
            var nonActiveElVal = $('#id_maxmembers').val();

            adjustGropOptions(activeEllID, activeElVal, nonActiveElVal);
        }
    }
    
    
    $('#id_general').focus();

    // End of Load Settings    
    
    
  
    //if knowledge gets checked
    $('#id_js_knowledge').click(function(){
    	if ($('#id_knowledge').prop('checked')){
    		$('#id_knowledge').prop('checked',false);
    		$('#id_knowledgelines').attr('disabled', 'disabled');
    	}else{
    		$('#id_knowledge').prop('checked', true);
    		$('#id_knowledgelines').removeAttr('disabled');
    	}
    	$(".js_knowledgeWrapper").toggle();
    });
    
    //if topics gets checked
    $('#id_js_topics').click(function(){
    	if ($('#id_topics').prop('checked')){
            //topics off
    		$('#id_topics').prop('checked',false);
    		$('#id_topiclines').attr('disabled', 'disabled');
            
            var activeElID = 'group_size';
            var activeElVal = 0;
            var nonActiveElVal = 0;
            adjustGropOptions(activeElID, activeElVal, nonActiveElVal);
            
            $("#group_opt_numb").removeAttr('disabled');
//            $('#numb_of_groups').val(0);
    	}else{
            //topics on
    		$('#id_topics').prop('checked', true);
    		$('#id_topiclines').removeAttr('disabled');
            
            var activeElID = 'group_size';
            var activeElVal = 0;
            var nonActiveElVal = getTopicsNumb();
            adjustGropOptions(activeElID, activeElVal, nonActiveElVal);
            
            $("#group_opt_numb").attr('disabled', 'disabled');
//            $("#group_opt_size").prop("checked", true);
//            $("#group_size").removeAttr('disabled');
//            $("#numb_of_groups").attr('disabled', 'disabled');
//            $('#numb_of_groups').val($('#tpc').find('.multi_field', '.multi_fields').length);
    	}
    	$(".js_topicsWrapper").toggle();
    });
    
    
    
    
    function addInput($wrapper, $cat, $value){
        $theID = parseInt($('.multi_field:last-child', $wrapper).attr('id').substr(8)) + 1
        $multifieldID = 'input' + $cat + $theID;

        // adds input field
        $('.multi_field:first-child', $wrapper).clone(true).attr('id',$multifieldID)
                                                            .appendTo($wrapper).find('input').val($value).focus();  
        addPreview($wrapper, $cat, $theID, $value);
    }
    
    function addPreview($wrapper, $cat, $theID, $value){
        $previewRowID = $cat + 'Row' +  $theID;
        
        if($cat == 'prk'){
            $('.knowlRow:first-child', '#preknowledges').clone(true).attr('id',$previewRowID)
                                                                .appendTo('#preknowledges').find('th').text($value);
        }
        if($cat == 'tpc'){
            $('.topicLi:first-child', '#previewTopics').clone(true).attr('id',$previewRowID)
                                                                .appendTo('#previewTopics').html('<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>' + $value);
        }
    }
    
    function removeInput($wrapper, $cat, $theID){
        if ($('.multi_field', $wrapper).length > 1){
            $previewRowID = $cat + 'Row' +  $theID;
            $multifieldID = 'input' + $cat + $theID;
            //remove Preview
            $('#' + $previewRowID).remove();
            //remove Input
            $('#' + $multifieldID).remove();
            
            //remove from Moodle native input field
            if($cat == 'prk'){
                synchronizePreknowledge();
            }
            if($cat == 'tpc'){
                synchronizeTopics();
                $('#numb_of_groups').val(getTopicsNumb());
//                synchronizeTopicsWithGroupnumber();
            }
        }
    }
    
    
    //dynamic inputs function
    $('.multi_field_wrapper').each(function dynamicInputs() {
        var $wrapper = $('.multi_fields', this);
        var $cat = $(this).parent().attr('id');
        
        //add new empty field on button
        $(".add_field", $(this)).click(function() {
            $value = '';
            addInput($wrapper, $cat, $value);
        });
        
        //removes field on button
        $('.multi_field .remove_field', $wrapper).click(function() {
            $theID = parseInt($(this).parent().attr('id').substr(8));
            
            removeInput($wrapper, $cat, $theID);    
        });
        
        
    // Create Preview and write to the native Moodle input
        $('.multi_field input:text', $wrapper).keyup(function() {
        	$previewRowID = ($cat + 'Row' + parseInt($(this).parent().attr('id').substr(8)));
                  if ($cat == 'prk'){
                      $('#' + $previewRowID).children('th').text($(this).val());
                      synchronizePreknowledge();
                  }
                  if ($cat == 'tpc'){
                      $('#' + $previewRowID).html('<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>' + $(this).val());
                      synchronizeTopics();
                      $('#numb_of_groups').val(getTopicsNumb());
                      selectOption('#id_maxgroups', getTopicsNumb());
                  }
              });
//      });
  });
    
        
  
    function synchronizePreknowledge(){
      stringOfPreknowledge = '';
      $('.js_preknowledgeInput').each(function(){
          if(!$(this).val() == ''){
            stringOfPreknowledge += $(this).val() + '\n';
          }
      });
      $('#id_knowledgelines').val(stringOfPreknowledge.slice(0, -1));
    }

    function synchronizeTopics(){
      stringOfTopics = '';
      $('.js_topicInput').each(function(){
          if(!$(this).val() == ''){
            stringOfTopics += $(this).val() + '\n';
          }
      });
      $('#id_topiclines').val(stringOfTopics.slice(0, -1));
    }
    
    

    //Groupoptions radiobutton listener
    $('input[name=group_opt]').change(function(e){
        var activeElVal = 0;
        var nonActiveElVal = 0;
        var activeElID = $(this).val();
        adjustGropOptions(activeElID, activeElVal, nonActiveElVal);
    });
    
    //Groupoptions values listener
    $('input[class=group_opt]').bind('keyup change', function(){
            var elID = $(this).attr('id');
            var elValue = $(this).val();
            if(elID == 'group_size'){
            	selectOption('#id_maxmembers', elValue);
            }else{
            	selectOption('#id_maxgroups', elValue);
            }
        });
    
    
    
    function adjustGropOptions($activeEllID, $activeElVal, $nonActiveElVal){
        if($activeEllID == 'group_size'){
        	$('#group_opt_size').prop('checked', true);
            $('#group_size').removeAttr('disabled').val($activeElVal);
            $('#numb_of_groups').attr('disabled', 'disabled').val($nonActiveElVal);
            
            //Moodle nativ fields
            $('#id_groupoption_0').prop('checked', true);
            $('#id_maxmembers').removeAttr('disabled').val($activeElVal);
            $('#id_maxgroups').attr('disabled', 'disabled');
            selectOption('#id_maxgroups', $nonActiveElVal);
            
        }else{
        	$('#group_opt_numb').prop('checked', true);
            $('#numb_of_groups').removeAttr('disabled').val($activeElVal);
            $('#group_size').attr('disabled', 'disabled').val($nonActiveElVal);
            
            //Moodle nativ fields
            $('#id_groupoption_1').prop('checked', true);
            $('#id_maxgroups').removeAttr('disabled').val($activeElVal);
            $('#id_maxmembers').attr('disabled', 'disabled').val($nonActiveElVal);
            selectOption('#id_maxmembers', $nonActiveElVal);
        }
    }

    
    function getTopicsNumb(){
        var topicsCounter = 0;
        $('.js_topicInput').each(function(){
          if(!$(this).val() == ''){
            topicsCounter++;
          }
      });
        return topicsCounter;
    }    
    
    
    
    function selectOption($selectID, $value){
    	$($selectID + ' option').prop('selected', false).filter('[value="' + $value + '"]').prop('selected', true);
    	// TODO @Eduard: Da maxgroups und maxmembers nun Textfelder sind, musst das hier anpassen
    }
    
    

    
    
  /////////////////////// Sticky buttons ///////////////////////////////////////////////////////////
    
    function UpdateBtnWrapp() {
        $(".persist-area").each(function() {

        var el             = $(this),
           offset         = el.offset(),
           scrollTop      = $(window).scrollTop(),
           floatingWrapper = $(".floatingWrapper", this)

        if ((scrollTop > offset.top) && (scrollTop < offset.top + el.height())) {
            floatingWrapper.css({
            "visibility": "visible"
            });
        } else {
            floatingWrapper.css({
            "visibility": "hidden"
            });      
        };
    });
}

// DOM Ready      
    $(function() {

        var clonedWrapper,
            theWidth = $('.col_100 h4').width();


       $(".persist-area").each(function() {
           clonedWrapper = $(".btn_wrap", this);       
           clonedWrapper
             .before(clonedWrapper.clone(true))
    //            .css('width', widthYeah)
    //             .css("width", clonedWrapper.width())
           .css('width', theWidth)
             .addClass("floatingWrapper");

       });

       $(window).scroll(UpdateBtnWrapp).trigger("scroll");
        //    alert(theWidth);

    });

///////////////////////////////////////////////////////////////////////////////////////
    
    
    //
    //  Datepicker + validation of dates on submit
    //
    
    
    
    $.datepicker.regional['de'] = {
        dateFormat: 'dd.mm.yy',
        monthNames: ['Januar','Februar','M\u00e4rz','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember'],
        dayNames: ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag','Samstag'],
        dayNamesMin: ['So', 'Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa']
    };
    
    $("#startDate, #endDate").datepicker(); // initialize Datepicker 
    if(true){                               //TODO Welche Sprache/Land wird verwendet, bei deutsch: true
        $.datepicker.setDefaults( $.datepicker.regional[ 'de' ] );
    }else $.datepicker.setDefaults( $.datepicker.regional[ '' ] ); //else default: engl.
    
    $("#startDate").datepicker("setDate", new Date() ); // Set default startDate as current date
//    $("#endDate").datepicker("setDate", new Date() );
//    var currentDate = $( "#startDate" ).datepicker( "getDate" );
    $('#endDate').datepicker("setDate", "+7");
    
    
    //set endDate + 7 Days from startDate by default
    $( '#startDate' ).change(function() {
            var date = $(this).datepicker('getDate');
            date.setDate(date.getDate() + 7); // Add 7 days
            $('#endDate').datepicker('setDate', date); // Set as default
      });
    
    
    
    // validating the docent form page 2
    $( "#docent_settings_2" ).submit(function( event ){
        var proceed = true;
        
        var startDate = $( "#startDate" ).datepicker( "getDate" );
        var endDate = $( "#endDate" ).datepicker( "getDate" );

        // check the date
        if (startDate >= endDate){
            proceed = false;
            
            $('#error_date').css({display: "inline-block"});
        }
        
        if(proceed){ //if form is valid submit form
            return true;
        }
        
        event.preventDefault();         // prevent submitting 
        
        scrollTo('.errors');            //scroll to the error messages
        
//        $('html, body').animate({       
//            scrollTop: $('.errors').offset().top
//        }, 80);
//        
    });
    
    // scroll to function
    function scrollTo($param) {
        $('html, body').animate({    
            scrollTop: $($param).offset().top
        }, 80);
    }

});
