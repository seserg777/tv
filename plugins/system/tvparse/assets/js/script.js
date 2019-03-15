function Strategy() {this.exec = function() {}};

const ids = [];
let infoBlock = '';

const saveMovieInfo = (movie_id, poster_quality, screeencaps_quality, lang) => {
	return new Promise((resolve, reject) => {		
		request('saveMovieInfo', JSON.stringify({'movie_id': movie_id, 'poster_quality': poster_quality, 'screeencaps_quality': screeencaps_quality, 'language': lang}))
		.done(function(r) {
			//console.log(r, r.data);
			if(typeof r.data != 'undefined'){
				//console.log(r.data);
				let infoBlock = jQuery('.Getmoviesfromlist');
				
				if (typeof r.data === 'string' || r.data instanceof String){
					infoBlock.append(`<div class="alert alert-error" data-movie-id="${r.data.id}" ><h4><b>${r.data}</b> Movie#${movie_id}</h4><p></p></div>`);
					resolve();
				} else {
					if(typeof r.data.exist != 'undefined'){
						infoBlock.append(`<div class="alert alert-info" data-movie-id="${r.data.id}" ><h4><b>Exist</b> Movie#${r.data.id}  ${r.data.title}</h4><p></p></div>`);
					} else {
						infoBlock.append(`<div class="alert alert-success" data-movie-id="${r.data.id}" ><h4>Movie#${r.data.id}  ${r.data.title}</h4><p></p></div>`);
					}
					resolve(r.data);
				}
				jQuery('html, body').stop().animate({scrollTop: infoBlock.find('[data-movie-id="'+r.data.id+'"]').offset().top - 120 });
			} else {
				resolve();
			}
		})
		.fail(function() {
			//reject('saveMovieInfo error!');
			resolve('saveMovieInfo error!');
		});
	});
}

const getAllSeasons = () => {
	return new Promise((resolve, reject) => {		
		request('getAllSeasons')
		.done(function(r) {
			//console.log(r);
			if(typeof r.data != 'undefined' && r.data.length){
				
				//let infoBlock = jQuery('#episodes-result');
				
				for(let i in r.data){
					//console.log(r.data[i]);
					infoBlock.append(`<div class="alert" data-film-id="${r.data[i].film}" data-season-number="${r.data[i].season_number}"><h4>Tvshow#${r.data[i].film} Season #${r.data[i].season_number} ${r.data[i].title}</h4><p></p></div>`);
					jQuery('html, body').stop().animate({scrollTop: infoBlock.find('[data-film-id="'+r.data[i].film+'"][data-season-number="'+r.data[i].season_number+'"]').offset().top - 120 });
				}
				
				resolve(r.data);
			}
		})
		.fail(function() {
			reject('getAllSeasons error!');
		});
	});
}

const saveTvSeasonEpisode = (tv_id, season_number, language) => {
	return new Promise((resolve, reject) => {
		if(season_number <= 0){
			console.log('Find zero season_number');
			resolve();
		} else {
			request('saveTvSeasonEpisode', JSON.stringify({'tv_id': tv_id, 'season_number': season_number, 'language': language}))
			.done(function(r) {
				//console.log(r);
				if(typeof r.data != 'undefined' && r.data != null && r.data[0]){
					
					//let infoBlock = jQuery('#episodes-result');
					
					for(let i in r.data){
						//console.log(r.data[i]);
						let row = infoBlock.find('[data-film-id="'+tv_id+'"][data-season-number="'+season_number+'"] p');
						row.append(`<small class="alert alert-success">${r.data[i]}</small>`);
						jQuery('html, body').stop().animate({scrollTop: infoBlock.find('[data-film-id="'+tv_id+'"][data-season-number="'+season_number+'"]').offset().top - 120 });
					}
					
					resolve(r.data);
				} else {
					resolve();
				}
			})
			.fail(function() {
				alert('saveTvSeasonEpisode fail #'+tv_id);
				reject('saveTvSeasonEpisode error!');
			});
		}
	});
}

const getAllTvList = (season_poster_quality, season_screeencaps_quality,screencaps_count, lang) => {
	return new Promise((resolve, reject) => {		
		request('getAllTvList', JSON.stringify({'season_poster_quality': season_poster_quality, 'season_screeencaps_quality': season_screeencaps_quality, 'screencaps_count': screencaps_count, 'lang': lang}))
		.done(function(r) {
			if(typeof r.data != 'undefined' && r.data.length){
				
				for(let i in r.data){
					infoBlock.append(`<div class="alert" data-film-id="${r.data[i].id}">${r.data[i].title} #${r.data[i].id}<p></p></div>`);
				}
				
				resolve(r.data);
			}
		})
		.fail(function() {reject('getAllTvList error!');});
	});
}

