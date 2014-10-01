(function(){

    function isValid($, eventName){
        var valid = true;
        $.each(function(i, el){
            valid = valid && el.dispatchEvent(new Event(eventName));
        });
        return valid;
    }    

    function setHandler(els){

        els.each(function(i, el){
            if(!(el instanceof HTMLInputElement || el instanceof HTMLFormElement)) return;
            var $Input, $Form;

            function formHnd(e){
                var valid = $Form[0].checkValidity() && $Form[0].querySelectorAll("input[w-equal-to]").length == $Form[0].querySelectorAll("input[w-equal-to][w-is-equal]").length;   
                valid = isValid($Form, valid ? "validdata" : "invaliddata") && valid;        
                if(!valid && e.type == "submit") e.preventDefault();  
            }

            function inputHnd(e){
                if(e.type == "invalid") return e.target.dispatchEvent(new Event("invaliddata"));

                try {
	                var curretPos = e.target.selectionStart;
	                e.target.value = e.target.value;

	                curretPos = curretPos > e.target.value.length ? e.target.value.length : curretPos;
	                e.target.setSelectionRange(curretPos,curretPos);
	            } catch(e) {}
            
                var isEqual  = true;
                if(e.target.getAttribute("w-equal-to") != null)
                    if(e.target.value == document.querySelector(e.target.getAttribute("w-equal-to")).value) e.target.getAttribute("w-is-equal") = "";
                    else {
                        isEqual = false;
                        e.target.removeAttribute("w-is-equal");
                    }
            
                e.target.dispatchEvent(new Event(e.target.checkValidity() && isEqual ? "validdata" : "invaliddata"));
            };

            if(el instanceof HTMLFormElement){
                $Form  = $(el).on("submit keyup",formHnd);            
                $Input = $(el).find("input, textarea").on("input invalid",inputHnd);
            } else if(el instanceof HTMLInputElement || el instanceof HTMLTextAreaElement){
                $Input = $(el).on("input invalid",inputHnd);
                $Form  = $(el.form).on("submit keyup",formHnd);
            }
        });

    };

    $.fn.onvalid = function(handler){
        setHandler(this);
        this.on("validdata",handler);

        return this;
    };

    $.fn.oninvalid = function(handler){
        setHandler(this);
        this.on("invaliddata",handler);

        return this;
    };

})();

function setThankCount() {
    $.post("/api", { action: "thanksCount"}, function(data) {
        $("[gt-count]").html(data.count);
    });
}

$(function(){

    localStorage.clear();

	$("form input").oninvalid(function(e){
        $(e.target).parent().addClass("invalid");
    }).onvalid(function(e){
        $(e.target).parent().removeClass("invalid");
    });

    $("form").submit(function(){
    	if ($(this).find(".invalid").length) return false;
    });

    setThankCount();
    setInterval(setThankCount, 5000);
});