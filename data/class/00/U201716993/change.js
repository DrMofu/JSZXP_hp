addLoadEvent(imgbest);
window.onload=imgbest();
function imgbest()
{
	if(!document.getElementsByTagName||!document.getElementById) return false;
	if(!document.getElementById("mywives")) return false;
	var mywives=document.getElementById("mywives");
	var links=mywives.getElementsByTagName("a");

	for(var i=0;i<links.length;i++)
	{
			links[i].onclick=function()
			{
				picchange(this);
				return false;
			}
	}
}

function picchange(pic)
{
	if(!document.getElementById("placeholder")) return false;
	var source=pic.getAttribute("href");
	var placeholder=document.getElementById("placeholder");
	placeholder.setAttribute("src",source);
	if(document.getElementById("unique"))
	{
		var text=pic.getAttribute("title");
		var oritext=document.getElementById("unique");
		oritext.firstChild.nodeValue = text;
	}
	return true;
}

function countBodyChildren()
{
	var body_element=document.getElementsByTagName("body")[0];
	alert(body_element.childNodes.length);
}
window.onload=countBodyChildren();


function addLoadEvent(func)
{
	var oldload=window.onload;
	if(typeof window.onload !="function")
	{
		window.onload=func;
	}
	else
	{
		window.onload=function()
		{
			oldload();
			fuc();
		}
	}
}