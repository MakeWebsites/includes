
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>


<div id="wb-api" style="margin-bottom: 100px">

    <div v-if="!c2">
    <select class="form-control" v-model="c2">
            <option value="" disabled selected>Select country</option>
            <option v-for="country in countries" :key="country.c2" :value="country.c2" >{{ country.name }}</option>
        </select>
        <!--<wbc2 v-on:childc2="getc2"></wbc2>-->
    </div>
    <div v-if="c2">
        <h5>Data from {{cname}} <img v-bind:src="'<?php echo plugins_url( '', __FILE__ ) ?>' + '/inc/banderas/'+c2+'.png'" class="img-fluid"/></h5>
        
                <canvas id="co2"></canvas>
                <h6>Yearly CO<sub>2</sub> emissions (in Mt)</h6>
				
				<canvas id="pfs"></canvas>
				<h6>% of Energy production coming from oil, gas and coal sources </h6>

          <button class="btn btn-lg btn-filled" @click="reiniciar">Select another country</button>
    </div>
    
</div>
<script>
    new Vue({
          el: '#wb-api',
          data() {
            return {
                countries: [
            {name:' China', c2: 'cn'},
            {name:' United States', c2: 'us'},
            {name:' India', c2: 'in'},
            {name:' Russia', c2: 'ru'},
            {name:' Japan', c2: 'jp'},
            {name:' Brazil', c2: 'br'},
            {name:' Germany', c2: 'de'},
            {name:' Indonesia', c2: 'id'},
            {name:' Canada', c2: 'ca'},
            {name:' Mexico', c2: 'mx'},
            {name:' Iran', c2: 'ir'},
            {name:' South Korea', c2: 'kr'},
            {name:' Australia', c2: 'au'},
            {name:' Saudi Arabia', c2: 'sa'},
            {name:' United Kingdom', c2: 'gb'},
            {name:' South Africa', c2: 'za'},
            {name:' France', c2: 'fr'},
            {name:' Italy', c2: 'it'},
            {name:' Turkey', c2: 'tr'},
            {name:' Ukraine', c2: 'ua'},
            {name:' Thailand', c2: 'th'},
            {name:' Poland', c2: 'pl'},
            {name:' Argentina', c2: 'ar'},
            {name:' Pakistan', c2: 'pk'},
            {name:' Kazakhstan', c2: 'kz'}],
            c2: '',
            }
        },
        computed: {
            cname: function() {
             return   this.countries.find(x => x.c2 === this.c2).name;
            }
        },
        methods: {
            getc2 (value) {
            this.c2 = value.c2;
            this.cname = value.country;
            },
            reiniciar() {
                this.c2 = null;
                document.documentElement.scrollTop = 150;
            },
            gchart1: function() {
            var c2 = this.c2;
            var url = 'https://api.worldbank.org/v2/country/' + c2 + '/indicator/EN.ATM.CO2E.KT?format=json';
			var lchart = axios.get(url).then(function(response) {
                    var dat = response.data[1];
                    
                    var years = [];
					var co2 = [];
					
					var i = 0;
                    dat.forEach(function(entry) {
                        if (entry.value != null) {
							co2.push (entry.value/1000);
							//if (i % 3 == 0) 
								years.push (entry.date);
							//else years.push('');
							
						}
						i++;
                    });
					
                    co2 = co2.reverse();
					years = years.reverse();
					
					var ctxL = document.getElementById("co2").getContext('2d');
					var myLineChart = new Chart(ctxL, {
					type: 'line',
					data: {
					labels: years,
					datasets: [{
					label: "CO\u2082 emissions (Mt)",
					data: co2,
					backgroundColor: [
					'rgba(121, 85, 72, 0.3)',
					],
					borderColor: [
					'rgba(0, 10, 130, .7)',
					],
					borderWidth: 2
					}
					]
					},
					options: {
					responsive: true,
					scales: { yAxes: [{
								ticks: {
									beginAtZero: true,
									//stepSize: 20
								},
								scaleLabel: {
								display: true,
								labelString: "CO\u2082 emissions (Mt)"
							  }
							}],
					xAxes: [{
							ticks: {
							maxTicksLimit: 10,
							maxRotation: 0,
							minRotation: 0
							},
							gridLines: {
							display: false },
							scaleLabel: {
							display: true,
							labelString: "Year"
						  }
					}]
					}
					}
					});
					});
				},
			gchart2: function() {
            var c2 = this.c2;
            var url = 'https://api.worldbank.org/v2/country/' + c2 + '/indicator/EG.ELC.FOSL.ZS?format=json';
			var lchart = axios.get(url).then(function(response) {
                    var dat = response.data[1];
                    
                    var years = [];
					var pfs = [];
					
					var i = 0;
                    dat.forEach(function(entry) {
                        if (entry.value != null) {
							pfs.push (entry.value);
							//if (i % 3 == 0) 
								years.push (entry.date);
							//else years.push('');
							
						}
						i++;
                    });
					console.log(pfs);
					
                    pfs = pfs.reverse();
					years = years.reverse();
					
					var ctxL = document.getElementById("pfs").getContext('2d');
					var myLineChart = new Chart(ctxL, {
					type: 'line',
					data: {
					labels: years,
					datasets: [{
					label: "'% use of fossil fuels",
					data: pfs,
					backgroundColor: [
					'rgba(105, 0, 132, .2)',
					],
					borderColor: [
					'rgba(200, 99, 132, .7)',
					],
					borderWidth: 2
					}
					]
					},
					options: {
					responsive: true,
					scales: { yAxes: [{
								ticks: {
									beginAtZero: true,
									stepSize: 20
								},
								scaleLabel: {
								display: true,
								labelString: "'% use of fossil fuels"
							  }
							}],
					xAxes: [{
							ticks: {
							maxTicksLimit:10,
							maxRotation: 0,
							minRotation: 0
							},
							gridLines: {
							display: false },
							scaleLabel: {
							display: true,
							labelString: "Year"
						  }
					}]
					}
					}
					});
					});
				}
            },
            watch: {
                c2: function() {
                    this.gchart1();
					this.gchart2();
                }
            }
    });
</script>