const getTvInfo = (tv_id, lang) => {
	return new Promise((resolve, reject) => {							
		request('getTvInfo', JSON.stringify({'tv_id': tv_id, 'lang': lang}))
		.done(function(r) {
			if(r.data){
				infoBlock.append(`<div class="alert" data-film-id="${r.data.id}">${r.data.original_name} #${r.data.id}<p></p></div>`);
				jQuery('html, body').animate({scrollTop: jQuery('[data-film-id="'+r.data.id+'"]').offset().top - 120 });
			} else {
				infoBlock.append(`<div class="alert alert-danger" data-film-id="${tv_id}">#${tv_id}<p></p></div>`);
			}
			resolve(r);
		})
		.fail(function() {
			alert('getTvInfo fail #'+tv_id);
			reject('getTvInfo error!');
		});
	});
}

const parseAllTvPages = (pages) => {
	for (let i = 1, p = Promise.resolve(); i <= pages; i++) {
		p = p.then(_ => new Promise(resolve =>
			getTvPageInfo(i)
			.then(function(result){resolve();}).catch(e => {alert(e)}
		)))
	}
}

const getTvPageInfo = (page) => {
	return new Promise((resolve, reject) => {							
		request('parseTvPage', JSON.stringify({'page': page}))
		.done(function(r) {			
			for (let i in r.data){
				ids.push(r.data[i]);
				infoBlock.append(`<div class="alert" data-film-id="${r.data[i].id}">${r.data[i].original_name} #${r.data[i].id}<p></p></div>`);
			}
			resolve(r);
		})
		.fail(function() {reject('parseTvPage error!');});
	});
}

const saveTvInfo = (id, lang) => {
	return new Promise((resolve, reject) => {
		request('saveTvInfo', JSON.stringify({'tv_id': id, 'lang': lang}))
		.done(function(r) {			
			if(typeof r.message != 'undefined'){
				infoBlock.find('[data-film-id="'+id+'"]').find('p').append('<small class="alert-error">saveTvInfo error: '+r.message+'</small>');
				resolve(true);
			}
			
			if(typeof r.data != 'undefined'){
				if(r.data == 'Film exist'){
					infoBlock.find('[data-film-id="'+id+'"]').find('p').append('<small class="alert-info">Film exist</small>');
					resolve(false);
				}
				if(r.data == true){
					infoBlock.find('[data-film-id="'+id+'"]').find('p').append('<small class="alert-success">Film was created</small>');
					resolve(true);
				}				
			}
		})
		.fail(function() {
			alert('saveTvInfo fail #'+id);
			reject('saveTvInfo error!');
		});
	})
}	

const saveTvExternal = (id) => {
	return new Promise((resolve, reject) => {
		request('saveTvExternal', JSON.stringify({'tv_id': id}))
		.done(function(r) {			
			if(typeof r.data != 'undefined'){
				if(r.data == true){
					infoBlock.find('[data-film-id="'+id+'"]').find('p').append('<small class="alert-success">Tv external was saved</small>');
				}	
			}
			resolve(r.data);
		})
		.fail(function() {
			alert('saveTvExternal fail #'+id);
			reject('saveTvExternal error!');
		});
	})
}	

const saveTvImages = (id, name, film_screeencaps_quality, screencaps_count) => {
	return new Promise((resolve, reject) => {
		request('saveTvImages', JSON.stringify({'tv_id': id, 'tv_name': encodeURIComponent(name), 'film_screeencaps_quality': film_screeencaps_quality, 'screencaps_count': screencaps_count}))
		.done(function(r) {
			if(typeof r.data != 'undefined'){
				if(r.data == true){
					infoBlock.find('[data-film-id="'+id+'"]').find('p').append('<small class="alert-success">Tv images was saved</small>');
				}	
				if(r.data == false){
					infoBlock.find('[data-film-id="'+id+'"]').find('p').append('<small class="alert-error">Tv images not saved</small>');
				}	
			}
			resolve(r.data);
		})
		.fail(function() {
			alert('saveTvImages fail #'+id);
			//reject('saveTvImages error!');
			resolve('saveTvImages error!');
		});
	})
}	

const saveTvMoreInfo = (id) => {
	return new Promise((resolve, reject) => {
		request('saveTvMoreInfo', JSON.stringify({'tv_id': id}))
		.done(function(r) {
			if(typeof r.data != 'undefined'){
				if(r.data == true){
					infoBlock.find('[data-film-id="'+id+'"]').find('p').append('<small class="alert-success">Tv more info was saved</small>');
				}	
				if(r.data == false){
					infoBlock.find('[data-film-id="'+id+'"]').find('p').append('<small class="alert-error">Tv more info not saved</small>');
				}	
			}
			resolve(r.data);
		})
		.fail(function() {
			//reject('saveTvMoreInfo error!');
			alert('saveTvMoreInfo fail #'+id);
			resolve('ERR');
		});
	})
}	

const saveTvSeasons = (id, screencaps_count, season_poster_quality, season_screeencaps_quality, lang) => {
	return new Promise((resolve, reject) => {
		request('saveTvSeasons', JSON.stringify({'tv_id': id, 'screencaps_count': screencaps_count, 'season_poster_quality': season_poster_quality, 'season_screeencaps_quality': season_screeencaps_quality, 'lang': lang}))
		.done(function(r) {			
			if(typeof r.data != 'undefined'){
				if(r.data == true){
					infoBlock.find('[data-film-id="'+id+'"]').find('p').append('<small class="alert-success">Tv season was saved</small>');
				}	else {
					if(r.data == false){
						infoBlock.find('[data-film-id="'+id+'"]').find('p').append('<small class="alert-error">Tv season not saved</small>');
					} else {
						infoBlock.find('[data-film-id="'+id+'"]').find('p').append('<small class="alert-info">'+r.data+'</small>');
					}
				}
				jQuery('html, body').animate({scrollTop: jQuery('[data-film-id="'+id+'"]').offset().top - 120 });
			}
			resolve(r.data);
		})
		.fail(function() {
			alert('saveTvSeasons fail #'+id);
			//reject('saveTvSeasons error!');
			resolve('saveTvSeasons error!');
		});
	})
}	

