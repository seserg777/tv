importScripts('shim.js');
importScripts('xlsx.full.min.js');
postMessage({t:"ready"});

onmessage = function (evt) {
	let v;
	try {
		v = XLSX.read(evt.data.d, {type: evt.data.b});
		postMessage({t:"xlsx", d:JSON.stringify(v)});
		//console.log(v);
	} catch(e) { 
		postMessage({t:"e",d:e.stack||e}); 
	}
};