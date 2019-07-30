class Common{

	static allStatus(then) {
		return axios.get('/project/getStatus/')
			.then(({data}) => then(data));
	}

	static allPriority(then) {
		return axios.get('/project/getPriority/')
			.then(({data}) => then(data));
	}
	
}

export default Common;