const saveTvNextEpisode = (id) => {
	return new Promise((resolve, reject) => {
		request('saveTvNextEpisode', JSON.stringify({'tv_id': id}))
		.done(function(r) {
			
			if(typeof r.message != 'undefined'){resolve(r.message);}
			
			if(typeof r.data != 'undefined'){
				if(r.data == true){
					infoBlock.find('[data-film-id="'+id+'"]').find('p').append('<small class="alert-success">Season next season was saved</small>');
				}	
				if(r.data == false){
					infoBlock.find('[data-film-id="'+id+'"]').find('p').append('<small class="alert-error">Season next season not saved</small>');
				}	
			}
			
			resolve(r.data);
		})
		.fail(function() {
			alert('saveTvNextEpisode fail #'+id);
			//reject('saveTvNextEpisode error!');
			resolve('saveTvNextEpisode error!');			
		});
	})
}

const processTv = (id, name, screencaps_count, season_poster_quality, film_screeencaps_quality, season_screeencaps_quality, lang) => {
	return new Promise((resolve, reject) => {
			
			let step1 = saveTvInfo(id, lang);
			
			step1.then(function(result){
				jQuery('html, body').animate({scrollTop: jQuery('[data-film-id="'+id+'"]').offset().top - 120 });
				
				if(result == true){
					let step2 = saveTvExternal(id);
					step2.then(function(result){
						jQuery('html, body').animate({scrollTop: jQuery('[data-film-id="'+id+'"]').offset().top - 120 });
						
						if(result == true){
							let step3 = saveTvImages(id, name, film_screeencaps_quality, screencaps_count);
							step3.then(function(result){
								jQuery('html, body').animate({scrollTop: jQuery('[data-film-id="'+id+'"]').offset().top - 120});

									let step4 = saveTvMoreInfo(id);
									step4.then(function(result){
										jQuery('html, body').animate({scrollTop: jQuery('[data-film-id="'+id+'"]').offset().top - 120});
										
											let step5 = saveTvSeasons(id, screencaps_count, season_poster_quality, season_screeencaps_quality, lang);
											step5.then(function(result){
												jQuery('html, body').animate({scrollTop: jQuery('[data-film-id="'+id+'"]').offset().top - 120});
													let step6 = saveTvNextEpisode(id);
													step6.then(function(result){
														if(result == true){
															jQuery('[data-film-id="'+id+'"]').removeAttr('class').addClass('alert alert-success');
															resolve();
														} else {
															if(result == 'Next episode info not found'){
																infoBlock.find('[data-film-id="'+id+'"]').find('p').append('<small class="alert-info">saveTvNextEpisode info: '+result+'</small>');
																resolve();
															} else {
																infoBlock.find('[data-film-id="'+id+'"]').find('p').append('<small class="alert-error">saveTvNextEpisode error: '+result+'</small>');
															}
														}
													});

											}).catch(e => {console.log(e);infoBlock.find('[data-film-id="'+id+'"]').find('p').append('<small class="alert-error">saveTvSeasons error</small>');});

									}).catch(e => {console.log(e);infoBlock.find('[data-film-id="'+id+'"]').find('p').append('<small class="alert-error">saveTvMoreInfo error</small>');});

							}).catch(e => {console.log(e);infoBlock.find('[data-film-id="'+id+'"]').find('p').append('<small class="alert-error">saveTvImages error</small>');});
						}
					}).catch(e => {console.log(e);infoBlock.find('[data-film-id="'+id+'"]').find('p').append('<small class="alert-error">saveTvExternal error</small>');});
				} else {
					resolve();
				}
			});
	});
}

