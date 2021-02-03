function component() {
    return {
        responseData: null,
        charts: [],
        validAreas: [
            'Aberdeen City',
            'Aberdeenshire',
            'Angus',
            'Argyll and Bute',
            'Clackmannanshire',
            'Dumfries and Galloway',
            'Dundee City',
            'East Ayrshire',
            'East Dunbartonshire',
            'East Lothian',
            'East Renfrewshire',
            'City of Edinburgh',
            'Falkirk',
            'Fife',
            'Glasgow City',
            'Highland',
            'Inverclyde',
            'Midlothian',
            'Moray',
            'Na h-Eileanan Siar',
            'North Ayrshire',
            'North Lanarkshire',
            'Orkney Islands',
            'Perth and Kinross',
            'Renfrewshire',
            'Scottish Borders',
            'Shetland Islands',
            'South Ayrshire',
            'South Lanarkshire',
            'Stirling',
            'West Dunbartonshire',
            'West Lothian',
        ],
        selectedArea: '',
        validTimescales: ['alltime', '30days'],
        timescale: 'alltime',
        init() {
            this.$watch('selectedArea', value => this.onSelectedAreaChange(value));

            const queryParams = new URLSearchParams(window.location.search);
            const initialArea = queryParams.get('area');
            const initialTimescale = queryParams.get('timescale');

            if (this.validTimescales.includes(initialTimescale)) {
                this.timescale = initialTimescale;
            }

            if (this.validAreas.includes(initialArea)) {
                this.selectedArea = initialArea;
            } else {
                this.getData();
                this.updateTitle();
            }
        },
        onSelectedAreaChange() {
            this.getData();
            this.updateTitle();
            this.updateQueryParams();
        },
        getData() {
            this.$refs.selectAreaInput.disabled = true;

            fetch('/data-v2.php?area=' + encodeURI(this.selectedArea))
                .then(response => response.json())
                .then(result => {
                    this.responseData = result.data || result.body;
                    this.updateData();
                })
                .catch(error => {
                    console.error(error);
                })
                .finally(() => {
                    this.$refs.selectAreaInput.disabled = false;
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
            const newCases = this.getValues(data, 'newCasesByPublishDate');
            const newAdmissions = this.getValues(data, 'newAdmissions');
            const newDeaths = this.getValues(data, 'newDeaths28DaysByPublishDate');
            const newVaccinations = this.getValues(data, 'newPeopleVaccinatedFirstDoseByPublishDate');
            const cumVaccinations = this.getValues(data, 'cumPeopleVaccinatedFirstDoseByPublishDate');

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
            this.createChart('new-vaccinations', {
                labels: labels,
                datasets: [{ name: 'New', values: newVaccinations }]
            }, '#1cb733');
            this.createChart('cum-vaccinations', {
                labels: labels,
                datasets: [{ name: 'Cumulative', values: cumVaccinations }]
            }, '#6e46f9');
        },
        updateTitle() {
            let title = 'Coronavirus (COVID-19) statistics for ' + (this.selectedArea ? this.selectedArea : 'Scotland');
            document.getElementById('page_title').innerText = title;
            document.title = title;
        },
        updateQueryParams() {
            let queryParams = new URLSearchParams(window.location.search);
            queryParams.set('area', this.selectedArea);
            queryParams.set('timescale', this.timescale);
            history.replaceState(null, null, '?' + queryParams.toString());
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
        createChart(id, chartData, color, type='bar') {
            if (id === 'admissions' && this.selectedArea != '') {
                return;
            }

            if (typeof this.charts[id] === 'undefined') {
                this.charts[id] = new frappe.Chart('#chart-'+id, {
                    data: chartData,
                    height: 350,
                    animate: false,
                    type: type,
                    colors: Array.isArray(color) ? color : [color],
                    barOptions: {
                        spaceRatio: 0.1,
                    },
                    lineOptions: {
                        hideDots: 1,
                        regionFill: 1,
                    },
                    axisOptions: {
                        xAxisMode: 'tick',
                        xIsSeries: true
                    },
                    tooltipOptions: {
                        formatTooltipY: function(d) {
                            return d ? d.toLocaleString() : d;
                        },
                    }
                });
            } else {
                this.charts[id].update(chartData);
            }
        },
        changeTimescale(newTimescale) {
            this.timescale = newTimescale;
            this.updateData();
            this.updateQueryParams();
        }
    }
}
