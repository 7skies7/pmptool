class Report{
	
	static getResourceReport(then) {
		return axios.get('/report/resourcereport')
			.then(({data}) => then(data));
	}

	static getAllProjects(then) {
		return axios.get('/project')
			.then(({data}) => then(data));
	}

	static getAllResources(then) {
		return axios.get('/company/getResources')
			.then(({data}) => then(data));
	}
}

export default Report;