function StrategyFirstStep() {
	this.exec = function(data) {
		
		infoBlock = jQuery('.Getlist');
		
		request('getGenres', JSON.stringify({'create':'1'}))
		.done(function(r) {
			if(typeof r.data.genres != 'undefined'){
				infoBlock.append(`<p class="alert alert-info">Найдено ${r.data.genres.length} жанров при первичном запросе.</p>`);
			}
			if(r.data.success == true){
				infoBlock.append(`<p class="alert alert-info">Найдено ${r.data.parse_tags} жанров при первичном запросе. Создано ${r.data.create_tags} новых жанров в базе.</p>`);
			}
			
			request('parseAllPagesCount', JSON.stringify(data))
			.done(function(r) {				
				if(typeof r.data.total_pages != 'undefined' && typeof r.data.total_results != 'undefined'){
					infoBlock.append(`<p class="alert alert-info">Найдено ${r.data.total_pages} страниц и ${r.data.total_results} результатов при первичном запросе.</p>`);
					
					if(r.data.total_pages){
						infoBlock.append('<p class="alert alert-info">Запрашиваем список всех фильмов</p>');					
						
						let promises = [];
						
						for (let i = 1; i <= r.data.total_pages; i++) {
							let promise = getTvPageInfo(i);
							promises.push(promise);
						}
						
						Promise.all(promises).then(function(result){							
							ids.reduce( (p, _, i) => 
								p.then(_ => new Promise(resolve =>
									processTv(ids[i].id, ids[i].name, data.screencaps_count, data.season_poster_quality, data.film_screeencaps_quality, data.season_screeencaps_quality, '').then(function(result){
										resolve();
									})
								))
							, Promise.resolve() );
						});					
					}
				}
			})
			.fail(function() {console.log('Ошибка Ajax запроса!');});
		})
        .fail(function() {console.log('Ошибка Ajax запроса!');});
	};
};
StrategyFirstStep.prototype = new Strategy();
StrategyFirstStep.prototype.constructor = StrategyFirstStep;

function StrategySecondStep(data) {
	this.exec = function(data) {
		request('secondStep', JSON.stringify(data))
		.done(function(r) {				
				if(typeof r.data.create_films != 'undefined' && typeof r.data.parse_fims != 'undefined'){
					infoBlock.append(`<p class="alert alert-info">Найдено ${r.data.parse_fims} фильмов из них ${r.data.create_films} сохранено в БД.</p>`);
				}
			})
			.fail(function() {console.log('Ошибка Ajax запроса!');});
	}
}
StrategySecondStep.prototype = new Strategy();
StrategySecondStep.prototype.constructor = StrategySecondStep;

function StrategyThirdStep(data) {
	this.exec = function(data) {
		jQuery('#result-1').append("<div class='alert alert-info'>Get list saved Tv's</div>");
		request('getTvs')
		.done(function(r) {				
			while(ids.length > 0) {
				ids.pop();
			}
			
			for (let i in r.data){
				ids.push(r.data[i]);
				jQuery('#result-1').append(`<div class="alert" data-film-id="${r.data[i].id}">${r.data[i].title} #${r.data[i].id}<p></p></div>`);
			}
			
			ids.reduce( (p, _, i) => 
				p.then(_ => new Promise(resolve =>
					getTvNextSeriesInfo(ids[i].id).then(function(result){
						resolve();
					})
				))
			, Promise.resolve() );
		})
		.fail(function() {console.log('Ошибка Ajax запроса!');});
	}
}
StrategyThirdStep.prototype = new Strategy();
StrategyThirdStep.prototype.constructor = StrategyThirdStep;

const getTvNextSeriesInfo = (id) => {
	return new Promise((resolve, reject) => {
		request('getTvNextSeriesInfo', JSON.stringify({'tv_id':id}))
		.done(function(r) {			
			jQuery('html, body').animate({scrollTop: jQuery('[data-film-id="'+id+'"]').offset().top - 120});
			if(r.data == true){
				jQuery('#result-1').find('[data-film-id="'+id+'"]').find('p').append('<small class="alert-success">Tv NextEpisode data was updated</small>');
				jQuery('[data-film-id="'+id+'"]').removeAttr('class').addClass('alert alert-success');
			} else {
				if(r.data == false){
					jQuery('#result-1').find('[data-film-id="'+id+'"]').find('p').append('<small class="alert-info">Tv has not NextEpisode data</small>');
				} else {
					jQuery('#result-1').find('[data-film-id="'+id+'"]').find('p').append('<small class="alert-danger">Tv NextEpisode update error: '+r.data+'</small>');
				}
			}	
			resolve();
		})
        .fail(function() {console.log('Ошибка Ajax запроса!');});
	});
}

function Context(strategy) {this.exec = function(message) {strategy.exec(message);}}

const request = (type, data) => {return jQuery.getJSON('index.php?option=com_ajax&plugin=tvparse&group=system&format=json&type='+type+'&data='+data);}

let X = XLSX;
let XW = {msg: 'xlsx',worker: '/plugins/system/tvparse/assets/js/xlsxworker.js'};

const xw = (data) => {
	return new Promise((resolve, reject) => {
		const worker = new Worker(XW.worker);
		worker.onmessage = function(e) {
			switch(e.data.t) {
				case 'ready': break;
				case 'e': 
					console.error(e.data.d); 
					reject(e.data.d);
					break;
				case XW.msg: 
					resolve(JSON.parse(e.data.d));
					break;
			}
		};
		worker.postMessage({d:data,b:'array'});
	});
};

