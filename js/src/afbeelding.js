function onderschriftAchtergrond (onderschrift){

	var

	werkImg = onderschrift.parentNode.getElementsByTagName('img')[0],
	werkCanvas = document.createElement('canvas'),
	kontekst = werkCanvas.getContext('2d'),

	analyseerVanafX = werkImg.clientWidth - onderschrift.clientWidth,
	analyseerVanafY = werkImg.clientHeight - onderschrift.clientHeight,

	rauweData,
	data = [],
	t = [],
	lichtTotaal = 0,
	lichtTel = 0;

	werkCanvas.width = werkImg.clientWidth;
	werkCanvas.height = werkImg.clientHeight;
	werkCanvas.className = "werk-canvas";
	body.appendChild(werkCanvas);

	kontekst.drawImage(werkImg, 0, 0, werkImg.clientWidth, werkImg.clientHeight);
	rauweData = kontekst.getImageData(analyseerVanafX, analyseerVanafY, onderschrift.clientWidth, onderschrift.clientHeight).data;

	l = rauweData.length;

	for (i = 0; i < l; i++) {
		t.push(rauweData[i]);
		if (i && ((i+1)%4) === 0) {
			data.push(t);
			t = [];
		}
	}

	i = 0;
	while (i < onderschrift.clientHeight){

		j = 0;
		while (j < onderschrift.clientWidth) {

			lichtTotaal += (  data[i*j][0] + data[i*j][1] + data[i*j][2] + 3 ) * (  (data[i*j][3] + 1) / 255 );
			lichtTel++;

			j += 10;
		}
		i += 10;
	}

	var lichtCijfer = ((lichtTotaal / 3)  /  lichtTel) - 1;

	if (lichtCijfer < 122 ) {
		onderschrift.classList.add('donker');
	}

}