
function zetStijl(nodeList, eigenschap, waarde){
	l = nodeList.length;
	for (i = 0; i < l; i++) {
		nodeList[i].style[eigenschap] = waarde;
	}
}

function actieInit(e, testKlasse){

	e.preventDefault();
	e.stopPropagation();

	return e.target.classList.contains(testKlasse) ? e.target : e.target.parentNode.classList.contains(testKlasse) ? e.target.parentNode : e.target.parentNode.parentNode;
}