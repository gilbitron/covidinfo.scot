<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Coronavirus (COVID-19) statistics for Scotland</title>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="stylesheet" href="/style.css">
</head>
<body class="bg-gray-100 bg-gradient-to-b from-blue-50 via-gray-100 to-gray-100">
    <div x-data="component()" x-init="init()" class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <header class="mb-16 text-center">
            <h1 id="page_title" class="text-3xl tracking-tight leading-10 font-extrabold text-gray-900 mb-10 sm:text-4xl md:text-5xl">Coronavirus (COVID-19) statistics for Scotland</h1>
            <div class="max-w-lg mx-auto md:flex">
                <div class="max-w-xs mx-auto mb-6 md:max-w-none md:mx-4 md:mb-0">
                    <select id="select_area" class="form-select block w-full pl-3 pr-10 py-2 text-base leading-6 border-gray-300 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5">
                        <option value="">Scotland</option>
                        <option value="Aberdeen City">Aberdeen City</option>
                        <option value="Aberdeenshire">Aberdeenshire</option>
                        <option value="Angus">Angus</option>
                        <option value="Argyll and Bute">Argyll and Bute</option>
                        <option value="Clackmannanshire">Clackmannanshire</option>
                        <option value="Dumfries and Galloway">Dumfries and Galloway</option>
                        <option value="Dundee City">Dundee City</option>
                        <option value="East Ayrshire">East Ayrshire</option>
                        <option value="East Dunbartonshire">East Dunbartonshire</option>
                        <option value="East Lothian">East Lothian</option>
                        <option value="East Renfrewshire">East Renfrewshire</option>
                        <option value="City of Edinburgh">Edinburgh</option>
                        <option value="Falkirk">Falkirk</option>
                        <option value="Fife">Fife</option>
                        <option value="Glasgow City">Glasgow City</option>
                        <option value="Highland">Highland</option>
                        <option value="Inverclyde">Inverclyde</option>
                        <option value="Midlothian">Midlothian</option>
                        <option value="Moray">Moray</option>
                        <option value="Na h-Eileanan an Iar">Na h-Eileanan an Iar</option>
                        <option value="North Ayrshire">North Ayrshire</option>
                        <option value="North Lanarkshire">North Lanarkshire</option>
                        <option value="Orkney Islands">Orkney Islands</option>
                        <option value="Perth and Kinross">Perth and Kinross</option>
                        <option value="Renfrewshire">Renfrewshire</option>
                        <option value="Scottish Borders">Scottish Borders</option>
                        <option value="Shetland Islands">Shetland Islands</option>
                        <option value="South Ayrshire">South Ayrshire</option>
                        <option value="South Lanarkshire">South Lanarkshire</option>
                        <option value="Stirling">Stirling</option>
                        <option value="West Dunbartonshire">West Dunbartonshire</option>
                        <option value="West Lothian">West Lothian</option>
                    </select>
                </div>
                <div>
                    <span class="relative z-0 inline-flex shadow-sm rounded-md">
                        <button
                            type="button"
                            class="relative inline-flex items-center px-4 py-2 rounded-l-md border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                            :class="{ 'active': timescale === 'alltime' }"
                            @click="changeTimescale('alltime')">
                            All Time
                        </button>
                        <button
                            type="button"
                            class="-ml-px relative inline-flex items-center px-4 py-2 rounded-r-md border border-gray-300 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:z-10 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                            :class="{ 'active': timescale === '30days' }"
                            @click="changeTimescale('30days')">
                            Last 30 Days
                        </button>
                    </span>
                </div>
            </div>
        </header>

        <div class="mb-10">
            <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-3">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dl>
                        <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
                            Total New Cases
                        </dt>
                        <dd class="mt-1 text-3xl leading-9 font-semibold text-gray-900">
                            <span id="total-cases"></span>
                        </dd>
                    </dl>
                </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dl>
                        <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
                            Total Hospital Admissions
                        </dt>
                        <dd class="mt-1 text-3xl leading-9 font-semibold text-gray-900">
                            <span id="total-admissions"></span>
                        </dd>
                    </dl>
                </div>
                </div>
                <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <dl>
                        <dt class="text-sm leading-5 font-medium text-gray-500 truncate">
                            Total Deaths
                        </dt>
                        <dd class="mt-1 text-3xl leading-9 font-semibold text-gray-900">
                            <span id="total-deaths"></span>
                        </dd>
                    </dl>
                </div>
                </div>
            </div>
        </div>


        <div class="bg-white overflow-hidden shadow rounded-lg mb-10">
            <div class="px-4 py-5 sm:p-6">
                <h2>New Cases</h2>
                <div id="chart-cases"></div>
            </div>
        </div>

        <div class="md:grid md:gap-4 lg:gap-10 md:grid-cols-2">
            <div>
                <div class="bg-white overflow-hidden shadow rounded-lg mb-10">
                    <div class="px-4 py-5 sm:p-6">
                        <h2>Hospital Admissions</h2>
                        <div id="chart-admissions"></div>
                    </div>
                </div>
            </div>
            <div>
                <div class="bg-white overflow-hidden shadow rounded-lg mb-10">
                    <div class="px-4 py-5 sm:p-6">
                        <h2>Deaths</h2>
                        <div id="chart-deaths"></div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="text-sm text-gray-400 leading-7 mt-4">
            <p>Created by <a href="https://gilbitron.me" target="_blank">Gilbert Pellegrom</a> from <a href="https://dev7studios.co" target="_blank">Dev7studios</a>.</p>
            <p>Data supplied by the <a href="https://coronavirus.data.gov.uk/" target="_blank">UK Government</a> under the <a href="https://www.nationalarchives.gov.uk/doc/open-government-licence/version/3/" target="_blank">OGL license</a>.</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.0/dist/alpine.min.js" defer></script>
    <script src="https://unpkg.com/frappe-charts@1.5.2/dist/frappe-charts.min.iife.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/luxon@1.25.0/build/global/luxon.min.js"></script>
    <script>
        function component() {
            return {
                responseData: null,
                timescale: 'alltime',
                charts: [],
                init() {
                    const queryParams = new URLSearchParams(window.location.search);
                    const initialArea = queryParams.get('area');
                    this.getData(initialArea ? initialArea : '');

                    const selectElement = document.getElementById('select_area');
                    selectElement.addEventListener('change', (event) => {
                        const area = event.target.value;
                        this.getData(area);

                        let queryParams = new URLSearchParams(window.location.search);
                        queryParams.set('area', area);
                        history.replaceState(null, null, area ? '?' + queryParams.toString() : '/');

                        this.updateTitle(area);
                    });
                },
                getData(area = '') {
                    const selectElement = document.getElementById('select_area');
                    selectElement.disabled = true;

                    fetch('/data.php?area=' + encodeURI(area))
                        .then(response => response.json())
                        .then(result => {
                            this.responseData = result.data;
                            this.updateData();
                        })
                        .catch(error => {
                            console.error(error);
                        })
                        .finally(() => {
                            const selectElement = document.getElementById('select_area');
                            selectElement.disabled = false;
                        });
                },
                updateData() {
                    let data = JSON.parse(JSON.stringify(this.responseData));

                    if (this.timescale === 'alltime') {
                        data = data.reverse();
                    } else {
                        data = data.slice(0, 30).reverse();
                    }

                    const labels = this.getLabels(data);
                    const newCases = this.getValues(data, 'newCases');
                    const newAdmissions = this.getValues(data, 'newAdmissions');
                    const newDeaths = this.getValues(data, 'newDeaths');

                    const totalCases = newCases.reduce((sum, val) => sum + val, 0);
                    document.getElementById('total-cases').innerText = totalCases.toLocaleString();
                    const totalAdmissions = newAdmissions.reduce((sum, val) => sum + val, 0);
                    document.getElementById('total-admissions').innerText = totalAdmissions.toLocaleString();
                    const totalDeaths = newDeaths.reduce((sum, val) => sum + val, 0);
                    document.getElementById('total-deaths').innerText = totalDeaths.toLocaleString();

                    this.createChart('cases', {
                        labels: labels,
                        datasets: [{ name: 'New Cases', values: newCases }]
                    }, '#4299E1');
                    this.createChart('admissions', {
                        labels: labels,
                        datasets: [{ name: 'Admissions', values: newAdmissions }]
                    }, '#ECC94B');
                    this.createChart('deaths', {
                        labels: labels,
                        datasets: [{ name: 'Deaths', values: newDeaths }]
                    }, '#F56565');
                },
                updateTitle(area) {
                    let title = 'Coronavirus (COVID-19) statistics for ' + (area ? area : 'Scotland');
                    document.getElementById('page_title').innerText = title;
                    document.title = title;
                },
                getLabels(data) {
                    return data.map(item => {
                        return luxon.DateTime.fromISO(item.date).toFormat('d LLL');
                    });
                },
                getValues(data, key) {
                    return data.map(item => {
                        return item[key];
                    });
                },
                createChart(id, chartData, color) {
                    if (typeof this.charts[id] === 'undefined') {
                        this.charts[id] = new frappe.Chart('#chart-'+id, {
                            data: chartData,
                            height: 350,
                            animate: false,
                            type: 'bar',
                            colors: [color],
                            barOptions: {
                                spaceRatio: 0.1
                            },
                            axisOptions: {
                                xAxisMode: 'tick',
                                xIsSeries: true
                            }
                        });
                    } else {
                        this.charts[id].update(chartData);
                    }
                },
                changeTimescale(newTimescale) {
                    this.timescale = newTimescale;
                    this.updateData();
                }
            }
        }
    </script>
</body>
</html>