const process_wb = (wb, format = 'obj') => {
	let output = '';
	
	let to_obj = function to_json(workbook) {
		let result = [];
		workbook.SheetNames.forEach(function(sheetName) {
			let roa = X.utils.sheet_to_json(workbook.Sheets[sheetName], {header:1});
			if(roa.length) result = roa;
		});
		return result;
	};
	
	let to_json = function to_json(workbook) {
		let result = {};
		workbook.SheetNames.forEach(function(sheetName) {
			let roa = X.utils.sheet_to_json(workbook.Sheets[sheetName], {header:1});
			if(roa.length) result[sheetName] = roa;
		});
		return JSON.stringify(result, 2, 2);
	};

	let to_csv = function to_csv(workbook) {
		let result = [];
		workbook.SheetNames.forEach(function(sheetName) {
			let csv = X.utils.sheet_to_csv(workbook.Sheets[sheetName]);
			if(csv.length){
				result.push("SHEET: " + sheetName);
				result.push("");
				result.push(csv);
			}
		});
		return result.join("\n");
	};

	return new Promise((resolve, reject) => {
		switch(format) {
			case "obj": 
				output = to_obj(wb); 
				resolve(output);
				break;
			case "json": 
				output = to_json(wb); 
				resolve(output);
				break;
			default: 
				//output = to_csv(wb);
				resolve(output);
		}
	});
}

const process_sheet = (b) => { 
	return new Promise((resolve, reject) => {
		if(b.length){
			for (let i in b){
				if(!b[i].length){
					delete b[i];
				} else {
					if(i > 0){
						for (let j in b[i]){
							let key = b[0][j];
							b[i][key] = b[i][j];
						}
					}
				}
			}
			
			resolve(b);
		} else {
			alert('Parse XLSX error!');
			reject('Parse XLSX error!');
		}	
	});		
}

const replaceSeasonInfo = (item) => {
	return new Promise((resolve, reject) => {
		jQuery.ajax({
			url : 'index.php?option=com_ajax&plugin=tvparse&group=system&format=json&type=replaceSeasonInfo',
			type: 'POST',
			data : {
				tv_id : item.themoviedbid,
				season_number: item['season#'],
				text : encodeURIComponent(item.text)
			}
		})
		.done(function(r) {
			r = JSON.parse(r);
			
			jQuery('html, body').animate({scrollTop: jQuery('[data-film-themoviedbid="'+item.themoviedbid+'"]').offset().top - 120});
			if(r.data == true){
				infoBlock.find('[data-film-themoviedbid="'+item.themoviedbid+'"]').find('p').append('<small class="alert-success">Season info replaced</small>');
				jQuery('[data-film-themoviedbid="'+item.themoviedbid+'"]').removeAttr('class').addClass('alert alert-success');
			} else {
				if(r.data == false){
					infoBlock.find('[data-film-themoviedbid="'+item.themoviedbid+'"]').find('p').append('<small class="alert-info">Season info not replaced</small>');
				} else {
					if(r.data = 'Film not exist'){
						infoBlock.find('[data-film-themoviedbid="'+item.themoviedbid+'"]').find('p').append('<small class="alert-info">Film not exist</small>');
					} else {
						infoBlock.find('[data-film-themoviedbid="'+item.themoviedbid+'"]').find('p').append('<small class="alert-danger">Replace Season info error: '+r.data+'</small>');
					}
				}
			}
			
			resolve();
		})
        .fail(function() {console.log('replaceSeasonInfo error!');});
	});
}

const replaceLinksInfo = (item) => {
	if(typeof item.links == 'undefined'){
		item.links = '';
	}
	return new Promise((resolve, reject) => {
		jQuery.ajax({
			url : 'index.php?option=com_ajax&plugin=tvparse&group=system&format=json&type=replaceLinksInfo',
			type: 'POST',
			data : {
				tv_id : item.id,
				season_number: item['season_number'],
				links : item.links
			}
		})
		.done(function(r) {
			r = JSON.parse(r);
			
			//console.log(r);
			
			jQuery('html, body').animate({scrollTop: jQuery('[data-film-themoviedbid="'+item.themoviedbid+'"][data-season-number="'+item['season_number']+'"]').offset().top - 120});
			if(r.data == true){
				infoBlock.find('[data-film-themoviedbid="'+item.themoviedbid+'"][data-season-number="'+item['season_number']+'"]').find('p').append('<small class="alert-success">Links info replaced</small>');
				jQuery('[data-film-themoviedbid="'+item.themoviedbid+'"][data-season-number="'+item['season_number']+'"]').removeAttr('class').addClass('alert alert-success');
			} else {
				if(r.data == false){
					infoBlock.find('[data-film-themoviedbid="'+item.themoviedbid+'"][data-season-number="'+item['season_number']+'"]').find('p').append('<small class="alert-info">Links info not replaced</small>');
				} else {
					if(r.data = 'Film not exist'){
						infoBlock.find('[data-film-themoviedbid="'+item.themoviedbid+'"][data-season-number="'+item['season_number']+'"]').find('p').append('<small class="alert-info">Film not exist</small>');
					} else {
						infoBlock.find('[data-film-themoviedbid="'+item.themoviedbid+'"][data-season-number="'+item['season_number']+'"]').find('p').append('<small class="alert-danger">Replace Links info error: '+r.data+'</small>');
					}
				}
			}
			
			resolve();
		})
        .fail(function() {console.log('replaceSeasonInfo error!');});
	});
}

