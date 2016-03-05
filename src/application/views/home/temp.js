function create(){
	var list = document.getElementsByTagName("created");
	cln = list[0].cloneNode(true);
	var list1 = document.getElementsByTagName("create");
	list1[0].appendChild(cln);
	var a=cln.childNodes[3];
	var b=a.childNodes[1];
	b.innerHTML="Foot Ball";
}
