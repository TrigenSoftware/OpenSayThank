/* inline-block margin fix. only for dev enviroment! */

function TGInlineBlockFix(){
	var element, elements = $("*");

	elements.each(function(){
		element = $(this);
		if(element.css("display") != "inline-block") return;
		element = element.parent();

		//console.log("before", element.html());
		element.html(element.html().trim().replace(/>[\n\r\s]+</g, "><"));
		//console.log("after", element.html());
	});

	element = null;
	elements = null;
};

//$(TGInlineBlockFix);