const replaceMovieInfo = (item) => {
	return new Promise((resolve, reject) => {
		jQuery.ajax({
			url : 'index.php?option=com_ajax&plugin=tvparse&group=system&format=json&type=replaceMovieInfo',
			type: 'POST',
			data : {
				tv_id : item.id,
				text : item.description
			}
		})
		.done(function(r) {
			r = JSON.parse(r);
			
			//console.log(r);
			
			jQuery('html, body').animate({scrollTop: jQuery('[data-film-themoviedbid="'+item.id+'"]').offset().top - 120});
			if(r.data == true){
				infoBlock.find('[data-film-themoviedbid="'+item.id+'"]').find('p').append('<small class="alert-success">Movie info replaced</small>');
				jQuery('[data-film-themoviedbid="'+item.id+'"]').removeAttr('class').addClass('alert alert-success');
			} else {
				if(r.data == false){
					infoBlock.find('[data-film-themoviedbid="'+item.id+'"]').find('p').append('<small class="alert-info">Movie info not replaced</small>');
				} else {
					if(r.data = 'Film not exist'){
						infoBlock.find('[data-film-themoviedbid="'+item.id+'"]').find('p').append('<small class="alert-info">Movie not exist</small>');
					} else {
						infoBlock.find('[data-film-themoviedbid="'+item.id+'"]').find('p').append('<small class="alert-danger">Replace Movie info error: '+r.data+'</small>');
					}
				}
			}
			
			resolve();
		})
        .fail(function() {console.log('replaceSeasonInfo error!');});
	});
}

const replaceTvInfo = (item) => {
	return new Promise((resolve, reject) => {
		jQuery.ajax({
			url : 'index.php?option=com_ajax&plugin=tvparse&group=system&format=json&type=replaceTvInfo',
			type: 'POST',
			data : {
				tv_id : item.themoviedbid,
				text : encodeURIComponent(item.text)
			}
		})
		.done(function(r) {
			r = JSON.parse(r);
			
			jQuery('html, body').animate({scrollTop: jQuery('[data-film-themoviedbid="'+item.themoviedbid+'"]').offset().top - 120});
			if(r.data == true){
				infoBlock.find('[data-film-themoviedbid="'+item.themoviedbid+'"]').find('p').append('<small class="alert-success">Tv info replaced</small>');
				jQuery('[data-film-themoviedbid="'+item.themoviedbid+'"]').removeAttr('class').addClass('alert alert-success');
			} else {
				if(r.data == false){
					infoBlock.find('[data-film-themoviedbid="'+item.themoviedbid+'"]').find('p').append('<small class="alert-info">Tv info not replaced</small>');
				} else {
					if(r.data = 'Film not exist'){
						infoBlock.find('[data-film-themoviedbid="'+item.themoviedbid+'"]').find('p').append('<small class="alert-info">Film not exist</small>');
					} else {
						infoBlock.find('[data-film-themoviedbid="'+item.themoviedbid+'"]').find('p').append('<small class="alert-danger">Replace Tv info error: '+r.data+'</small>');
					}
				}
			}
			
			resolve();
		})
        .fail(function() {console.log('replaceSeasonInfo error!');});
	});
}

