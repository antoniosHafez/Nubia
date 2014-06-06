
    function autocomp(type)
    {
    
$(function() {
	//autocomplete
            switch(type) {
case 'medication':
   
     var medication=document.getElementById("medsearch").value;
    
        $("#medsearch").autocomplete({
          	source: "<?= $this->baseUrl() ?>/Autocomplete/get-Medication/medication/"+medication+"",
		minLength: 1
                
	});
        $('#medication_id').find('option').remove();
     
   
    $.getJSON("<?= $this->baseUrl() ?>/Autocomplete/get-Medication/medication/"+medication+"", function (data) {
              $.each(data, function (index, item) {
         $('#medication_id').append(
              $('<option></option>').val(index).html(item)
          );
    });
    
 });
 

    break;
case 'disease':
        var disease=document.getElementById("dissearch").value;
    
        $("#dissearch").autocomplete({
          	source: "<?= $this->baseUrl() ?>/Autocomplete/get-Disease/disease/"+disease+"",
		minLength: 1
                
	});
        $('#disease_id').find('option').remove();
     
   
    $.getJSON("<?= $this->baseUrl() ?>/Autocomplete/get-Disease/disease/"+disease+"", function (data) {
              $.each(data, function (index, item) {
         $('#disease_id').append(
              $('<option></option>').val(index).html(item)
          );
    });
    
 });
      
    break;
case 'radiation':
      var radiation=document.getElementById("radsearch").value;
    
        $("#radsearch").autocomplete({
          	source: "<?= $this->baseUrl() ?>/Autocomplete/get-Radiation/radiation/"+radiation+"",
		minLength: 1
                
	});
        $('#radiation_id').find('option').remove();
     
   
    $.getJSON("<?= $this->baseUrl() ?>/Autocomplete/get-Radiation/radiation/"+radiation+"", function (data) {
              $.each(data, function (index, item) {
         $('#radiation_id').append(
              $('<option></option>').val(index).html(item)
          );
    });
    
 });
      
    break
    case 'surgery':
      var surgery=document.getElementById("sursearch").value;
    
        $("#sursearch").autocomplete({
          	source: "<?= $this->baseUrl() ?>/Autocomplete/get-Surgery/surgery/"+surgery+"",
		minLength: 1
                
	});
        $('#surgery_id').find('option').remove();
     
   
    $.getJSON("<?= $this->baseUrl() ?>/Autocomplete/get-Surgery/surgery/"+surgery+"", function (data) {
              $.each(data, function (index, item) {
         $('#surgery_id').append(
              $('<option></option>').val(index).html(item)
          );
    });
    
 });
      
    break;
    case 'test':
      var test=document.getElementById("testsearch").value;
    
        $("#testsearch").autocomplete({
          	source: "<?= $this->baseUrl() ?>/Autocomplete/get-Test/test/"+test+"",
		minLength: 1
                
	});
        $('#test_id').find('option').remove();
     
   
    $.getJSON("<?= $this->baseUrl() ?>/Autocomplete/get-Test/test/"+test+"", function (data) {
              $.each(data, function (index, item) {
         $('#test_id').append(
              $('<option></option>').val(index).html(item)
          );
    });
    
 });
      
    break;
case  'vital':
     var vital=document.getElementById("vitsearch").value;
    
        $("#vitsearch").autocomplete({
          	source: "<?= $this->baseUrl() ?>/Autocomplete/get-Vital/vital/"+vital+"",
		minLength: 1
                
	});
        $('#vital_id').find('option').remove();
     
   
    $.getJSON("<?= $this->baseUrl() ?>/Autocomplete/get-Vital/vital/"+vital+"", function (data) {
              $.each(data, function (index, item) {
         $('#vital_id').append(
              $('<option></option>').val(index).html(item)
          );
    });
    
 });
    break;
    
}
        
      
       
        
});
}

    function HandleKey(type) {
        switch(type) {
case 'medication':
    k = document.getElementById('medsearch');

    break;
case 'disease':
    k = document.getElementById('dissearch');
    break;
case 'radiation':
    k = document.getElementById('radsearch');
    break
    case 'surgery':
    k = document.getElementById('sursearch');
    break;
case 'vital':
    k = document.getElementById('vitsearch');
    break;
    case 'test':
         k = document.getElementById('testsearch');
    break;
}
var keys = k.value;

if (keys.length > 0) {
FindKey(keys,type);
}
        
}

function FindKey(keys,type) {
  switch(type) {
case 'medication':
   opts = document.getElementById('medication_id');

    break;
case 'disease':
    opts = document.getElementById('disease_id');
    break;
case 'radiation':
    opts = document.getElementById('radiation_id');
    break
    case 'surgery':
    opts = document.getElementById('surgery_id');
    break;
case 'vital':
    opts = document.getElementById('vital_id');
    break;
    case 'test':
         opts = document.getElementById('test_id');
    break;
}

for(i = 0; i < opts.length; i++) {
     var array = [opts.item(i).text];
    if (array[0]==keys)
    {
    opts.selectedIndex = opts.item(i).index;
     }
if (opts.item(i).text.substr(0, keys.length) == keys) {
// Select the option opts.item(i)
document.selectedIndex = i;
return false;
}
}
}

function addtobox(type)
{
    switch(type)
    {
    case 'medication':
   opts = document.getElementById('medication_id');
   box = document.getElementById("medbox");
    key = document.getElementById('medsearch');
    break;
case 'disease':
    opts = document.getElementById('disease_id');
    box = document.getElementById("disbox");
    key = document.getElementById('dissearch');
    break;
case 'radiation':
    opts = document.getElementById('radiation_id');
    box = document.getElementById("radbox");
    key = document.getElementById('radsearch');
    break
    case 'surgery':
    opts = document.getElementById('surgery_id');
    box = document.getElementById("surbox");
    key = document.getElementById('sursearch');
    break;
case 'vital':
    opts = document.getElementById('vital_id');
    box = document.getElementById("vitbox");
    key = document.getElementById('vitsearch');
    break;
    case 'test':
         opts = document.getElementById('test_id');
         box = document.getElementById("testbox");
         key = document.getElementById('testsearch');
    break;
}
    if(!isfound(box,opts.item(opts.selectedIndex).value)){
        if(key.value == opts.item(opts.selectedIndex).text){
    box.options[box.options.length]=new Option(opts.item(opts.selectedIndex).text,opts.item(opts.selectedIndex).value,true,true);
        }
            }
    
}
function isfound(list,v)
{
 
        flag = false;
    for(i = 0; i < list.length; i++) {
     var array = [list.item(i).value];
    if (array[0]==v)
    {
            flag = true;
    }

}
return flag;
}

