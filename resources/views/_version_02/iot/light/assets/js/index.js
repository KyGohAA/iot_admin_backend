$(function() {
    "use strict";
    
	var mapData = {
			"US": 298,			
            "AU": 760,
            "CA": 870,
			"IN": 2000000,
			"GB": 120,
	};
	
	if( $('#world-map-markers2').length > 0 ){
		$('#world-map-markers2').vectorMap(
		{
			map: 'world_mill_en',
			backgroundColor: 'transparent',
			borderColor: '#fff',
			borderOpacity: 0.25,
			borderWidth: 0,
			color: '#e6e6e6',
			regionStyle : {
				initial : {
				  fill : '#ececec'
				}
			  },

			markerStyle: {
                initial: {
                            r: 5,
                            'fill': '#fff',
                            'fill-opacity':1,
                            'stroke': '#000',
                            'stroke-width' : 1,
                            'stroke-opacity': 0.4
                        },
                },
		   
            markers: [
                { latLng: [37.09,-95.71], name: 'America' },                
                { latLng: [-25.27, 133.77], name: 'Australia' },
                { latLng: [56.13,-106.34], name: 'Canada' },
                { latLng: [20.59,78.96], name: 'India' },
                { latLng: [55.37,-3.43], name: 'United Kingdom' },
            ],

			series: {
				regions: [{
					values: {
						"US": '#339af6',						
						"AU": '#02b5b2',
						"IN": '#f1a627',
                        "GB": '#445771',
                        "CA": '#68bb35',
					},
					attribute: 'fill'
				}]
			},
			hoverOpacity: null,
			normalizeFunction: 'linear',
			zoomOnScroll: false,
			scaleColors: ['#000000', '#000000'],
			selectedColor: '#000000',
			selectedRegions: [],
			enableZoom: false,
			hoverColor: '#fff',
		});
    }

    $('.sparkline-pie2').sparkline('html', {
        type: 'pie',
        offset: 90,
        width: '160px',
        height: '160px',
        sliceColors: ['#05b4d8', '#35cd3a', '#716aca']
    })

    
    $('.sparkline-pie').sparkline('html', {
        type: 'pie',
        offset: 90,
        width: '130px',
        height: '130px',
        sliceColors: ['#3C89DA', '#f3ad06', '#22af46', '#DE4847']
    })

    $('.sparkbar').sparkline('html', { type: 'bar' });


    // top products =================
    var dataStackedBar = {
        labels: ['Q1','Q2','Q3','Q4','Q5','Q6','Q7','Q8','Q9'],
        series: [
            [2350,3205,4520,2351,5632,3205,4520,1020,2905],
            [2541,2583,1592,2674,2323,1592,2674,1363,7650],
            [1212,5214,2325,4235,2519,1212,5214,2591,5361],
        ]
    };
    new Chartist.Bar('#university_income', dataStackedBar, {
        height: "250px",
        stackBars: true,
        axisX: {
            showGrid: false
        },
        axisY: {
            labelInterpolationFnc: function(value) {
                return (value / 1000) + 'k';
            }
        },
        plugins: [
            Chartist.plugins.tooltip({
                appendToBody: true
            }),
            Chartist.plugins.legend({
                legendNames: ['Fees', 'Donation', 'Other']
            })
        ]
    }).on('draw', function(data) {
            if (data.type === 'bar') {
                data.element.attr({
                    style: 'stroke-width: 25px'
                });
            }
    });

});