const startAction = (type) => {	
	switch (type) {
		case 'getList':
			const FirstStep = new Context( new StrategyFirstStep() );
			FirstStep.exec({
				'cp_year': jQuery('#cp_year').val(),
				'cp_vote': jQuery('#cp_vote').val(),
				'cp_lang': jQuery('#cp_lang').val(),
				'cp_lang_rec': jQuery('#cp_lang_rec').val(),
				'screencaps_count': jQuery('#screencaps_count').val(),
				'season_poster_quality': jQuery('#season_poster_quality_getlist').val(),
				'film_screeencaps_quality': jQuery('#film_screeencaps_quality_getlist').val(),
				'season_screeencaps_quality': jQuery('#season_screeencaps_quality_getlist').val()
			});
			break;
			
		case 'getNextSeason':
			const ThirdStep = new Context( new StrategyThirdStep() );
			ThirdStep.exec({});
			break;
			
		case 'importTvs':
			//console.log('importTvs');
			
			infoBlock = jQuery('#result-2');
			
			infoBlock.html('');
			infoBlock.append("<div class='alert alert-info'>Start import Tv s</div>");
			
			let importTvsFile = jQuery('#jform_importtvs').prop('files')[0];
			let importTvsFilename = importTvsFile.name;
			let importTvsReader = new FileReader();
			
			importTvsReader.onload = async function(e) {
				let data = e.target.result;
				data = new Uint8Array(data);
				let a = await xw(data);
				let b = await process_wb(a);
				let c = await process_sheet(b);
				c.splice(0, 1);
				
				for (let i in c){
					infoBlock.append(`<div class="alert" data-film-themoviedbid="${c[i].themoviedbid}">${c[i].Name} #${c[i].themoviedbid}<p></p></div>`);
				}
				
				c.reduce( (p, _, i) => 
					p.then(_ => new Promise(resolve =>
						replaceTvInfo(c[i]).then(function(result){
							resolve();
						})
					))
				, Promise.resolve() );
			}
			
			importTvsReader.readAsArrayBuffer(importTvsFile);
			
			break;
			
		case 'importSeasons':
			//console.log('importSeasons');
			
			infoBlock = jQuery('#result-2');
			
			infoBlock.html('');
			infoBlock.append("<div class='alert alert-info'>Start import Seasons</div>");
			
			let importSeasonsFile = jQuery('#jform_importseasons').prop('files')[0];
			let importSeasonsFilename = importSeasonsFile.name;
			let importSeasonsReader = new FileReader();
			
			importSeasonsReader.onload = async function(e) {
				let data = e.target.result;
				data = new Uint8Array(data);
				let a = await xw(data);
				let b = await process_wb(a);
				let c = await process_sheet(b);
				c.splice(0, 1);
				
				for (let i in c){
					infoBlock.append(`<div class="alert" data-film-themoviedbid="${c[i].themoviedbid}">${c[i].Name} #${c[i].themoviedbid}<p></p></div>`);
				}
				
				c.reduce( (p, _, i) => 
					p.then(_ => new Promise(resolve =>
						replaceSeasonInfo(c[i]).then(function(result){
							resolve();
						})
					))
				, Promise.resolve() );					
			}
			
			importSeasonsReader.readAsArrayBuffer(importSeasonsFile);
			
			break;
		
		case 'importLinks':
			//console.log('importLinks');
			
			infoBlock = jQuery('#result-2');
			
			infoBlock.html('');
			infoBlock.append("<div class='alert alert-info'>Start import Links</div>");
			
			let importLinksFile = jQuery('#jform_importlinks').prop('files')[0];
			let importLinksFilename = importLinksFile.name;
			let importLinksReader = new FileReader();
			
			importLinksReader.onload = async function(e) {
				let data = e.target.result;
				data = new Uint8Array(data);
				let a = await xw(data);
				let b = await process_wb(a);
				let c = await process_sheet(b);
				c.splice(0, 1);
				
				//console.log(c);
				
				for (let i in c){
					infoBlock.append(`<div class="alert" data-film-themoviedbid="${c[i].themoviedbid}" data-season-number="${c[i].season_number}">${c[i].title} #${c[i].themoviedbid} Season number:${c[i].season_number}<p></p></div>`);
				}
				
				c.reduce( (p, _, i) => 
					p.then(_ => new Promise(resolve =>
						replaceLinksInfo(c[i]).then(function(result){
							resolve();
						})
					))
				, Promise.resolve() );
			}
			
			importLinksReader.readAsArrayBuffer(importLinksFile);
			
			break;
			
		case 'importMovies':
			//console.log('importMovies');
			
			infoBlock = jQuery('#result-2');
			
			infoBlock.html('');
			infoBlock.append("<div class='alert alert-info'>Start import movies</div>");
			
			let importMoviesFile = jQuery('#jform_importmovies').prop('files')[0];
			let importMoviesFilename = importMoviesFile.name;
			let importMoviesReader = new FileReader();
			
			importMoviesReader.onload = async function(e) {
				let data = e.target.result;
				data = new Uint8Array(data);
				let a = await xw(data);
				let b = await process_wb(a);
				let c = await process_sheet(b);
				c.splice(0, 1);
				
				//console.log(c);
				
				for (let i in c){
					infoBlock.append(`<div class="alert" data-film-themoviedbid="${c[i].id}">${c[i].title} #${c[i].id}<p></p></div>`);
				}
				
				c.reduce( (p, _, i) => 
					p.then(_ => new Promise(resolve =>
						replaceMovieInfo(c[i]).then(function(result){
							resolve();
						})
					))
				, Promise.resolve() );
			}
			
			importMoviesReader.readAsArrayBuffer(importMoviesFile);
			
			break;			
			
		case 'getListFromList':
			//console.log('getListFromList');
			
			infoBlock = jQuery('.Getlistfromlist');
			
			let tv_ids = jQuery('#ids').val().split('\n');
			let season_poster_quality = jQuery('#season_poster_quality_getlistfromlist').val();
			let film_screeencaps_quality = jQuery('#film_screeencaps_quality_getlistfromlist').val();
			let season_screeencaps_quality = jQuery('#season_screeencaps_quality_getlistfromlist').val();
			let screencaps_count = jQuery('#screencaps_count_list').val();
			let lang = jQuery('#lang_getlistfromlist').val();
			for (let i in tv_ids){
				if(tv_ids[i] == ''){
					tv_ids.splice(i, 1);
				}
			}
			
			if(tv_ids.length){
				infoBlock.append(`<p class="alert alert-info">Найдено ${tv_ids.length} IDs в списке.</p>`);
				infoBlock.append(`<p class="alert alert-info">Начинаем обработку.</p>`);
				
				let promises = [];
				for (let i in tv_ids) {
					let promise = getTvInfo(tv_ids[i], lang);
					promises.push(promise);
				}
				
				Promise.all(promises).then(function(result){
					result.reduce( (p, _, i) => 
						p.then(_ => new Promise(resolve => {
							if(result[i].data){
								processTv(result[i].data.id, result[i].data.original_name, screencaps_count, season_poster_quality, film_screeencaps_quality, season_screeencaps_quality, lang).then(function(result){
									resolve()
								})
							} else {
								resolve()
							}
						}))
					, Promise.resolve() );
				});

			}
			
			break;
			
			case 'getNewSeasonsFromList':
				//console.log('getNewSeasonsFromList');
				
				infoBlock = jQuery('.getnewseasons-info');
				
				let idsl = jQuery('#idsl').val().split('\n');
				let season_poster_quality_gnsl = jQuery('#season_poster_quality_gns').val();
				let season_screeencaps_quality_gnsl = jQuery('#season_screeencaps_quality_gns').val();
				let screencaps_count_gnsl = jQuery('#screencaps_count_gns').val();
				let lang_gnsl = jQuery('#lang_gns').val();
				
				//console.log(idsl);
				
				if(idsl.length){
					infoBlock.append(`<p class="alert alert-info">Найдено ${idsl.length} IDs в списке.</p>`);
					infoBlock.append(`<p class="alert alert-info">Начинаем обработку.</p>`);
				}
				
				for (let i in idsl){
					if(idsl[i] == ''){
						idsl.splice(i, 1);
					}
					infoBlock.append(`<div class="alert" data-film-id="${idsl[i]}">#${idsl[i]}<p></p></div>`);
				}				
				
				idsl.reduce( (p, _, i) => 
					p.then(_ => new Promise(resolve =>
						saveTvSeasons(idsl[i], screencaps_count_gnsl, season_poster_quality_gnsl, season_screeencaps_quality_gnsl, lang_gnsl).then(function(result){
							resolve();
						})
					))
				, Promise.resolve() );
				
				/*const list = getAllTvList(season_poster_quality_gns, season_screeencaps_quality_gns,screencaps_count_gns, lang_gns);
				list.then(function(result){					
					result.reduce( (p, _, i) => 
						p.then(_ => new Promise(resolve =>
							saveTvSeasons(result[i].id, screencaps_count_gns, season_poster_quality_gns, season_screeencaps_quality_gns, lang_gns).then(function(result){
								resolve();
							})
						))
					, Promise.resolve() );
				});*/
				
				break;
				
			case 'getNewAllSeasons':
				//console.log('getNewAllSeasons');
				
				infoBlock = jQuery('.getnewseasons-info');
				
				let season_poster_quality_gns = jQuery('#season_poster_quality_gns').val();
				let season_screeencaps_quality_gns = jQuery('#season_screeencaps_quality_gns').val();
				let screencaps_count_gns = jQuery('#screencaps_count_gns').val();
				let lang_gns = jQuery('#lang_gns').val();
				
				const list = getAllTvList(season_poster_quality_gns, season_screeencaps_quality_gns,screencaps_count_gns, lang_gns);
				list.then(function(result){					
					result.reduce( (p, _, i) => 
						p.then(_ => new Promise(resolve =>
							saveTvSeasons(result[i].id, screencaps_count_gns, season_poster_quality_gns, season_screeencaps_quality_gns, lang_gns).then(function(result){
								resolve();
							})
						))
					, Promise.resolve() );
				});
				
				break;
				
			case 'getAllSeasonsEpisodes':
				//console.log('getAllSeasonsEpisodes');
				
				let lang_episodes = jQuery('#lang_episodes').val();
				
				infoBlock = jQuery('#episodes-result');
				
				const episodes_list = getAllSeasons();
				episodes_list.then(function(result){
					//console.log(result);				
					result.reduce( (p, _, i) => 
						p.then(_ => new Promise(resolve =>
							saveTvSeasonEpisode(result[i].film, result[i].season_number, lang_episodes).then(function(result){
								resolve();
							})
						))
					, Promise.resolve() );
				});
				
				break;
				
			case 'getMoviesFromList':
				//console.log('getMoviesFromList');
			
				infoBlock = jQuery('.Getmoviesfromlist');
			
				let getmoviesfromlist_movies_ids = jQuery('#getmoviesfromlist_ids').val().split('\n');
				let getmoviesfromlist_poster_quality = jQuery('#getmoviesfromlist_poster_quality').val();
				let getmoviesfromlist_screeencaps_quality = jQuery('#getmoviesfromlist_screeencaps_quality').val();
				let getmoviesfromlist_lang = jQuery('#getmoviesfromlist_lang').val();
				for (let i in getmoviesfromlist_movies_ids){
					if(getmoviesfromlist_movies_ids[i] == ''){
						getmoviesfromlist_movies_ids.splice(i, 1);
					}
				}
			
				if(getmoviesfromlist_movies_ids.length){
					infoBlock.append(`<p class="alert alert-info">Найдено ${getmoviesfromlist_movies_ids.length} IDs в списке.</p>`);
					infoBlock.append(`<p class="alert alert-info">Начинаем обработку.</p>`);

					getmoviesfromlist_movies_ids.reduce( (p, _, i) => 
						p.then(_ => new Promise(resolve => {
							if(getmoviesfromlist_movies_ids[i]){
								saveMovieInfo(
									getmoviesfromlist_movies_ids[i],
									getmoviesfromlist_poster_quality,
									getmoviesfromlist_screeencaps_quality,
									getmoviesfromlist_lang
								).then(function(result){
									resolve()
								})
							} else {
								resolve()
							}
						}))
					, Promise.resolve() );
					
					console.log('Finish!');
				}
				
				break;
	}
}